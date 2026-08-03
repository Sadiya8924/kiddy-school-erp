<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JurnalHarian extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'school_id', 'kelas_id', 'materi_id', 'siswa_id', 'user_id', 'tanggal', 
        'tema', 'kegiatan', 'catatan', 'foto', 'video'
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

    public function lmsMateri() {
        return $this->belongsTo(Materi::class, 'materi_id');
    }

    public function getFotoUrlAttribute() {
        if (!$this->foto) return null;
        $filename = basename($this->foto);
        if (str_starts_with($this->foto, 'jurnal_media/foto/')) {
            return route('jurnal.media', ['type' => 'foto', 'filename' => $filename]);
        }
        return asset('storage/' . $this->foto);
    }

    public function getVideoUrlAttribute() {
        if (!$this->video) return null;
        $filename = basename($this->video);
        if (str_starts_with($this->video, 'jurnal_media/video/')) {
            return route('jurnal.media', ['type' => 'video', 'filename' => $filename]);
        }
        return asset('storage/' . $this->video);
    }
}