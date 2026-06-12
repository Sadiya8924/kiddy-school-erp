<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Semester;
use App\Models\AnggotaKelas;
use Illuminate\Support\Facades\Auth;

class SiswaController extends Controller
{
    // 1. TAMPILKAN DAFTAR SISWA
    public function index(Request $request)
    {
        $user = Auth::user();
        $schoolId = $user->isSuperAdmin() ? session('active_school_id') : $user->school_id;
        
        $semesterAktif = Semester::where('is_active', true)->first();
        
        $query = Siswa::with('kelasAktif')->where('school_id', $schoolId);

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
            } elseif ($request->has('kelas_id') && $request->kelas_id != '') {
                $query->whereHas('kelas', function($q) use ($request, $semesterAktif) {
                    $q->where('anggota_kelas.kelas_id', $request->kelas_id);
                    if ($semesterAktif) $q->where('anggota_kelas.semester_id', $semesterAktif->id);
                })->where('status', 'aktif');
            } else {
                $query->where('status', 'aktif');
            }
        }
        
        $siswas = $query->get();
        $list_kelas = Kelas::where('school_id', $schoolId)->get();
        
        return view('data_siswa', compact('siswas', 'list_kelas', 'semesterAktif'));
    }

    // 2. TAMBAH SISWA BARU
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

    // 4. TAMPILKAN PROFIL / BIODATA DETAIL SISWA
    public function show($id)
    {
        $user = Auth::user();
        $schoolId = $user->isSuperAdmin() ? session('active_school_id') : $user->school_id;
        
        $siswa = Siswa::with('kelasAktif')->where('school_id', $schoolId)->findOrFail($id);
        return view('siswa_detail', compact('siswa'));
    }

    // FUNGSI UNTUK MENAMPILKAN FORM PENDAFTARAN (GET)
    public function createPendaftaran(Request $request)
    {
        $school_id = $request->query('school_id');
        if (!$school_id) {
            return redirect('/')->with('error', 'Silakan pilih sekolah terlebih dahulu.');
        }

        $school = \Illuminate\Support\Facades\DB::table('schools')->where('id', $school_id)->first();
        if (!$school) {
            return redirect('/')->with('error', 'Sekolah tidak ditemukan.');
        }

        return view('pendaftaran', compact('school'));
    }

    // FUNGSI UNTUK MENYIMPAN DATA PENDAFTARAN (POST)
    public function storePendaftaran(Request $request)
    {
        $request->validate([
            'school_id'    => 'required|exists:schools,id',
            'nama_lengkap' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tgl_lahir'    => 'required|date',
            'foto_anak'    => 'required|image|mimes:jpeg,png,jpg|max:2048', 
            'nik'          => 'required|string|max:20',
            'agama'        => 'required|string',
            'alamat'       => 'required|string',
            'rt'           => 'required|string|max:5',
            'rw'           => 'required|string|max:5',
            'dusun'        => 'required|string',
            'kelurahan'    => 'required|string',
            'kecamatan'    => 'required|string',
            'kode_pos'     => 'required|string|max:10',
            'nama_ayah'    => 'required|string',
            'nama_ibu'     => 'required|string',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto_anak')) {
            $fotoPath = $request->file('foto_anak')->store('foto_siswa', 'public');
        }

        \Illuminate\Support\Facades\DB::table('siswas')->insert([
            'school_id'       => $request->school_id,
            'nama_lengkap'    => $request->nama_lengkap,
            'tempat_lahir'    => $request->tempat_lahir,
            'tgl_lahir'       => $request->tgl_lahir,
            'jenis_kelamin'   => $request->jenis_kelamin,
            'nik'             => $request->nik,
            'agama'           => $request->agama,
            'anak_ke'         => $request->anak_ke,
            'jml_saudara'     => $request->jml_saudara,
            
            'alamat'          => $request->alamat,
            'rt'              => $request->rt,
            'rw'              => $request->rw,
            'dusun'           => $request->dusun,
            'kelurahan'       => $request->kelurahan,
            'kecamatan'       => $request->kecamatan,
            'kode_pos'        => $request->kode_pos,
            'koordinat_rumah' => $request->koordinat_rumah,

            'jenis_tinggal'     => $request->jenis_tinggal,
            'alat_transportasi' => $request->alat_transportasi,
            'telepon'           => $request->telepon,
            'hp'                => $request->hp,
            'email'             => $request->email,
            'jarak_rumah'       => $request->jarak_rumah,
            'tinggi_badan'      => $request->tinggi_badan,
            'berat_badan'       => $request->berat_badan,
            'lingkar_kepala'    => $request->lingkar_kepala,

            'nama_ayah'         => $request->nama_ayah,
            'nik_ayah'          => $request->nik_ayah,
            'tahun_lahir_ayah'  => $request->tahun_lahir_ayah,
            'pendidikan_ayah'   => $request->pendidikan_ayah,
            'pekerjaan_ayah'    => $request->pekerjaan_ayah,
            'penghasilan_ayah'  => $request->penghasilan_ayah,

            'nama_ibu'          => $request->nama_ibu,
            'nik_ibu'           => $request->nik_ibu,
            'tahun_lahir_ibu'   => $request->tahun_lahir_ibu,
            'pendidikan_ibu'    => $request->pendidikan_ibu,
            'pekerjaan_ibu'     => $request->pekerjaan_ibu,
            'penghasilan_ibu'   => $request->penghasilan_ibu,
            
            'nama_ortu'         => $request->nama_ayah, 
            'foto_anak'         => $fotoPath,
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        $user = \Illuminate\Support\Facades\Auth::user();
        if ($user instanceof \App\Models\User && $user->isAdmin()) {
            return redirect('/data-siswa')->with('success', 'Data siswa baru berhasil ditambahkan!');
        }
        return redirect('/')->with('success', 'Pendaftaran berhasil! Silakan tunggu informasi dari pihak sekolah.');
    }

    // IMPORT CSV DAPODIK
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

            $siswa = \App\Models\Siswa::where('school_id', $schoolId)
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
}