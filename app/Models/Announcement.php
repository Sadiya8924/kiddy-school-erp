<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    // 1. Mengizinkan kolom-kolom ini diisi data dari form
    protected $fillable = [
        'school_id', 
        'kelas_id', 
        'judul', 
        'isi', 
        'target', 
        'tanggal_kegiatan'
    ];
    // 2. Memberi tahu bahwa satu pengumuman ini milik satu sekolah
    public function school()
    {
        return $this->belongsTo(School::class);
    }
}