<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    // علاقة مع الفريق
    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }
}