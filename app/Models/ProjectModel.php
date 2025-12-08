<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectModel extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function projectType()
    {
        return $this->belongsTo(ProjectType::class);
    }

       public function projectAmerItems()
    {
        return $this->hasMany(ProjectAmerItem::class);
    }
}
