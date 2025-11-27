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

    // create uuid stores 
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($store) {
            // الحصول على أكبر رقم UUID موجود حالياً
            $maxUuid = static::selectRaw('MAX(CAST(SUBSTRING(uuid, 3) AS UNSIGNED)) as max_number')
                ->value('max_number');

            // الرقم الجديد = أكبر رقم + 1
            $newNumber = ($maxUuid ?? 0) + 1;

            // إنشاء UUID الجديد
            $store->uuid = 'ST' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
        });
    }
}
