<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Rapor;
use App\Models\Semester;
use App\Models\PerkembanganFisik;
use App\Models\JurnalHarian;
use App\Models\Materi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WaliMuridController extends Controller
{
    // TM-37: Helper to get all children linked to logged in parent
    private function getChildren()
    {
        $user = Auth::user();
        return Siswa::where('email', $user->email)
            ->orWhere('user_id', $user->id)
            ->orWhereHas('user', function($q) use ($user) {
                $q->where('users.id', $user->id);
            })->get();
    }

    private function getActiveChild($requestedChildId = null)
    {
        $children = $this->getChildren();

        if ($children->isEmpty()) {
            return null;
        }

        $childId = $requestedChildId ?: session('active_child_id');

        if ($childId) {
            $child = $children->where('id', $childId)->first();
            if (!$child) {
                // Safe fallback to first child if requested child ID does not belong to parent
                $defaultChild = $children->first();
                session(['active_child_id' => $defaultChild->id]);
                return $defaultChild;
            }
            session(['active_child_id' => $child->id]);
            return $child;
        }

        $defaultChild = $children->first();
        session(['active_child_id' => $defaultChild->id]);
        return $defaultChild;
    }

    // TM-37: Switch Active Child in session
    public function switchChild(Request $request)
    {
        $request->validate(['child_id' => 'required|integer']);

        $children = $this->getChildren();
        $child = $children->where('id', $request->child_id)->first();

        if (!$child) {
            return back()->with('error', 'Anak tidak ditemukan dalam daftar Anda.');
        }

        session(['active_child_id' => $child->id]);
        return back()->with('success', "Berhasil beralih ke data anak: {$child->nama_lengkap}");
    }

    private function checkEncryptedRedirect(Request $request, $siswa = null)
    {
        if ($request->route('kode_yayasan')) {
            return null;
        }

        $user = Auth::user();
        $yayasan = null;

        if ($siswa && $siswa->school_id) {
            $sch = \App\Models\School::find($siswa->school_id);
            if ($sch && $sch->yayasan_id) {
                $yayasan = \App\Models\Yayasan::find($sch->yayasan_id);
            }
        }

        if (!$yayasan && $user) {
            $yId = method_exists($user, 'getUserYayasanId') ? $user->getUserYayasanId() : ($user->yayasan_id ?? null);
            if ($yId) {
                $yayasan = \App\Models\Yayasan::find($yId);
            }
        }

        if (!$yayasan) {
            $children = $this->getChildren();
            if ($children->isNotEmpty()) {
                $sch = \App\Models\School::find($children->first()->school_id);
                if ($sch && $sch->yayasan_id) {
                    $yayasan = \App\Models\Yayasan::find($sch->yayasan_id);
                }
            }
        }

        if (!$yayasan) {
            $yayasan = \App\Models\Yayasan::where('status', 'aktif')->first();
        }

        if ($yayasan) {
            $routeName = $request->route()->getName();
            $targetRouteName = 'yayasan.' . $routeName;
            if (\Illuminate\Support\Facades\Route::has($targetRouteName)) {
                $params = array_merge(['kode_yayasan' => $yayasan->encrypted_token], $request->route()->parameters());
                return redirect()->route($targetRouteName, $params);
            }
        }

        return null;
    }

    // TM-37: Rapor Anak
    public function rapor(Request $request, $id = null)
    {
        $siswa = $this->getActiveChild($id);
        if (!$siswa) {
            return redirect('/dashboard')->with('error', 'Belum ada data anak yang terhubung.');
        }

        if ($redirect = $this->checkEncryptedRedirect($request, $siswa)) {
            return $redirect;
        }

        $semesterId = session('active_semester_id') ?: optional(Semester::where('is_active', true)->first())->id;
        $semesterAktif = $semesterId ? Semester::find($semesterId) : Semester::where('is_active', true)->first();

        $rapor = $semesterAktif ? Rapor::where('siswa_id', $siswa->id)->where('semester_id', $semesterAktif->id)->first() : null;

        return view('walimurid.rapor', compact('siswa', 'rapor', 'semesterAktif'));
    }

    // TM-37: Perkembangan Fisik Anak
    public function perkembangan(Request $request, $id = null)
    {
        $siswa = $this->getActiveChild($id);
        if (!$siswa) {
            return redirect('/dashboard')->with('error', 'Belum ada data anak yang terhubung.');
        }

        if ($redirect = $this->checkEncryptedRedirect($request, $siswa)) {
            return $redirect;
        }

        $riwayat = PerkembanganFisik::where('siswa_id', $siswa->id)->orderBy('tgl_periksa', 'desc')->get();
        return view('walimurid.perkembangan', compact('siswa', 'riwayat'));
    }

    // TM-18 & TM-37: Jurnal Harian Anak
    public function jurnal(Request $request, $id = null)
    {
        $siswa = $this->getActiveChild($id);
        if (!$siswa) {
            return redirect('/dashboard')->with('error', 'Belum ada data anak yang terhubung.');
        }

        if ($redirect = $this->checkEncryptedRedirect($request, $siswa)) {
            return $redirect;
        }

        $jurnals = JurnalHarian::where('siswa_id', $siswa->id)
            ->latest('tanggal')
            ->paginate(15);

        return view('walimurid.jurnal', compact('siswa', 'jurnals'));
    }

    // TM-37: Tautkan Anak via NISN
    public function storeTautkanAnak(Request $request)
    {
        $request->validate(['nisn' => 'required|string|max:20']);

        $user = Auth::user();
        $siswa = Siswa::where('nisn', $request->nisn)->first();

        if (!$siswa) {
            return back()->with('error', 'NISN tidak ditemukan di database sekolah.');
        }

        $siswa->update(['email' => $user->email]);

        if (DB::getSchemaBuilder()->hasTable('student_guardian')) {
            DB::table('student_guardian')->updateOrInsert(
                ['siswa_id' => $siswa->id, 'user_id' => $user->id],
                ['peran' => 'wali', 'is_wali_utama' => true, 'updated_at' => now()]
            );
        }

        session(['active_child_id' => $siswa->id]);

        return back()->with('success', "Anak {$siswa->nama_lengkap} berhasil ditautkan ke akun Anda!");
    }
}
