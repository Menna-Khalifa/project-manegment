<?php

namespace Database\Seeders;

use App\Models\Section;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // حذف جميع البيانات من الجداول
        DB::table('sections')->delete();

        // إنشاء مجموعات افتراضية
        $sections = [
            [
                'name' => 'Mechanical',
                'description' => 'Mechanical',
            ],
            [
                'name' => 'Civil',
                'description' => 'Civil',
            ],
            [
                'name' => 'Electrical',
                'description' => 'Electrical',
            ],
        ];

        foreach ($sections as $section) {
            Section::create($section);
        }
    }
}
