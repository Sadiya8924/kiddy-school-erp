<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\SchoolScope;

class Siswa extends Model
{
    protected $table = 'siswas';

    protected $fillable = [
        'school_id', 'user_id', 'nama_lengkap', 'nik', 'nisn', 'no_induk',
        'nama_ortu', 'foto_anak', 'tempat_lahir', 'tgl_lahir', 'jenis_kelamin',
        'tinggi_badan', 'berat_badan', 'koordinat_rumah', 'status',
        'alamat', 'rt', 'rw', 'dusun', 'kelurahan', 'kecamatan', 'kode_pos',
        'agama', 'anak_ke', 'jml_saudara', 'jenis_tinggal', 'alat_transportasi', 'telepon', 'hp', 'email', 'jarak_rumah', 'lingkar_kepala',
        'nama_ayah', 'nik_ayah', 'tahun_lahir_ayah', 'pendidikan_ayah', 'pekerjaan_ayah', 'penghasilan_ayah',
        'nama_ibu', 'nik_ibu', 'tahun_lahir_ibu', 'pendidikan_ibu', 'pekerjaan_ibu', 'penghasilan_ibu'
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new SchoolScope);
    }

    public function school() { return $this->belongsTo(School::class); }
    public function user() { return $this->belongsTo(User::class); }
    
    // 1. Relasi Banyak-ke-Banyak: Mengambil SEMUA riwayat kelas (TK A, TK B, dst)
    public function kelas()
    {
        return $this->belongsToMany(Kelas::class, 'anggota_kelas', 'siswa_id', 'kelas_id')
                    ->withPivot('semester_id')
                    ->withTimestamps();
    }
    
    // 2. Relasi Khusus: Mengambil objek Kelas HANYA pada semester yang AKTIF
    public function kelasAktif()
    {
        return $this->belongsToMany(Kelas::class, 'anggota_kelas', 'siswa_id', 'kelas_id')
                    ->join('semesters', 'anggota_kelas.semester_id', '=', 'semesters.id')
                    ->where('semesters.is_active', true)
                    ->select('kelas.*');
    }

    // 3. JALAN PINTAS (ACCESSOR): Inilah sihirnya!
    // Nanti di file Blade (HTML), kamu cukup ketik: {{ $siswa->nama_kelas_aktif }}
    public function getNamaKelasAktifAttribute()
    {
        $kelas = $this->kelasAktif()->first();
        return $kelas ? $kelas->nama_kelas : 'Belum ada kelas';
    }
    
    public function rapors() { return $this->hasMany(Rapor::class, 'siswa_id'); }
    public function absensis() { return $this->hasMany(Absensi::class, 'siswa_id'); }
    public function perkembanganFisiks() { return $this->hasMany(PerkembanganFisik::class, 'siswa_id'); }

    // Relasi ke tabel arsip_rapors (Satu siswa bisa punya banyak arsip/semester)
    public function arsipRapors()
    {
        return $this->hasMany(ArsipRapor::class);
    }
}