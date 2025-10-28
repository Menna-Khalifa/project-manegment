<?php

namespace Database\Seeders;

use App\Models\Section;
use App\Models\SectionItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SectionItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // حذف جميع البيانات من الجداول
        DB::table('section_items')->delete();

        // إنشاء مجموعات افتراضية
        $section_items = [
            [
                'name' => 'Supply and Install AC Unit',
                'description' => 'Supply and Install AC Unit',
                'section_id' => 1,
            ],
            [
                'name' => 'Supply Pumps',
                'description' => 'Supply Pumps',
                'section_id' => 1,
            ],
            [
                'name' => 'Supply Chillers 100 ton',
                'description' => 'Supply Chillers 100 ton',
                'section_id' => 1,
            ],
            [
                'name' => 'Foundation',
                'description' => 'Foundation',
                'section_id' => 2,
            ],
            [
                'name' => 'Painting',
                'description' => 'Painting',
                'section_id' => 2,
            ],
            [
                'name' => 'Plaster',
                'description' => 'Plaster',
                'section_id' => 2,
            ],
            [
                'name' => 'Cable',
                'description' => 'Cable',
                'section_id' => 3,
            ],
            [
                'name' => 'Supply MCC Panels',
                'description' => 'Supply MCC Panels',
                'section_id' => 3,
            ],
            [
                'name' => 'Supply DDC Panels',
                'description' => 'Supply DDC Panels',
                'section_id' => 3,
            ],
            [
                'name' => 'Supply Cable 50mm*4 core',
                'description' => 'Supply Cable 50mm*4 core',
                'section_id' => 3,
            ],
        ];

        foreach ($section_items as $section_item) {
            SectionItem::create($section_item);
        }
    }
}
