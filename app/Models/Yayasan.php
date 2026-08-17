<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Yayasan extends Model
{
    use HasFactory;

    protected $table = 'yayasans';

    protected $fillable = [
        'nama_yayasan',
        'kode_yayasan',
        'penanggung_jawab',
        'telepon',
        'alamat',
        'logo_yayasan',
        'landing_content',
        'status',
    ];

    protected $casts = [
        'landing_content' => 'array',
    ];


    public function schools()
    {
        return $this->hasMany(School::class, 'yayasan_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'yayasan_id');
    }
}
