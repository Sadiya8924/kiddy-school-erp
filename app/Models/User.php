<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['school_id', 'kelas_id', 'name', 'email', 'password', 'role'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Definisi Role (Biar gampang dipanggil di Sidebar & Controller)
    public function isSuperAdmin(): bool { return $this->role === 'super_admin'; }
    public function isAdmin(): bool { return $this->role === 'admin'; }
    public function isGuru(): bool { return $this->role === 'guru'; }
    public function isKepsek(): bool { return $this->role === 'kepsek'; }
    public function isWaliMurid(): bool { return $this->role === 'wali_murid'; }

    // Relasi Database
    public function school() { return $this->belongsTo(School::class); }
    public function kelas() { return $this->belongsTo(Kelas::class, 'kelas_id'); } 
    public function siswas() { return $this->hasMany(Siswa::class); } 
}