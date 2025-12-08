<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectCapacity extends Model
{
    use HasFactory;

    protected $guarded = [];

     public function projectAmerItems()
    {
        return $this->hasMany(ProjectAmerItem::class);
    }
}
