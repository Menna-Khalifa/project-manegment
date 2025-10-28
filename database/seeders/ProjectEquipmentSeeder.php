<?php

namespace Database\Seeders;

use App\Models\ProjectEquipment;
use Illuminate\Database\Seeder;

class ProjectEquipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projectEquipment = [
            // Project 1 Equipment
            [
                'project_id' => 1,
                'equipment_id' => 1,
                'qty' => 5,
                'status' => 'available',
            ],
            [
                'project_id' => 1,
                'equipment_id' => 2,
                'qty' => 3,
                'status' => 'delivered',
            ],
            [
                'project_id' => 1,
                'equipment_id' => 3,
                'qty' => 2,
                'status' => 'unavailable',
            ],

            // Project 2 Equipment
            [
                'project_id' => 2,
                'equipment_id' => 2,
                'qty' => 4,
                'status' => 'available',
            ],
            [
                'project_id' => 2,
                'equipment_id' => 2,
                'qty' => 6,
                'status' => 'delivered',
            ],
            [
                'project_id' => 2,
                'equipment_id' => 1,
                'qty' => 2,
                'status' => 'available',
            ],

            // Project 3 Equipment
            [
                'project_id' => 3,
                'equipment_id' => 1,
                'qty' => 8,
                'status' => 'delivered',
            ],
            [
                'project_id' => 3,
                'equipment_id' => 3,
                'qty' => 4,
                'status' => 'delivered',
            ],

            // Project 4 Equipment
            [
                'project_id' => 4,
                'equipment_id' => 2,
                'qty' => 3,
                'status' => 'available',
            ],
            [
                'project_id' => 4,
                'equipment_id' => 3,
                'qty' => 7,
                'status' => 'unavailable',
            ],
            [
                'project_id' => 4,
                'equipment_id' => 2,
                'qty' => 5,
                'status' => 'available',
            ],

            // Project 5 Equipment
            [
                'project_id' => 5,
                'equipment_id' => 1,
                'qty' => 6,
                'status' => 'delivered',
            ],
            [
                'project_id' => 5,
                'equipment_id' => 3,
                'qty' => 4,
                'status' => 'available',
            ],
        ];

        foreach ($projectEquipment as $equipment) {
            ProjectEquipment::create($equipment);
        }
    }
}
