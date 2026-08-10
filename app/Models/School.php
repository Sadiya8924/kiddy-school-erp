<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    protected $fillable = [
        'yayasan_id',
        'nama_sekolah', 'npsn', 'alamat', 'logo_sekolah',
        'kepala_sekolah', 'kepala_sekolah_user_id', 'telepon',
        'tahun_ajaran_aktif', 'semester_aktif', 'status',
        'is_rapor_open',
        'kuota_tk_pagi', 'kuota_tk_siang', 'kuota_paud', 'max_indent_years'
    ];

    public function yayasan() { return $this->belongsTo(Yayasan::class, 'yayasan_id'); }
    // TM-23: Kepala Sekolah via FK (bukan teks bebas) — untuk rapor & approver
    public function kepalaSekolahUser() { return $this->belongsTo(User::class, 'kepala_sekolah_user_id'); }
    public function kepalaSekolah() { return $this->kepalaSekolahUser(); } // alias singkat
    public function users() { return $this->hasMany(User::class); }
    public function siswas() { return $this->hasMany(Siswa::class); }
    public function rapors() { return $this->hasMany(Rapor::class); }
    public function absensis() { return $this->hasMany(Absensi::class); }
    public function perkembanganFisiks() { return $this->hasMany(PerkembanganFisik::class); }
    public function modules()
    {
        return $this->belongsToMany(Module::class, 'school_modules')
                    ->withPivot('is_active_for_guru', 'is_active_for_admin', 'is_active_for_kepsek', 'is_active_for_wali_murid');
    }
}