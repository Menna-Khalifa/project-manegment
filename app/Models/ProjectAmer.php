<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectAmer extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function scopeDept($query, $dept)
    {
        return $query->where('dept', $dept);
    }

    public function scopeRegion($query, $region)
    {
        return $query->where('region', $region);
    }

    public function scopeStatus($query, $status)
    {
        return $query->where('request_status', $status);
    }

    public function scopePriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeByDept($query, $dept)
    {
        return $query->where('dept', $dept);
    }

    public function scopeByRegion($query, $region)
    {
        return $query->where('region', $region);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('request_status', $status);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function items()
    {
        return $this->hasMany(ProjectAmerItem::class);
    }
    
    public function invoice()
    {
        return $this->HasOne(InvoiceAmer::class);
    }
}
