<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Semester;
use App\Models\AnggotaKelas;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SiswaController extends Controller
{
    // TM-26, TM-38: TAMPILKAN DAFTAR SISWA DENGAN SERVER-SIDE PAGINASI & FILTER & CARI
    public function index(Request $request)
    {
        $user = Auth::user();
        $schoolId = session('active_school_id') ?? $user->school_id;

        // Mode Pusat for SuperAdmin (TM-39)
        if (!$schoolId && ($user->isSuperAdmin() || $user->isOwner())) {
            $ringkasan = DB::table('siswas')
                ->select('school_id', DB::raw('count(*) as total'))
                ->groupBy('school_id')
                ->get()
                ->map(function($r) {
                    $r->sekolah = DB::table('schools')->where('id', $r->school_id)->first();
                    return $r;
                });

            return view('superadmin.siswa_ringkasan', compact('ringkasan'));
        }

        $semesterAktif = Semester::where('is_active', true)->first();

        $query = Siswa::with('kelasAktif')->where('school_id', $schoolId);

        // Filter berdasarkan peran & kelas
        if ($user->role === 'guru' && $user->kelas_id) {
            $query->whereHas('kelas', function($q) use ($user, $semesterAktif) {
                $q->where('anggota_kelas.kelas_id', $user->kelas_id);
                if ($semesterAktif) {
                    $q->where('anggota_kelas.semester_id', $semesterAktif->id);
                }
            })->where('status', 'aktif');
        } else {
            if ($request->kelas_id == 'none') {
                $query->whereDoesntHave('kelas', function($q) use ($semesterAktif) {
                    if ($semesterAktif) $q->where('anggota_kelas.semester_id', $semesterAktif->id);
                })->where('status', 'aktif');
            } elseif ($request->kelas_id == 'alumni') {
                $query->where('status', 'lulus');
            } elseif ($request->kelas_id == 'menunggu') {
                $query->where('status', 'menunggu');
            } elseif ($request->filled('kelas_id') && $request->kelas_id != 'all') {
                $query->whereHas('kelas', function($q) use ($request, $semesterAktif) {
                    $q->where('anggota_kelas.kelas_id', $request->kelas_id);
                    if ($semesterAktif) $q->where('anggota_kelas.semester_id', $semesterAktif->id);
                })->where('status', 'aktif');
            } else {
                $query->where('status', 'aktif');
            }
        }

        if ($request->filled('tahun_inden')) {
            $query->where('program_pilihan', 'LIKE', '%(Tahun: ' . $request->tahun_inden . ')%');
        }

        // TM-38: Server-side search box
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function($sub) use ($q) {
                $sub->where('nama_lengkap', 'LIKE', "%{$q}%")
                    ->orWhere('nik', 'LIKE', "%{$q}%")
                    ->orWhere('nisn', 'LIKE', "%{$q}%");
            });
        }

        // TM-38: Whitelist per_page to prevent DoS
        $perPage = in_array((int) $request->per_page, [10, 25, 50, 100]) ? (int) $request->per_page : 25;

        $siswas = $query->orderBy('nama_lengkap')->paginate($perPage)->withQueryString();
        $list_kelas = Kelas::where('school_id', $schoolId)->get();

        $list_tahun_inden = Siswa::where('school_id', $schoolId)
            ->whereNotNull('program_pilihan')
            ->pluck('program_pilihan')
            ->map(function($prog) {
                preg_match('/\(Tahun: (.*?)\)/', $prog, $matches);
                return isset($matches[1]) ? $matches[1] : null;
            })
            ->filter()
            ->unique()
            ->sort()
            ->values();

        return view('data_siswa', compact('siswas', 'list_kelas', 'semesterAktif', 'list_tahun_inden'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nik' => 'required|string',
        ]);

        $schoolId = session('active_school_id') ?? Auth::user()->school_id;

        $siswaBaru = Siswa::create([
            'school_id' => $schoolId,
            'nama_lengkap' => $request->nama_lengkap,
            'nik' => $request->nik,
            'status' => 'aktif'
        ]);

        if ($request->filled('kelas_id')) {
            $semesterAktif = Semester::where('is_active', true)->first();
            if ($semesterAktif) {
                AnggotaKelas::create([
                    'siswa_id' => $siswaBaru->id,
                    'kelas_id' => $request->kelas_id,
                    'semester_id' => $semesterAktif->id
                ]);
            }
        }

        return back()->with('success', 'Siswa baru berhasil ditambahkan!');
    }

    public function setKelas(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'kelas_id' => 'required|exists:kelas,id'
        ]);

        $semesterAktif = Semester::where('is_active', true)->first();
        if (!$semesterAktif) {
            return back()->with('error', 'Gagal! Belum ada semester yang aktif.');
        }

        AnggotaKelas::updateOrCreate(
            [
                'siswa_id' => $request->siswa_id,
                'semester_id' => $semesterAktif->id
            ],
            [
                'kelas_id' => $request->kelas_id
            ]
        );

        return back()->with('success', 'Siswa berhasil dimasukkan ke kelas!');
    }

    // TM-28: Multi-guardian linking & auto-fetch parent name
    public function tautkanOrtu(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'email'    => 'required|email'
        ]);

        $siswa = Siswa::findOrFail($request->siswa_id);
        
        // Auto-fetch parent name from pendaftaran if available
        $parentUser = User::where('email', $request->email)->first();
        
        $siswa->update([
            'email' => $request->email,
            'nama_ortu' => $parentUser ? $parentUser->name : ($siswa->nama_ibu ?: $siswa->nama_ayah ?: $siswa->nama_ortu)
        ]);

        // TM-28: Sync pivot student_guardian if table exists
        if (Schema::hasTable('student_guardian') && $parentUser) {
            DB::table('student_guardian')->updateOrInsert(
                ['siswa_id' => $siswa->id, 'user_id' => $parentUser->id],
                ['peran' => 'wali', 'is_wali_utama' => true, 'updated_at' => now()]
            );
        }

        return back()->with('success', 'Akun orang tua berhasil ditautkan ke data ' . $siswa->nama_lengkap . '!');
    }

    // TM-04: IDOR Protection via Policy
    public function show($id)
    {
        $siswa = Siswa::with('kelasAktif')->findOrFail($id);
        
        // IDOR Authorization check
        $this->authorize('view', $siswa);

        return view('siswa_detail', compact('siswa'));
    }

    public function createPendaftaran(Request $request)
    {
        $user = Auth::user();
        $schools = \App\Models\School::where('status', 'aktif')->get();

        $selected_school_id = $request->query('school_id');
        $is_locked = false;

        // TM-31: Admin Context — lock school_id to current admin's school
        if ($user && !$user->isSuperAdmin()) {
            if ($user->school_id) {
                $selected_school_id = $user->school_id;
                $is_locked = true;
            }
        }

        $school = null;
        $list_tahun_inden = [];
        $kuota_per_tahun = [];

        if ($selected_school_id) {
            $school = DB::table('schools')->where('id', $selected_school_id)->first();
            if ($school) {
                $tahunMulai = date('m') <= 6 ? date('Y') : date('Y') + 1;
                $max_years = $school->max_indent_years ?? 3;

                for ($i = 0; $i < $max_years; $i++) {
                    $thn1 = $tahunMulai + $i;
                    $thn2 = $thn1 + 1;
                    $tahunString = $thn1 . '/' . $thn2;
                    $list_tahun_inden[] = $tahunString;

                    $terisi_tk_pagi = DB::table('siswas')->where('school_id', $school->id)->where('program_pilihan', 'LIKE', 'TK Pagi%'.$tahunString.'%')->where('status', '!=', 'lulus')->count();
                    $terisi_tk_siang = DB::table('siswas')->where('school_id', $school->id)->where('program_pilihan', 'LIKE', 'TK Siang%'.$tahunString.'%')->where('status', '!=', 'lulus')->count();
                    $terisi_paud = DB::table('siswas')->where('school_id', $school->id)->where('program_pilihan', 'LIKE', 'PAUD%'.$tahunString.'%')->where('status', '!=', 'lulus')->count();

                    $kuota_per_tahun[$tahunString] = [
                        'tk_pagi' => max(0, ($school->kuota_tk_pagi ?? 30) - $terisi_tk_pagi),
                        'tk_siang' => max(0, ($school->kuota_tk_siang ?? 30) - $terisi_tk_siang),
                        'paud' => max(0, ($school->kuota_paud ?? 30) - $terisi_paud),
                    ];
                }
            }
        }

        return view('pendaftaran', compact('schools', 'school', 'selected_school_id', 'is_locked', 'list_tahun_inden', 'kuota_per_tahun'));
    }

    public function storePendaftaran(Request $request)
    {
        $isAdmin = Auth::check() && !Auth::user()->isWaliMurid();

        // TM-31: Proof of payment optional for internal Admin entries
        $rules = [
            'school_id'        => 'required|exists:schools,id',
            'program_pilihan'  => 'required|string',
            'tahun_inden'      => 'required|string',
            'nama_lengkap'     => 'required|string|max:255',
            'nik'              => 'required|string',
            'no_kk'            => 'required|string',
            'tempat_lahir'     => 'required|string|max:255',
            'tgl_lahir'        => 'required|date',
            'lintang'          => 'nullable|string',
            'bujur'            => 'nullable|string',
            'foto_anak'        => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'foto_kk'          => 'nullable|mimes:jpeg,png,jpg,pdf|max:2048',
            'foto_akte'        => 'nullable|mimes:jpeg,png,jpg,pdf|max:2048',
            'bukti_pembayaran' => $isAdmin ? 'nullable|image|mimes:jpeg,png,jpg|max:2048' : 'required|image|mimes:jpeg,png,jpg|max:2048',
        ];

        $request->validate($rules);

        $program_gabungan = $request->program_pilihan . ' (Tahun: ' . $request->tahun_inden . ')';

        $fotoPath = $request->hasFile('foto_anak') ? $request->file('foto_anak')->store('foto_siswa', 'public') : null;
        $buktiPath = $request->hasFile('bukti_pembayaran') ? $request->file('bukti_pembayaran')->store('bukti_pendaftaran', 'public') : null;

        if ($request->hasFile('foto_kk')) $request->file('foto_kk')->store('dokumen_siswa', 'public');
        if ($request->hasFile('foto_akte')) $request->file('foto_akte')->store('dokumen_siswa', 'public');

        $koordinat_gabungan = ($request->lintang && $request->bujur) ? ($request->lintang . ', ' . $request->bujur) : '-';

        $siswaId = DB::table('siswas')->insertGetId([
            'school_id'       => $request->school_id,
            'program_pilihan' => $program_gabungan,
            'nama_lengkap'    => $request->nama_lengkap,
            'nik'             => $request->nik,
            'no_kk'           => $request->no_kk,
            'jenis_kelamin'   => $request->jenis_kelamin ?? 'L',
            'tempat_lahir'    => $request->tempat_lahir,
            'tgl_lahir'       => $request->tgl_lahir,
            'agama'           => $request->agama ?? 'Islam',
            'anak_ke'         => $request->anak_ke ?? 1,
            'jml_saudara'     => $request->jml_saudara ?? 1,
            'alamat'          => $request->alamat,
            'rt'              => $request->rt,
            'rw'              => $request->rw,
            'dusun'           => $request->dusun,
            'kelurahan'       => $request->kelurahan,
            'kecamatan'       => $request->kecamatan,
            'kode_pos'        => $request->kode_pos,
            'hp'              => $request->hp,
            'nama_ayah'       => $request->nama_ayah,
            'nik_ayah'        => $request->nik_ayah,
            'nama_ibu'        => $request->nama_ibu,
            'nik_ibu'         => $request->nik_ibu,
            'tinggi_badan'    => $request->tinggi_badan,
            'berat_badan'     => $request->berat_badan,
            'lingkar_kepala'  => $request->lingkar_kepala,
            'koordinat_rumah' => $koordinat_gabungan,
            'lintang'         => $request->lintang ?? '0',
            'bujur'           => $request->bujur ?? '0',
            'foto_anak'       => $fotoPath,
            'bukti_pembayaran'=> $buktiPath,
            'email'           => $request->email,
            'status'          => $isAdmin ? 'aktif' : 'menunggu',
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        return back()->with('success', 'Pendaftaran berhasil disimpan!');
    }

    public function importNisn(Request $request)
    {
        $request->validate([
            'file_csv' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('file_csv');
        $fileContent = file_get_contents($file->path());
        $lines = explode("\n", $fileContent);

        $berhasil_update = 0;
        $di_skip = 0;

        $user = Auth::user();
        $schoolId = session('active_school_id') ?? $user->school_id;

        for ($i = 5; $i < count($lines); $i++) {
            if (trim($lines[$i]) == '') continue;

            $delimiter = strpos($lines[$i], ';') !== false ? ';' : ',';
            $data = str_getcsv($lines[$i], $delimiter);

            if (count($data) < 10) continue;

            $nama     = isset($data[1]) ? trim($data[1]) : null;
            $no_induk = isset($data[2]) ? trim($data[2]) : null;
            $nisn     = isset($data[4]) ? trim($data[4]) : null;
            $nik      = isset($data[7]) ? trim($data[7]) : null;

            if (!$nama) continue;

            $siswa = Siswa::where('school_id', $schoolId)
                        ->where(function($q) use ($nik, $nama) {
                            if ($nik && $nik != '') $q->where('nik', $nik);
                            else $q->where('nama_lengkap', 'LIKE', "%{$nama}%");
                        })->first();

            if ($siswa) {
                $updateData = [];
                if ($nisn && $nisn !== '') $updateData['nisn'] = $nisn;
                if ($no_induk && $no_induk !== '') $updateData['no_induk'] = $no_induk;

                if (!empty($updateData)) {
                    $siswa->update($updateData);
                    $berhasil_update++;
                }
            } else {
                $di_skip++;
            }
        }

        return back()->with('success', "Import Dapodik Selesai! Berhasil Update: $berhasil_update siswa.");
    }

    public function terimaPendaftaran($id)
    {
        DB::table('siswas')->where('id', $id)->update([
            'status' => 'aktif',
            'updated_at' => now()
        ]);

        return back()->with('success', 'Pendaftaran berhasil diverifikasi! Siswa sekarang berstatus Aktif.');
    }
}