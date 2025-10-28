<?php

namespace Database\Seeders;

use App\Models\Section;
use App\Models\Equipment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EquipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // حذف جميع البيانات من الجداول
        DB::table('equipments')->delete();

        // إنشاء مجموعات افتراضية
        $equipments = [
            [
                'name' => 'Scaffolding',
                'description' => 'Scaffolding',
                'count' => 10,
            ],
            [
                'name' => 'Electric Generator',
                'description' => 'Electric Generator',
                'count' => 10,
            ],
            [
                'name' => 'Exhaust Fans',
                'description' => 'Exhaust Fans',
                'count' => 10,
            ],
        ];

        foreach ($equipments as $equipment) {
            Equipment::create($equipment);
        }
    }
}
