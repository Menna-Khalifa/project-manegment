<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'type'];

    public function stores()
    {
        return $this->hasMany(Store::class);
    }

    public function projectAmerItems()
    {
        return $this->hasMany(ProjectAmerItem::class);
    }

    public function scopeTypeUnit($query)
    {
        return $query->where('type', 'unit');
    }

    public function scopeTypeStore($query)
    {
        return $query->where('type', 'store');
    }

}
