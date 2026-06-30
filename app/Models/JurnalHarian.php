<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JurnalHarian extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'school_id', 'kelas_id', 'materi_id', 'siswa_id', 'user_id', 'tanggal', 'presensi', 'mood',
        'tema', 'kegiatan', 'catatan', 'catatan_khusus', 'catatan_kepsek', 'foto', 'video'
    ];

    public function kelas() {
        return $this->belongsTo(Kelas::class);
    }
    public function guru() {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function siswa() {
        return $this->belongsTo(Siswa::class);
    }
    
    public function materi() {
        return $this->belongsTo(MateriJurnal::class, 'materi_id');
    }
}