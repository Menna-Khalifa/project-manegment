<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
    
    // إضافة علاقة المشاريع
    public function projectAmers()
    {
        return $this->hasMany(ProjectAmer::class);
    }

    // إضافة علاقة التقارير
    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}
