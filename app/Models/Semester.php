<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Semester extends Model {
    protected $table = 'semesters';
    protected $fillable = ['tahun_ajaran', 'jenis', 'is_active'];

    // Relasi: Satu semester memiliki banyak data rapor
    public function rapors()
    {
        return $this->hasMany(Rapor::class, 'semester_id');
    }
}