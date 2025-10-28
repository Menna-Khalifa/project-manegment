<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'po_num',
        'name',
        'description',
        'start_date',
        'end_date',
        'type',
        'status',
        'project_cost',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'project_cost' => 'decimal:2',
    ];

    // Relationships
    public function projectItems()
    {
        return $this->hasMany(ProjectItems::class);
    }


    /**
     * حساب نسبة اكتمال المشروع من نسب ال items الخاصه بالمشروع
     */
    public function getCompletionPercentageAttribute()
    {
        $items = $this->projectItems;

        if ($items->count() === 0) {
            return 0; // لو مفيش Items
        }

        $totalPercentage = $items->sum(function ($item) {
            if ($item->qty > 0) {
                return ($item->executed_qty / $item->qty) * 100;
            }
            return 0;
        });

        return round($totalPercentage / $items->count(), 2); // متوسط النسبة
    }


    /**
     * العلاقة مع فواتير المشروع
     */
    public function invoices()
    {
        return $this->hasMany(ProjectInvoice::class);
    }

    /**
     * حساب إجمالي المدفوعات المقبولة
     */
    public function getTotalPaidAttribute()
    {
        return $this->invoices()->where('status', 'approved')->sum('amount');
    }

    /**
     * حساب إجمالي المدفوعات المعلقه
     */
    public function getTotalPaymentPendingAttribute()
    {
        return $this->invoices()->where('status', 'pending')->sum('amount');
    }

    /**
     * حساب المبلغ المتبقي
     */
    public function getRemainingAmountAttribute()
    {
        return $this->project_cost - $this->total_paid;
    }

    /**
     * التحقق من اكتمال الدفع
     */
    public function getIsFullyPaidAttribute()
    {
        return $this->remaining_amount <= 0;
    }

    /**
     * نسبة الدفع المكتملة
     */
    public function getPaymentProgressAttribute()
    {
        if ($this->project_cost <= 0) return 0;
        return ($this->total_paid / $this->project_cost) * 100;
    }

    public function projectTeams()
    {
        return $this->hasMany(ProjectTeam::class);
    }



    public function projectEquipment()
    {
        return $this->hasMany(ProjectEquipment::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'project_teams');
    }

    public function equipment()
    {
        return $this->belongsToMany(Equipment::class, 'project_equipment')
            ->withPivot('qty', 'status')
            ->withTimestamps();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeDelivered($query)
    {
        return $query->where('status', 'delivered');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', 'like', '%' . $type . '%');
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('start_date', [$startDate, $endDate])
            ->whereBetween('end_date', [$startDate, $endDate]);
    }
}
