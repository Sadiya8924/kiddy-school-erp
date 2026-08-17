<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class SchoolScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        // Super admin, owner, dan wali murid tidak dibatasi ke satu school_id tertentu
        if ($user && !$user->isSuperAdmin() && !$user->isWaliMurid()) {
            if ($user->school_id) {
                $builder->where($model->getTable() . '.school_id', $user->school_id);
            }
        }
    }
}