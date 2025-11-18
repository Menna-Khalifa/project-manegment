<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectAmerItem extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function projectAmer()
    {
        return $this->belongsTo(ProjectAmer::class);
    }

    public function projectType()
    {
        return $this->belongsTo(ProjectType::class);
    }

    public function projectModel()
    {
        return $this->belongsTo(ProjectModel::class);
    }

    public function projectCapacity()
    {
        return $this->belongsTo(ProjectCapacity::class);
    }

    public function projectVolt()
    {
        return $this->belongsTo(ProjectVolt::class);
    }

    public function brand()
    {
        return $this->belongsTo(\App\Models\Brand::class);
    }
}
