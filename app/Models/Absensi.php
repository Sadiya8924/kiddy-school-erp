<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\SchoolScope;

class Absensi extends Model
{
    protected $table = 'absensis';

    protected $fillable = ['school_id', 'siswa_id', 'tanggal', 'status', 'keterangan'];

    protected static function booted(): void
    {
        static::addGlobalScope(new SchoolScope);
    }

    public function siswa() { return $this->belongsTo(Siswa::class); }
    public function school() { return $this->belongsTo(School::class); }
}