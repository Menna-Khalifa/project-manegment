<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectItems extends Model
{
    use HasFactory;

      protected $fillable = [
        'project_id',
        'section_id',
        'section_item_id',
        'qty',
        'received_qty',
        'executed_qty',
        'expected_arrival',
    ];

    protected $casts = [
        'expected_arrival' => 'date',
    ];

    // Relationships
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function sectionItem()
    {
        return $this->belongsTo(SectionItem::class);
    }

    // Scopes
    public function scopeByProject($query, $projectId)
    {
        return $query->where('project_id', $projectId);
    }

    public function scopeBySection($query, $sectionId)
    {
        return $query->where('section_id', $sectionId);
    }

    public function scopePendingDelivery($query)
    {
        return $query->whereColumn('received_qty', '<', 'qty');
    }

    public function scopePendingExecution($query)
    {
        return $query->whereColumn('executed_qty', '<', 'received_qty');
    }

    // Accessors
    public function getRemainingQtyAttribute()
    {
        return $this->qty - $this->received_qty;
    }

    public function getPendingExecutionQtyAttribute()
    {
        return $this->received_qty - $this->executed_qty;
    }
}
