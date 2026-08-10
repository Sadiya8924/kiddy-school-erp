<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Siswa;

class SiswaPolicy
{
    /**
     * Determine whether the user can view the student record.
     */
    public function view(User $user, Siswa $siswa): bool
    {
        // Superadmin & Owner can view all
        if ($user->isSuperAdmin() || $user->isOwner()) {
            return true;
        }

        // Wali murid can only view their linked children
        if ($user->isWaliMurid()) {
            return $user->email === $siswa->email || $siswa->user_id === $user->id;
        }

        // Staff (Admin, Guru, Kepsek) can only view students from their own school
        return $user->school_id === $siswa->school_id;
    }

    /**
     * Determine whether the user can update the student record.
     */
    public function update(User $user, Siswa $siswa): bool
    {
        if ($user->isSuperAdmin() || $user->isOwner()) {
            return true;
        }

        if ($user->isAdmin() || $user->isKepsek()) {
            return $user->school_id === $siswa->school_id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the student record.
     */
    public function delete(User $user, Siswa $siswa): bool
    {
        // Delete operations strictly restricted per TM-30
        return false;
    }
}
