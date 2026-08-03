<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\SchoolScope;

class Rapor extends Model
{
    protected $fillable = [
        'school_id', 'siswa_id', 'semester_id', 'status', 'template_id', 'nilai_json', 'foto_kegiatan',
        'sakit', 'izin', 'alfa',
        'tinggi_badan', 'berat_badan',
    ];

    // nilai_json = array (bukan text biasa)
    protected $casts = [
        'mulok' => 'array', 
        'nilai_json' => 'array',
        'foto_kegiatan' => 'array',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new SchoolScope);
    }

    public function siswa() { return $this->belongsTo(Siswa::class); }
    public function school() { return $this->belongsTo(School::class); }
}