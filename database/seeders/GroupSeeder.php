<?php

namespace Database\Seeders;

use App\Models\Group;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // تعطيل فحص foreign keys مؤقتاً
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // حذف البيانات الموجودة
        Group::truncate();

        // إعادة تفعيل فحص foreign keys
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // إنشاء مجموعات افتراضية
        $groups = [
            [
                'name' => 'المشرفين الرئيسيين',
                'description' => 'مجموعة المشرفين الرئيسيين للنظام',
            ],
            [
                'name' => 'مشرفي المحتوى',
                'description' => 'مجموعة مشرفي المحتوى والمنشورات',
            ],
            [
                'name' => 'مشرفي المستخدمين',
                'description' => 'مجموعة مشرفي إدارة المستخدمين',
            ],
            [
                'name' => 'مشرفي التقارير',
                'description' => 'مجموعة مشرفي التقارير والإحصائيات',
            ],
        ];

        foreach ($groups as $group) {
            Group::create($group);
        }
    }
}
