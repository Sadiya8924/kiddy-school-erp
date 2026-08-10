<?php

namespace App\Policies;

use App\Models\Siswa;
use App\Models\User;

/**
 * TM-04: SiswaPolicy — Cegah IDOR (Insecure Direct Object Reference)
 *
 * Sebelumnya: siapapun yang login bisa akses /siswa/{id} tanpa pengecekan.
 * Sesudah: akses dibatasi berdasarkan peran & kepemilikan data.
 *
 * Cara kerja:
 * - wali_murid : hanya bisa lihat siswa yang emailnya sama dengan email akun wali (relasi by email)
 * - guru       : hanya bisa lihat siswa di sekolahnya sendiri
 * - admin/kepsek/super_admin : bisa lihat siswa di sekolah yang sedang di-scope
 * - owner      : akses penuh ke semua siswa
 */
class SiswaPolicy
{
    /**
     * Menentukan apakah user boleh melihat profil siswa.
     */
    public function view(User $user, Siswa $siswa): bool
    {
        // Owner & super admin bypass semua cek
        if (in_array($user->role, ['owner', 'super_admin'])) {
            return true;
        }

        // Wali murid: hanya bisa lihat anak miliknya (cocokkan by email atau student_guardian)
        if ($user->role === 'wali_murid') {
            // Cek relasi langsung via email (pendekatan saat ini)
            if ($siswa->email && $siswa->email === $user->email) {
                return true;
            }
            // Cek via pivot student_guardian (TM-28, bila sudah diimplementasi)
            if (\Illuminate\Support\Facades\Schema::hasTable('student_guardian')) {
                return \Illuminate\Support\Facades\DB::table('student_guardian')
                    ->where('siswa_id', $siswa->id)
                    ->where('user_id', $user->id)
                    ->exists();
            }
            return false;
        }

        // Guru: hanya siswa di sekolah guru tersebut
        if ($user->role === 'guru') {
            return (int) $user->school_id === (int) $siswa->school_id;
        }

        // Admin & Kepsek: sekolah sendiri atau sekolah yang sedang di-pantau (super admin)
        $activeSchoolId = session('active_school_id') ?? $user->school_id;
        return (int) $activeSchoolId === (int) $siswa->school_id;
    }

    /**
     * Menentukan apakah user boleh meng-update data siswa.
     */
    public function update(User $user, Siswa $siswa): bool
    {
        // Hanya admin, kepsek, super_admin, owner yang boleh edit
        if (in_array($user->role, ['owner', 'super_admin'])) {
            return true;
        }

        if (in_array($user->role, ['admin', 'kepsek'])) {
            $activeSchoolId = session('active_school_id') ?? $user->school_id;
            return (int) $activeSchoolId === (int) $siswa->school_id;
        }

        return false;
    }
}
