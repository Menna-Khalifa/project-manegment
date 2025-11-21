<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceAmer extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_amer_id',
        'invoice_number',
        'amount',
        'payment_file',
        'status',
        'crane',
        'capper_pipe',
        'power_cable',
        'amount_crane',
        'amount_capper_pipe',
        'amount_power_cable',
        'notes',
        'created_by',
        'approved_at',
        'approved_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'approved_at' => 'datetime',
        'crane' => 'boolean',
        'capper_pipe' => 'boolean',
        'power_cable' => 'boolean',
        'amount_crane' => 'integer',
        'amount_capper_pipe' => 'integer',
        'amount_power_cable' => 'integer',
    ];

    public function projectAmer(): BelongsTo
    {
        return $this->belongsTo(ProjectAmer::class, 'project_amer_id');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
