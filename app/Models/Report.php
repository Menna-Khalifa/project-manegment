<?php
// app/Models/Report.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_type',
        'report_date',
        'store_id',
        'store_name', // جديد
        'store_city', // جديد
        'project_amer_id',
        'checklist_items',
        'custom_fields',
        'units', // جديد
        'images',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'report_date' => 'date',
        'checklist_items' => 'array',
        'custom_fields' => 'array',
        'units' => 'array', // جديد
        'images' => 'array',
    ];

    // Relations
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function projectAmer()
    {
        return $this->belongsTo(ProjectAmer::class);
    }

    // Get checklist items based on report type
    public static function getChecklistItems($reportType)
    {
        $checklists = [
            'start_up_report' => [
                ['en' => 'Make sure circuit breaker matching with unit capacity', 'ar' => 'التأكد من أن القاطع يتناسب مع قدرة الوحدة.'],
                ['en' => 'Make sure of cable connections', 'ar' => 'التأكد من توصيل الكوابل.'],
                ['en' => 'Make sure thickness of cable matching with unit capcity', 'ar' => 'التأكد من أن مساحة مقطع الكابل يتناسب مع قدرة الوحدة.'],
                ['en' => 'Make sure for direction of rotation motors', 'ar' => 'التأكد من اتجاه دوران المحركات'],
                ['en' => 'Make sure that the fan of the motor rotates freely without abnormal sound', 'ar' => 'التأكد من أن مروحة الموتور تتحرك بحرية بدون أي صوت غير طبيعي.'],
                ['en' => 'Check the tension of the belts and check the fatigue', 'ar' => 'التأكد من شدة السيور و فحص التآكل بها.'],
                ['en' => 'Make sure that there is no leakage', 'ar' => 'تأكد من عدم وجود تسريب'],
                ['en' => 'Make sure that there is no water lackage of water also ensure the continuous flow of water and air through the unit.', 'ar' => 'التأكد من عدم وجود تسريب مياه والتأكد من متابعة سريان المياه و سحب الهواء من داخل الوافرتي.'],
                ['en' => 'Clean the unit from inside and outside, and check the ventilation of the motor', 'ar' => 'نظف الوحدة من الخارج و الداخل و فحص تهوية الموتور والتأكد من أن مسارات الهواء خالية من الأتربة'],
                ['en' => 'To check air filters in terms of the clean level and the general condition', 'ar' => 'التأكد من سلامة الفلاتر من حيث النظافة و الحالة العامة'],
                ['en' => 'Make sure vibration damper installation', 'ar' => 'التأكد من تركيب ماص الإهتزازات'],
                ['en' => 'Examine drain pan and piping, ensure that they are free of obstructions, clogging,craks and erosion.', 'ar' => 'التأكد من أن الصيغ و الأنابيب لا تحتوي على أي أتربة أو تسرب.'],

            ],
            'work_completed' => [
                ['en' => 'Site preparation completed', 'ar' => 'اكتمال تجهيز الموقع'],
                ['en' => 'Equipment installation completed', 'ar' => 'اكتمال تركيب المعدات'],
                ['en' => 'Electrical connections verified', 'ar' => 'التحقق من التوصيلات الكهربائية'],
                ['en' => 'Testing and commissioning done', 'ar' => 'تم الإختبار والتشغيل'],
                ['en' => 'Documentation completed', 'ar' => 'اكتمال التوثيق'],
                ['en' => 'Client training provided', 'ar' => 'تم تدريب العميل'],
                ['en' => 'Final inspection passed', 'ar' => 'اجتياز الفحص النهائي'],
                ['en' => 'Safety protocols followed', 'ar' => 'تم اتباع بروتوكولات السلامة'],
            ],
            'sites_refer_report' => [
                ['en' => 'Site visit conducted', 'ar' => 'تم إجراء زيارة الموقع'],
                ['en' => 'Site conditions assessed', 'ar' => 'تم تقييم ظروف الموقع'],
                ['en' => 'Measurements taken', 'ar' => 'تم أخذ القياسات'],
                ['en' => 'Photos documented', 'ar' => 'تم توثيق الصور'],
                ['en' => 'Client requirements noted', 'ar' => 'تم تسجيل متطلبات العميل'],
                ['en' => 'Recommendations provided', 'ar' => 'تم تقديم التوصيات'],
                ['en' => 'Budget estimation prepared', 'ar' => 'تم إعداد تقدير الميزانية'],
            ],
        ];

        return $checklists[$reportType] ?? [];
    }

    // Get custom fields based on report type
    public static function getCustomFields($reportType)
    {
        $customFields = [
            'start_up_report' => [
                ['name' => 'condenser', 'label' => 'Condenser fan motor (A)', 'label_ar' => 'قدرة المروحة المحرك (أمبير)', 'type' => 'number'],
                ['name' => 'evaporator_current', 'label' => 'Evaporator fan motor (A)', 'label_ar' => 'المبخر مروحة المحرك (أمبير)', 'type' => 'number'],
                ['name' => 'volteg', 'label' => 'Volteg at site (V)', 'label_ar' => 'الجهد الكهربائي (فولت)', 'type' => 'number'],
            ],
            // في method getCustomFields
            'work_completed' => [
                ['name' => 'crane_amount', 'label' => 'Crane Amount', 'label_ar' => 'Crane Amount', 'type' => 'number'],
                ['name' => 'capper_pipe_amount', 'label' => 'Capper Pipe Amount', 'label_ar' => 'Capper Pipe Amount', 'type' => 'number'],
                ['name' => 'power_cable_amount', 'label' => 'Power Cable Amount', 'label_ar' => 'Power Cable Amount', 'type' => 'number'],
            ],
            'sites_refer_report' => [
                ['name' => 'site_area', 'label' => 'Site Area (m²)', 'label_ar' => 'مساحة الموقع (متر مربع)', 'type' => 'number'],
                ['name' => 'building_floors', 'label' => 'Number of Floors', 'label_ar' => 'عدد الطوابق', 'type' => 'number'],
                ['name' => 'estimated_cost', 'label' => 'Estimated Cost (SAR)', 'label_ar' => 'التكلفة المقدرة (ريال)', 'type' => 'number'],
                ['name' => 'recommended_solution', 'label' => 'Recommended Solution', 'label_ar' => 'الحل المقترح', 'type' => 'textarea'],
            ],
        ];

        return $customFields[$reportType] ?? [];
    }

    public function getReportTypeName()
    {
        $types = [
            'start_up_report' => 'Packaged Unit Start Up Report',
            'work_completed' => 'Work Completed Report',
            'sites_refer_report' => 'Sites Refer Report',
        ];

        return $types[$this->report_type] ?? $this->report_type;
    }

    // Delete images when report is deleted
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($report) {
            if ($report->images) {
                foreach ($report->images as $image) {
                    Storage::disk('public')->delete($image);
                }
            }
        });
    }
}
