<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectType extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function projectModels()
    {
        return $this->hasMany(ProjectModel::class);
    }
}
