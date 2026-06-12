<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArsipRapor extends Model
{
    protected $fillable = ['siswa_id', 'semester_id', 'file_path', 'nama_file'];

    public function siswa() {
        return $this->belongsTo(Siswa::class);
    }

    public function semester() {
        return $this->belongsTo(Semester::class);
    }
}