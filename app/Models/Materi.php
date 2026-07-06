<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materi extends Model
{
    use HasFactory;

    protected $table = 'materis';
    
    protected $fillable = [
        'school_id', 'judul', 'deskripsi', 'mata_pelajaran', 'kelas',
        'file', 'created_by', 'status', 'approved_by', 'approved_at', 'rejection_note'
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new \App\Models\Scopes\SchoolScope);
    }

    public function school() {
        return $this->belongsTo(School::class);
    }

    public function creator() {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver() {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
