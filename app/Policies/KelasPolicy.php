<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Kelas;

class KelasPolicy
{
    public function view(User $user, Kelas $kelas): bool
    {
        if ($user->isSuperAdmin() || $user->isOwner()) {
            return true;
        }

        return $user->school_id === $kelas->school_id;
    }

    public function update(User $user, Kelas $kelas): bool
    {
        if ($user->isSuperAdmin() || $user->isOwner()) {
            return true;
        }

        return ($user->isAdmin() || $user->isKepsek()) && $user->school_id === $kelas->school_id;
    }
}
