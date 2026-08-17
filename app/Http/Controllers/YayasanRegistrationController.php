<?php

namespace App\Http\Controllers;

use App\Models\YayasanRegistration;
use App\Models\Yayasan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class YayasanRegistrationController extends Controller
{
    /**
     * GET / — Halaman landing page utama SaaS Yayasan (publik).
     */
    public function landing()
    {
        return view('landing_utama');
    }

    /**
     * GET /daftar-yayasan — Halaman form pendaftaran yayasan baru khusus (dedicated page).
     */
    public function showForm()
    {
        return view('yayasan_registration');
    }

    /**
     * POST /daftar-yayasan — Simpan pendaftaran yayasan ke antrian.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_yayasan'          => 'required|string|max:255',
            'penanggung_jawab'      => 'required|string|max:255',
            'email_kontak'          => 'required|email|max:255',
            'telepon'               => 'required|string|max:20',
            'alamat'                => 'required|string',
            'lintang'               => 'nullable|numeric',
            'bujur'                 => 'nullable|numeric',
            'nomor_akta_pendirian'  => 'nullable|string|max:100',
            'nomor_sk_kemenkumham'  => 'nullable|string|max:100',
            'npwp_yayasan'          => 'nullable|string|max:30',
        ], [
            'nama_yayasan.required'     => 'Nama yayasan wajib diisi.',
            'penanggung_jawab.required' => 'Nama penanggung jawab wajib diisi.',
            'email_kontak.required'     => 'Email kontak wajib diisi.',
            'email_kontak.email'        => 'Format email tidak valid.',
            'telepon.required'          => 'Nomor telepon wajib diisi.',
            'alamat.required'           => 'Alamat yayasan wajib diisi.',
        ]);

        YayasanRegistration::create([
            'nama_yayasan'         => $request->nama_yayasan,
            'penanggung_jawab'     => $request->penanggung_jawab,
            'email_kontak'         => $request->email_kontak,
            'telepon'              => $request->telepon,
            'alamat'               => $request->alamat,
            'lintang'              => $request->lintang,
            'bujur'                => $request->bujur,
            'nomor_akta_pendirian' => $request->nomor_akta_pendirian,
            'nomor_sk_kemenkumham' => $request->nomor_sk_kemenkumham,
            'npwp_yayasan'         => $request->npwp_yayasan,
            'status'               => 'menunggu',
        ]);

        return back()->with('success', 'Pendaftaran yayasan berhasil dikirim! Tim kami akan memverifikasi dan menghubungi Anda dalam 1–3 hari kerja melalui email atau nomor telepon yang Anda cantumkan.');
    }

    /**
     * GET /owner/yayasan-registrations — Daftar antrian pendaftaran (owner only).
     */
    public function index()
    {
        $registrations = YayasanRegistration::latest()->paginate(20);
        $total_menunggu = YayasanRegistration::where('status', 'menunggu')->count();
        return view('owner.yayasan_registrations.index', compact('registrations', 'total_menunggu'));
    }

    /**
     * POST /owner/yayasan-registrations/{id}/approve — ACC pendaftaran yayasan.
     * - Buat record di tabel yayasans
     * - Buat akun super_admin untuk yayasan
     * - Update status ke disetujui
     */
    public function approve(Request $request, $id)
    {
        $reg = YayasanRegistration::findOrFail($id);

        if (!$reg->isMenunggu()) {
            return back()->with('error', 'Pendaftaran ini sudah diproses sebelumnya.');
        }

        $request->validate([
            'kode_yayasan' => 'required|string|max:20|unique:yayasans,kode_yayasan',
        ], [
            'kode_yayasan.required' => 'Kode yayasan wajib diisi sebelum ACC.',
            'kode_yayasan.unique'   => 'Kode yayasan ini sudah dipakai. Coba kode lain.',
        ]);

        // 1. Buat yayasan baru
        $yayasan = Yayasan::create([
            'nama_yayasan'         => $reg->nama_yayasan,
            'kode_yayasan'         => strtoupper($request->kode_yayasan),
            'penanggung_jawab'     => $reg->penanggung_jawab,
            'telepon'              => $reg->telepon,
            'alamat'               => $reg->alamat,
            'status'               => 'aktif',
        ]);

        // 2. Buat akun super_admin untuk yayasan ini
        $rawPassword = Str::random(10);
        $superAdmin = User::create([
            'name'       => 'Admin ' . $reg->nama_yayasan,
            'email'      => $reg->email_kontak,
            'password'   => Hash::make($rawPassword),
            'role'       => 'super_admin',
            'yayasan_id' => $yayasan->id,
        ]);

        // 3. Update status pendaftaran
        $reg->update([
            'status'       => 'disetujui',
            'kode_yayasan' => strtoupper($request->kode_yayasan),
            'catatan_owner'=> $request->catatan_owner,
            'diproses_at'  => now(),
        ]);

        $landingUrl = url('/y/' . $yayasan->kode_yayasan);

        return back()->with('success',
            "Yayasan \"{$yayasan->nama_yayasan}\" berhasil diaktifkan! " .
            "Akun Super Admin dibuat: email = {$reg->email_kontak}, password sementara = {$rawPassword}. " .
            "Landing page yayasan: {$landingUrl}"
        );
    }

    /**
     * POST /owner/yayasan-registrations/{id}/reject — Tolak pendaftaran.
     */
    public function reject(Request $request, $id)
    {
        $reg = YayasanRegistration::findOrFail($id);

        if (!$reg->isMenunggu()) {
            return back()->with('error', 'Pendaftaran ini sudah diproses sebelumnya.');
        }

        $request->validate([
            'catatan_owner' => 'required|string|max:500',
        ], [
            'catatan_owner.required' => 'Alasan penolakan wajib diisi.',
        ]);

        $reg->update([
            'status'        => 'ditolak',
            'catatan_owner' => $request->catatan_owner,
            'diproses_at'   => now(),
        ]);

        return back()->with('success', 'Pendaftaran yayasan "' . $reg->nama_yayasan . '" telah ditolak.');
    }
}
