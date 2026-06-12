<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\SchoolScope;

class PerkembanganFisik extends Model
{
    protected $fillable = [
        'school_id', 'siswa_id', 'tgl_periksa',
        'berat_badan', 'tinggi_badan', 'lingkar_kepala', 'catatan'
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new SchoolScope);
    }

    public function siswa() { return $this->belongsTo(Siswa::class); }
    public function school() { return $this->belongsTo(School::class); }
}