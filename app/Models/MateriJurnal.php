<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MateriJurnal extends Model
{
    use HasFactory;
    protected $fillable = ['school_id', 'user_id', 'kelompok', 'judul_materi', 'deskripsi', 'tujuan_pembelajaran', 'file_materi', 'tanggal_dibuat', 'tanggal_tayang', 'status', 'catatan_penolakan'];

    public function jurnals() {
        return $this->hasMany(JurnalHarian::class, 'materi_id');
    }

    public function guru() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function kelas_targets() {
        return $this->belongsToMany(Kelas::class, 'materi_kelas_targets', 'materi_id', 'kelas_id');
    }
}