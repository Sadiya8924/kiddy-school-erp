<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $fillable = ['name', 'route_name', 'icon', 'category', 'role_access'];

    public function schools()
    {
        return $this->belongsToMany(School::class, 'school_modules')
            ->withPivot('is_active_for_guru')
            ->withTimestamps();
    }
}
