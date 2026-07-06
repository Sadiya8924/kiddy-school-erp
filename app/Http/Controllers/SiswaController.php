<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Semester;
use App\Models\AnggotaKelas;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SiswaController extends Controller
{
    // 1. TAMPILKAN DAFTAR SISWA
    public function index(Request $request)
    {
        $user = Auth::user();
        $schoolId = $user->isSuperAdmin() ? session('active_school_id') : $user->school_id;
        
        $semesterAktif = Semester::where('is_active', true)->first();
        
        $query = Siswa::with('kelasAktif')->where('school_id', $schoolId);

        // LOGIKA FILTER KELAS / STATUS
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
            } elseif ($request->kelas_id) {
                $query->whereHas('kelas', function($q) use ($request, $semesterAktif) {
                    $q->where('anggota_kelas.kelas_id', $request->kelas_id);
                    if ($semesterAktif) $q->where('anggota_kelas.semester_id', $semesterAktif->id);
                })->where('status', 'aktif');
            } else {
                $query->where('status', 'aktif');
            }
        }

        // ---> LOGIKA FILTER TAHUN PENDAFTARAN DINAMIS <---
        if ($request->filled('tahun_inden')) {
            $query->where('program_pilihan', 'LIKE', '%(Tahun: ' . $request->tahun_inden . ')%');
        }
        
        $siswas = $query->get();
        $list_kelas = Kelas::where('school_id', $schoolId)->get();
        
        // CUMA nampilin list tahun pendaftaran yang bener-bener ada siswanya di database!
        $list_tahun_inden = Siswa::where('school_id', $schoolId)
            ->whereNotNull('program_pilihan')
            ->pluck('program_pilihan')
            ->map(function($prog) {
                // Ambil text di dalam (Tahun: XXXX/YYYY)
                preg_match('/\(Tahun: (.*?)\)/', $prog, $matches);
                return isset($matches[1]) ? $matches[1] : null;
            })
            ->filter()
            ->unique()
            ->sort()
            ->values();
        
        return view('data_siswa', compact('siswas', 'list_kelas', 'semesterAktif', 'list_tahun_inden'));
    }

    // 2. TAMBAH SISWA BARU (MANUAL ADMIN)
    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required',
            'nik' => 'required',
        ]);

        $schoolId = Auth::user()->isSuperAdmin() ? session('active_school_id') : Auth::user()->school_id;

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

    // 3. SET / PINDAH KELAS SISWA
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

    // FUNGSI UNTUK MENAUTKAN AKUN WALI MURID KE DATA SISWA
    public function tautkanOrtu(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:siswas,id',
            'email'    => 'required|email'
        ]);

        $siswa = Siswa::findOrFail($request->siswa_id);
        $siswa->update([
            'email' => $request->email
        ]);

        return back()->with('success', 'Akun orang tua berhasil ditautkan ke data ' . $siswa->nama_lengkap . '!');
    }

    // 4. TAMPILKAN PROFIL / BIODATA DETAIL SISWA
    public function show($id)
    {
        $user = Auth::user();
        $schoolId = $user->isSuperAdmin() ? session('active_school_id') : $user->school_id;
        
        $siswa = Siswa::with('kelasAktif')->where('school_id', $schoolId)->findOrFail($id);
        return view('siswa_detail', compact('siswa'));
    }

    // 5. FUNGSI UNTUK MENAMPILKAN FORM PENDAFTARAN WALI MURID (GET)
    public function createPendaftaran(Request $request)
    {
        $user = Auth::user();
        $schools = \App\Models\School::where('status', 'aktif')->get();
        
        $selected_school_id = $request->query('school_id');
        $is_locked = false;

        if ($user && !$user->isSuperAdmin()) {
            if ($user->school_id) {
                $selected_school_id = $user->school_id;
                $is_locked = true;
            }
        }

        $school = null;
        $list_tahun_inden = [];
        $kuota_per_tahun = []; // Array khusus untuk menyimpan kuota berdasarkan tahun

        if ($selected_school_id) {
            $school = DB::table('schools')->where('id', $selected_school_id)->first();
            if ($school) {
                // ---> Generate Pilihan Tahun dan Hitung Kuota Masing-Masing Tahun <---
                $tahunMulai = date('m') <= 6 ? date('Y') : date('Y') + 1;
                $max_years = $school->max_indent_years ?? 3; 
                
                for ($i = 0; $i < $max_years; $i++) {
                    $thn1 = $tahunMulai + $i;
                    $thn2 = $thn1 + 1;
                    $tahunString = $thn1 . '/' . $thn2;
                    $list_tahun_inden[] = $tahunString;

                    // Hitung jumlah yang sudah terdaftar KHUSUS di tahun tersebut (menggunakan LIKE agar anti-error spasi)
                    $terisi_tk_pagi = DB::table('siswas')->where('school_id', $school->id)->where('program_pilihan', 'LIKE', 'TK Pagi%'.$tahunString.'%')->where('status', '!=', 'lulus')->count();
                    $terisi_tk_siang = DB::table('siswas')->where('school_id', $school->id)->where('program_pilihan', 'LIKE', 'TK Siang%'.$tahunString.'%')->where('status', '!=', 'lulus')->count();
                    $terisi_paud = DB::table('siswas')->where('school_id', $school->id)->where('program_pilihan', 'LIKE', 'PAUD%'.$tahunString.'%')->where('status', '!=', 'lulus')->count();

                    // Simpan ke array dengan key tahun tersebut
                    $kuota_per_tahun[$tahunString] = [
                        'tk_pagi' => max(0, $school->kuota_tk_pagi - $terisi_tk_pagi),
                        'tk_siang' => max(0, $school->kuota_tk_siang - $terisi_tk_siang),
                        'paud' => max(0, $school->kuota_paud - $terisi_paud),
                    ];
                }
            }
        }

        return view('pendaftaran', compact('schools', 'school', 'selected_school_id', 'is_locked', 'list_tahun_inden', 'kuota_per_tahun'));
    }

    // 6. FUNGSI UNTUK MENYIMPAN DATA PENDAFTARAN WALI MURID (POST)
    public function storePendaftaran(Request $request)
    {
        $request->validate([
            'school_id'        => 'required|exists:schools,id',
            'program_pilihan'  => 'required|string',
            'tahun_inden'      => 'required|string',
            'nama_lengkap'     => 'required|string|max:255',
            'nik'              => 'required|string',
            'tempat_lahir'     => 'required|string|max:255',
            'tgl_lahir'        => 'required|date',
            'foto_anak'        => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $program_gabungan = $request->program_pilihan . ' (Tahun: ' . $request->tahun_inden . ')';

        $terisi = DB::table('siswas')->where('school_id', $request->school_id)->where('program_pilihan', $program_gabungan)->where('status', '!=', 'lulus')->count();
        $school = DB::table('schools')->where('id', $request->school_id)->first();
        
        $batas_kuota = 0;
        if ($request->program_pilihan == 'TK Pagi') $batas_kuota = $school->kuota_tk_pagi;
        if ($request->program_pilihan == 'TK Siang') $batas_kuota = $school->kuota_tk_siang;
        if ($request->program_pilihan == 'PAUD') $batas_kuota = $school->kuota_paud;

        if ($terisi >= $batas_kuota) {
            return back()->withInput()->with('error', 'Maaf, kuota untuk ' . $program_gabungan . ' sudah penuh!');
        }

        $fotoPath = null;
        if ($request->hasFile('foto_anak')) {
            $fotoPath = $request->file('foto_anak')->store('foto_siswa', 'public');
        }

        $buktiPath = null;
        if ($request->hasFile('bukti_pembayaran')) {
            $buktiPath = $request->file('bukti_pembayaran')->store('bukti_pendaftaran', 'public');
        }

        DB::table('siswas')->insert([
            'school_id'       => $request->school_id,
            'program_pilihan' => $program_gabungan,
            'nama_lengkap'    => $request->nama_lengkap,
            'nik'             => $request->nik,
            'jenis_kelamin'   => $request->jenis_kelamin,
            'tempat_lahir'    => $request->tempat_lahir,
            'tgl_lahir'       => $request->tgl_lahir,
            'agama'           => $request->agama,
            'anak_ke'         => $request->anak_ke,
            'jml_saudara'     => $request->jml_saudara,
            
            // Alamat & Kontak
            'alamat'          => $request->alamat,
            'rt'              => $request->rt,
            'rw'              => $request->rw,
            'dusun'           => $request->dusun,
            'kelurahan'       => $request->kelurahan,
            'kecamatan'       => $request->kecamatan,
            'kode_pos'        => $request->kode_pos,
            'jenis_tinggal'   => $request->jenis_tinggal,
            'alat_transportasi'=> $request->alat_transportasi,
            'jarak_rumah'     => $request->jarak_rumah,
            'hp'              => $request->hp,
            
            // Orang Tua
            'nama_ayah'       => $request->nama_ayah,
            'nik_ayah'        => $request->nik_ayah,
            'tahun_lahir_ayah'=> $request->tahun_lahir_ayah,
            'pendidikan_ayah' => $request->pendidikan_ayah,
            'pekerjaan_ayah'  => $request->pekerjaan_ayah,
            'penghasilan_ayah'=> $request->penghasilan_ayah,
            'nama_ibu'        => $request->nama_ibu,
            'nik_ibu'         => $request->nik_ibu,
            'tahun_lahir_ibu' => $request->tahun_lahir_ibu,
            'pendidikan_ibu'  => $request->pendidikan_ibu,
            'pekerjaan_ibu'   => $request->pekerjaan_ibu,
            'penghasilan_ibu' => $request->penghasilan_ibu,
            
            // Fisik & Berkas
            'tinggi_badan'    => $request->tinggi_badan,
            'berat_badan'     => $request->berat_badan,
            'lingkar_kepala'  => $request->lingkar_kepala,
            'foto_anak'       => $fotoPath,
            'bukti_pembayaran'=> $buktiPath,
            'email'           => $request->email,
            'status'          => 'menunggu',
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        return back()->with('success', 'Pendaftaran berhasil! Silakan tunggu konfirmasi dari pihak sekolah via WhatsApp atau Email.');
    }

    // 7. IMPORT CSV DAPODIK
    public function importNisn(Request $request)
    {
        $request->validate([
            'file_csv' => 'required|file|mimes:csv,txt|max:2048', 
        ], [
            'file_csv.mimes' => 'File harus berformat .csv! Silakan Save As / Download sebagai CSV dari Dapodik.',
        ]);

        $file = $request->file('file_csv');
        $fileContent = file_get_contents($file->path());
        $lines = explode("\n", $fileContent);
        
        $berhasil_update = 0;
        $di_skip = 0; 
        
        $user = Auth::user();
        $schoolId = $user->isSuperAdmin() ? session('active_school_id') : $user->school_id;

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

        return back()->with('success', "Import Dapodik Selesai! Berhasil Update: $berhasil_update siswa. (Data tidak ada di database / dilewati: $di_skip siswa).");
    }

    // FUNGSI UNTUK ADMIN MENERIMA PENDAFTARAN
    public function terimaPendaftaran($id)
    {
        DB::table('siswas')->where('id', $id)->update([
            'status' => 'aktif',
            'updated_at' => now()
        ]);
        
        return back()->with('success', 'Pendaftaran berhasil diverifikasi! Siswa sekarang berstatus Aktif.');
    }
}