<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['yayasan_id', 'school_id', 'kelas_id', 'name', 'email', 'password', 'role'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Definisi Role (Biar gampang dipanggil di Sidebar & Controller)
    public function isOwner(): bool { return $this->role === 'owner'; }
    public function isSuperAdmin(): bool { return in_array($this->role, ['super_admin', 'owner']); }
    public function isPureSuperAdmin(): bool { return $this->role === 'super_admin'; }
    public function isAdmin(): bool { return $this->role === 'admin'; }
    public function isGuru(): bool { return $this->role === 'guru'; }
    public function isKepsek(): bool { return $this->role === 'kepsek'; }
    public function isWaliMurid(): bool { return $this->role === 'wali_murid'; }

    // Relasi Database
    public function yayasan() { return $this->belongsTo(Yayasan::class, 'yayasan_id'); }
    public function school() { return $this->belongsTo(School::class); }
    public function kelas() { return $this->belongsTo(Kelas::class, 'kelas_id'); }
    public function siswas() { return $this->hasMany(Siswa::class); }

    /**
     * TM-28: Relasi many-to-many wali → siswa via pivot student_guardian.
     * children() = hanya anak aktif (status = aktif) — default untuk dashboard wali.
     * allChildren() = semua anak termasuk alumni — untuk riwayat.
     *
     * Sebelumnya: wali hanya bisa punya 1 anak via email match.
     * Sesudah: satu akun wali bisa punya banyak anak & satu anak bisa punya ayah+ibu.
     */
    public function children()
    {
        return $this->belongsToMany(Siswa::class, 'student_guardian', 'user_id', 'siswa_id')
                    ->withPivot('peran', 'is_wali_utama')
                    ->withTimestamps()
                    ->where('siswas.status', 'aktif');
    }

    public function allChildren()
    {
        return $this->belongsToMany(Siswa::class, 'student_guardian', 'user_id', 'siswa_id')
                    ->withPivot('peran', 'is_wali_utama')
                    ->withTimestamps();
    }

    /**
     * Dapatkan ID Yayasan pemilik pengguna ini.
     */
    public function getUserYayasanId(): ?int
    {
        if ($this->yayasan_id) {
            return (int) $this->yayasan_id;
        }

        if ($this->school_id && $this->school) {
            return (int) $this->school->yayasan_id;
        }

        if ($this->role === 'wali_murid') {
            $siswa = $this->children()->first() 
                ?? $this->allChildren()->first() 
                ?? Siswa::where('email', $this->email)->first() 
                ?? Siswa::where('user_id', $this->id)->first();

            if ($siswa) {
                if ($siswa->yayasan_id) return (int) $siswa->yayasan_id;
                if ($siswa->school && $siswa->school->yayasan_id) return (int) $siswa->school->yayasan_id;
            }
        }

        return null;
    }
}