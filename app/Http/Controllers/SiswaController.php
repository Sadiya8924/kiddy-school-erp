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
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

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
        Gate::authorize('view', $siswa);

        return view('siswa_detail', compact('siswa'));
    }

    public function createPendaftaran(Request $request)
    {
        $user = Auth::user();

        // 1. Tentukan yayasan_id & resolve encrypted hash token:
        $yayasan_id = null;
        $routeParam = $request->route('kode_yayasan');

        if ($routeParam) {
            $decryptedCode = \App\Models\Yayasan::decryptToken($routeParam);
            $y = \App\Models\Yayasan::where('kode_yayasan', $decryptedCode ?: $routeParam)->first();
            if ($y) $yayasan_id = $y->id;
        } elseif ($user && $user->yayasan_id) {
            $yayasan_id = $user->yayasan_id;
        } elseif ($request->filled('y') || $request->filled('token')) {
            $rawToken = $request->query('y') ?? $request->query('token');
            $decryptedCode = \App\Models\Yayasan::decryptToken($rawToken);
            $y = \App\Models\Yayasan::where('kode_yayasan', $decryptedCode ?: $rawToken)->first();
            if ($y) $yayasan_id = $y->id;
        } elseif ($request->filled('kode_yayasan')) {
            $decryptedCode = \App\Models\Yayasan::decryptToken($request->kode_yayasan);
            $y = \App\Models\Yayasan::where('kode_yayasan', $decryptedCode ?: $request->kode_yayasan)->first();
            if ($y) $yayasan_id = $y->id;
        } elseif ($request->filled('yayasan_id')) {
            $yayasan_id = $request->yayasan_id;
        } elseif ($request->filled('school_id')) {
            $sch = DB::table('schools')->where('id', $request->school_id)->first();
            if ($sch) $yayasan_id = $sch->yayasan_id;
        }

        // 2. Query sekolah HANYA dari yayasan tersebut (jika terdeteksi)
        if ($yayasan_id) {
            $schools = \App\Models\School::where('status', 'aktif')
                ->where('yayasan_id', $yayasan_id)
                ->get();
            $yayasan = \App\Models\Yayasan::find($yayasan_id);
        } else {
            $schools = \App\Models\School::where('status', 'aktif')->get();
            $yayasan = null;
        }

        // 3. Tentukan selected_school_id default:
        $selected_school_id = $request->query('school_id');
        $is_locked = false;

        // Jika user admin/staff internal sekolah
        if ($user && !$user->isSuperAdmin() && !$user->isWaliMurid() && $user->school_id) {
            $selected_school_id = $user->school_id;
            $is_locked = true;
        }

        // Jika belum ada school_id terpilih tapi ada sekolah, pilih sekolah pertama
        if (!$selected_school_id && $schools->count() > 0) {
            $selected_school_id = $schools->first()->id;
        }

        if (!$yayasan && $selected_school_id) {
            $sch = DB::table('schools')->where('id', $selected_school_id)->first();
            if ($sch && $sch->yayasan_id) {
                $yayasan = \App\Models\Yayasan::find($sch->yayasan_id);
            }
        }

        // PAKSA REDIRECT KE ENCRYPTED HASH TOKEN URL (Contoh: /y/eyJjIjoiWUFIMi.../form-pendaftaran?school_id=1#form)
        if ($yayasan && !$request->routeIs('yayasan.pendaftaran.form')) {
            return redirect()->to(route('yayasan.pendaftaran.form', array_merge(['kode_yayasan' => $yayasan->encrypted_token], $request->query())) . '#form');
        }

        // 4. Siapkan data kuota & tahun inden untuk SETIAP sekolah (mengikuti tahun_ajaran_aktif dari Super Admin/Pengaturan)
        $schools_data = [];

        foreach ($schools as $s) {
            // Tentukan tahun ajaran awal pendaftaran berdasarkan konfigurasi Super Admin / Sekolah:
            // 1. tahun_ajaran_aktif pada sekolah
            // 2. Jika kosong, pakai tahun_ajaran dari semester aktif
            // 3. Fallback: tahun kalender saat ini
            $taAktif = trim($s->tahun_ajaran_aktif ?? '');
            if (empty($taAktif)) {
                $semAktif = \App\Models\Semester::where('is_active', true)->first();
                $taAktif = $semAktif ? trim($semAktif->tahun_ajaran) : '';
            }
            if (empty($taAktif)) {
                $taAktif = date('Y') . '/' . (date('Y') + 1);
            }

            if (preg_match('/^(\d{4})/', $taAktif, $matches)) {
                $startYear = (int) $matches[1];
            } else {
                $startYear = (int) date('Y');
            }

            $max_years = (int) ($s->max_indent_years ?? 3);
            if ($max_years < 1) $max_years = 1;

            $list_tahun = [];
            $kuota_tahun = [];

            for ($i = 0; $i < $max_years; $i++) {
                $thn1 = $startYear + $i;
                $thn2 = $thn1 + 1;
                $tahunString = $thn1 . '/' . $thn2;
                $list_tahun[] = $tahunString;

                $terisi_tk_pagi = DB::table('siswas')->where('school_id', $s->id)->where('program_pilihan', 'LIKE', 'TK Pagi%'.$tahunString.'%')->where('status', '!=', 'lulus')->count();
                $terisi_tk_siang = DB::table('siswas')->where('school_id', $s->id)->where('program_pilihan', 'LIKE', 'TK Siang%'.$tahunString.'%')->where('status', '!=', 'lulus')->count();
                $terisi_paud = DB::table('siswas')->where('school_id', $s->id)->where('program_pilihan', 'LIKE', 'PAUD%'.$tahunString.'%')->where('status', '!=', 'lulus')->count();

                $kuota_tahun[$tahunString] = [
                    'tk_pagi' => max(0, ($s->kuota_tk_pagi ?? 30) - $terisi_tk_pagi),
                    'tk_siang' => max(0, ($s->kuota_tk_siang ?? 30) - $terisi_tk_siang),
                    'paud' => max(0, ($s->kuota_paud ?? 30) - $terisi_paud),
                ];
            }

            $schools_data[$s->id] = [
                'id' => $s->id,
                'nama_sekolah' => $s->nama_sekolah,
                'alamat' => $s->alamat,
                'tahun_ajaran_aktif' => $s->tahun_ajaran_aktif ?? $taAktif,
                'list_tahun' => $list_tahun,
                'kuota' => $kuota_tahun,
            ];
        }

        $currentSchool = $schools->where('id', $selected_school_id)->first() ?? $schools->first();

        return view('pendaftaran', compact(
            'schools',
            'schools_data',
            'currentSchool',
            'selected_school_id',
            'is_locked',
            'yayasan'
        ));
    }

    /**
     * API Realtime: Cek apakah email orang tua sudah terdaftar di sistem.
     */
    public function checkParentEmail(Request $request)
    {
        $email = trim($request->input('email', ''));
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return response()->json(['valid' => false, 'exists' => false]);
        }

        $user = User::where('email', $email)->first();
        if ($user) {
            $totalAnak = Siswa::where('email', $email)->count();
            return response()->json([
                'valid'      => true,
                'exists'     => true,
                'name'       => $user->name,
                'total_anak' => $totalAnak,
                'message'    => "Akun terdeteksi atas nama " . $user->name . ($totalAnak > 0 ? " (Sudah terdaftar {$totalAnak} anak sebelumnya)" : "") . ". Masukkan kata sandi akun Anda untuk konfirmasi penautan anak ini."
            ]);
        }

        return response()->json([
            'valid'   => true,
            'exists'  => false,
            'message' => "Email baru. Akun portal wali murid akan otomatis dibuatkan menggunakan email & kata sandi yang Anda tentukan di bawah."
        ]);
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
            'nik'              => 'required|string|unique:siswas,nik',
            'no_kk'            => 'required|string',
            'tempat_lahir'     => 'required|string|max:255',
            'tgl_lahir'        => 'required|date',
            'email'            => 'required|email',
            'lintang'          => 'nullable|string',
            'bujur'            => 'nullable|string',
            'foto_anak'        => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'foto_kk'          => 'nullable|mimes:jpeg,png,jpg,pdf|max:2048',
            'foto_akte'        => 'nullable|mimes:jpeg,png,jpg,pdf|max:2048',
            'bukti_pembayaran' => $isAdmin ? 'nullable|image|mimes:jpeg,png,jpg|max:2048' : 'required|image|mimes:jpeg,png,jpg|max:2048',
        ];

        // Jika pendaftar adalah publik (belum login), wajib menyertakan kata sandi akun
        if (!Auth::check()) {
            $rules['password'] = 'required|string|min:6';
        }

        $messages = [
            'school_id.required'        => 'Cabang sekolah wajib dipilih.',
            'program_pilihan.required'  => 'Program kelas wajib dipilih.',
            'tahun_inden.required'      => 'Tahun ajaran wajib dipilih.',
            'nama_lengkap.required'     => 'Nama lengkap anak wajib diisi.',
            'nik.required'              => 'NIK anak wajib diisi.',
            'nik.unique'                => 'NIK anak ini (' . $request->nik . ') sudah pernah terdaftar di sistem. Mohon periksa kembali NIK yang Anda masukkan.',
            'no_kk.required'            => 'Nomor Kartu Keluarga (KK) wajib diisi.',
            'tempat_lahir.required'     => 'Tempat lahir wajib diisi.',
            'tgl_lahir.required'        => 'Tanggal lahir wajib diisi.',
            'email.required'            => 'Email orang tua wajib diisi.',
            'email.email'               => 'Format email tidak valid.',
            'password.required'         => 'Kata sandi akun wajib diisi.',
            'password.min'              => 'Kata sandi minimal 6 karakter.',
            'bukti_pembayaran.required' => 'Bukti pembayaran pendaftaran wajib diunggah.',
            'foto_anak.image'           => 'File pas foto anak harus berupa gambar (JPG/PNG).',
        ];

        $request->validate($rules, $messages);

        // Validasi batasan usia anak berdasarkan Tahun Ajaran dan Program Kelas yang dipilih
        if ($request->filled('tahun_inden') && $request->filled('tgl_lahir')) {
            if (preg_match('/^(\d{4})/', $request->tahun_inden, $matches)) {
                $entryYear = (int) $matches[1];
                $birthDate = \Carbon\Carbon::parse($request->tgl_lahir);
                $birthYear = (int) $birthDate->format('Y');
                $cutoffDate = \Carbon\Carbon::create($entryYear, 7, 1);
                
                $diffYears = $birthDate->diffInYears($cutoffDate, false);
                $diffMonths = $birthDate->copy()->addYears(max(0, $diffYears))->diffInMonths($cutoffDate);

                $program = strtoupper($request->program_pilihan ?? '');
                $isPaud = str_contains($program, 'PAUD');
                $isTk = str_contains($program, 'TK');

                if ($isPaud) {
                    $minBirthYear = $entryYear - 4;
                    $maxBirthYear = $entryYear - 3;
                    if ($birthYear < $minBirthYear || $birthYear > $maxBirthYear) {
                        return back()->withInput()->withErrors([
                            'tgl_lahir' => "Untuk program PAUD pada Tahun Ajaran {$request->tahun_inden}, usia anak harus 3 – 4 tahun (kelahiran tahun {$minBirthYear} s/d {$maxBirthYear}). Tanggal lahir yang dimasukkan adalah " . $birthDate->format('d/m/Y') . " (usia {$diffYears} tahun {$diffMonths} bulan per Juli {$entryYear})."
                        ])->with('error', "Usia anak tidak memenuhi syarat untuk program PAUD (3 - 4 tahun) pada Tahun Ajaran {$request->tahun_inden}.");
                    }
                } elseif ($isTk) {
                    $minBirthYear = $entryYear - 7;
                    $maxBirthYear = $entryYear - 4;
                    if ($birthYear < $minBirthYear || $birthYear > $maxBirthYear) {
                        return back()->withInput()->withErrors([
                            'tgl_lahir' => "Untuk program TK pada Tahun Ajaran {$request->tahun_inden}, usia anak harus 4 – 6 tahun (kelahiran tahun {$minBirthYear} s/d {$maxBirthYear}). Tanggal lahir yang dimasukkan adalah " . $birthDate->format('d/m/Y') . " (usia {$diffYears} tahun {$diffMonths} bulan per Juli {$entryYear})."
                        ])->with('error', "Usia anak tidak memenuhi syarat untuk program TK (4 - 6 tahun) pada Tahun Ajaran {$request->tahun_inden}.");
                    }
                }
            }
        }

        $school = DB::table('schools')->where('id', $request->school_id)->first();
        $parentUser = null;

        // Logika Akun Wali Murid (Otomatis Buat Akun Baru atau Konfirmasi Akun Lama / Anak Ke-2)
        if (!Auth::check()) {
            $existingUser = User::where('email', $request->email)->first();

            if ($existingUser) {
                // Konfirmasi kata sandi akun yang sudah ada
                if (!Hash::check($request->password, $existingUser->password)) {
                    return back()->withInput()->with('error', 'Kata sandi untuk akun ' . $request->email . ' tidak cocok. Silakan masukkan kata sandi akun Anda yang benar untuk konfirmasi penautan anak.');
                }
                $parentUser = $existingUser;
            } else {
                // Buat akun wali murid baru secara otomatis
                $parentName = $request->nama_ibu ?: ($request->nama_ayah ?: 'Wali dari ' . $request->nama_lengkap);
                $parentUser = User::create([
                    'name'       => $parentName,
                    'email'      => $request->email,
                    'password'   => Hash::make($request->password),
                    'role'       => 'wali_murid',
                    'yayasan_id' => $school ? $school->yayasan_id : null,
                ]);
            }
        }

        $program_gabungan = $request->program_pilihan . ' (Tahun: ' . $request->tahun_inden . ')';

        $fotoPath = $request->hasFile('foto_anak') ? $request->file('foto_anak')->store('foto_siswa', 'public') : null;
        $buktiPath = $request->hasFile('bukti_pembayaran') ? $request->file('bukti_pembayaran')->store('bukti_pendaftaran', 'public') : null;

        if ($request->hasFile('foto_kk')) $request->file('foto_kk')->store('dokumen_siswa', 'public');
        if ($request->hasFile('foto_akte')) $request->file('foto_akte')->store('dokumen_siswa', 'public');

        $koordinat_gabungan = ($request->lintang && $request->bujur) ? ($request->lintang . ', ' . $request->bujur) : '-';

        $siswaId = DB::table('siswas')->insertGetId([
            'school_id'          => $request->school_id,
            'user_id'            => $parentUser ? $parentUser->id : (Auth::check() ? Auth::id() : null),
            'program_pilihan'    => $program_gabungan,
            'nama_lengkap'       => $request->nama_lengkap,
            'nik'                => $request->nik,
            'no_kk'              => $request->no_kk,
            'jenis_kelamin'      => $request->jenis_kelamin ?? 'Laki-laki',
            'tempat_lahir'       => $request->tempat_lahir,
            'tgl_lahir'          => $request->tgl_lahir,
            'agama'              => $request->agama ?? 'Islam',
            'anak_ke'            => $request->anak_ke ?? 1,
            'jml_saudara'        => $request->jml_saudara ?? 1,
            'alamat'             => $request->alamat,
            'rt'                 => $request->rt,
            'rw'                 => $request->rw,
            'dusun'              => $request->dusun,
            'kelurahan'          => $request->kelurahan,
            'kecamatan'          => $request->kecamatan,
            'kode_pos'           => $request->kode_pos,
            'jenis_tinggal'      => $request->jenis_tinggal,
            'alat_transportasi'  => $request->alat_transportasi,
            'jarak_rumah'        => $request->jarak_rumah,
            'hp'                 => $request->hp,
            'email'              => $request->email,
            'nama_ortu'          => $request->nama_ibu ?: ($request->nama_ayah ?: 'Wali Murid'),
            'nama_ayah'          => $request->nama_ayah,
            'nik_ayah'           => $request->nik_ayah,
            'tahun_lahir_ayah'   => $request->tahun_lahir_ayah,
            'pendidikan_ayah'    => $request->pendidikan_ayah,
            'pekerjaan_ayah'     => $request->pekerjaan_ayah,
            'penghasilan_ayah'   => $request->penghasilan_ayah,
            'nama_ibu'           => $request->nama_ibu,
            'nik_ibu'            => $request->nik_ibu,
            'tahun_lahir_ibu'    => $request->tahun_lahir_ibu,
            'pendidikan_ibu'     => $request->pendidikan_ibu,
            'pekerjaan_ibu'      => $request->pekerjaan_ibu,
            'penghasilan_ibu'    => $request->penghasilan_ibu,
            'tinggi_badan'       => $request->tinggi_badan,
            'berat_badan'        => $request->berat_badan,
            'lingkar_kepala'     => $request->lingkar_kepala,
            'koordinat_rumah'    => $koordinat_gabungan,
            'lintang'            => $request->lintang ?? '0',
            'bujur'              => $request->bujur ?? '0',
            'foto_anak'          => $fotoPath,
            'bukti_pembayaran'   => $buktiPath,
            'status'             => $isAdmin ? 'aktif' : 'menunggu',
            'created_at'         => now(),
            'updated_at'         => now(),
        ]);

        $school = DB::table('schools')->where('id', $request->school_id)->first();
        $yayasanObj = $school ? \App\Models\Yayasan::find($school->yayasan_id) : null;
        $loginUrl = $yayasanObj ? $yayasanObj->encrypted_login_url : route('login');

        return back()->with('success_pendaftaran', [
            'nama_siswa'   => $request->nama_lengkap,
            'nama_sekolah' => $school ? $school->nama_sekolah : 'Sekolah',
            'nama_yayasan' => $yayasanObj ? $yayasanObj->nama_yayasan : 'Yayasan Terdaftar',
            'email'        => $request->email,
            'login_url'    => $loginUrl,
        ]);
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