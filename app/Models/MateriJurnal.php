<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MateriJurnal extends Model
{
    use HasFactory;
    protected $fillable = ['school_id', 'kelompok', 'judul_materi', 'deskripsi', 'tanggal_dibuat'];

    public function jurnals() {
        return $this->hasMany(JurnalHarian::class, 'materi_id');
    }
}