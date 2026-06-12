<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{

    // Tambahkan ini di dalam class Kelas di app/Models/Kelas.php
    protected static function booted(): void
    {
        static::addGlobalScope(new \App\Models\Scopes\SchoolScope);
    }

    protected $table = 'kelas';
    protected $fillable = ['school_id', 'nama_kelas', 'tingkat', 'kelompok'];

    public function school() { return $this->belongsTo(School::class); }
    
    public function siswas()
    {
        return $this->belongsToMany(Siswa::class, 'anggota_kelas', 'kelas_id', 'siswa_id')
                    ->withTimestamps();
    }
    
    public function waliKelas() { return $this->hasMany(User::class, 'kelas_id'); }
}