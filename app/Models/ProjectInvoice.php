<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'invoice_number',
        'amount',
        'payment_file',
        'status',
        'notes',
        'approved_at',
        'approved_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    /**
     * العلاقة مع المشروع
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * العلاقة مع المستخدم الذي وافق على الفاتورة
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * حساب إجمالي المدفوعات المقبولة للمشروع
     */
    public static function getTotalApprovedPayments($projectId)
    {
        return self::where('project_id', $projectId)
            ->where('status', 'approved')
            ->sum('amount');
    }

    /**
     * حساب المبلغ المتبقي للمشروع
     */
    public static function getRemainingAmount($projectId)
    {
        $project = Project::find($projectId);
        if (!$project) return 0;

        $totalPaid = self::getTotalApprovedPayments($projectId);
        return $project->project_cost - $totalPaid;
    }

    /**
     * التحقق من اكتمال دفع المشروع
     */
    public static function isProjectFullyPaid($projectId)
    {
        return self::getRemainingAmount($projectId) <= 0;
    }
}
