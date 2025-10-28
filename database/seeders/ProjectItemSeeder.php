<?php

namespace Database\Seeders;

use App\Models\ProjectItems;
use Illuminate\Database\Seeder;

class ProjectItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projectItems = [
            [
                'project_id' => 1,
                'section_id' => 1,
                'section_item_id' => 1,
                'qty' => 10,
                'received_qty' => 8,
                'executed_qty' => 5,
                'expected_arrival' => '2025-02-15',
            ],
            [
                'project_id' => 1,
                'section_id' => 1,
                'section_item_id' => 2,
                'qty' => 15,
                'received_qty' => 12,
                'executed_qty' => 10,
                'expected_arrival' => '2025-02-20',
            ],
            [
                'project_id' => 1,
                'section_id' => 2,
                'section_item_id' => 3,
                'qty' => 20,
                'received_qty' => 15,
                'executed_qty' => 12,
                'expected_arrival' => '2025-03-01',
            ],
            [
                'project_id' => 2,
                'section_id' => 1,
                'section_item_id' => 1,
                'qty' => 25,
                'received_qty' => 20,
                'executed_qty' => 15,
                'expected_arrival' => '2025-02-25',
            ],
            [
                'project_id' => 2,
                'section_id' => 2,
                'section_item_id' => 4,
                'qty' => 12,
                'received_qty' => 10,
                'executed_qty' => 8,
                'expected_arrival' => '2025-03-10',
            ],
            [
                'project_id' => 3,
                'section_id' => 3,
                'section_item_id' => 5,
                'qty' => 30,
                'received_qty' => 30,
                'executed_qty' => 30,
                'expected_arrival' => '2024-11-15',
            ],
            [
                'project_id' => 4,
                'section_id' => 2,
                'section_item_id' => 2,
                'qty' => 18,
                'received_qty' => 5,
                'executed_qty' => 0,
                'expected_arrival' => '2025-03-20',
            ],
            [
                'project_id' => 5,
                'section_id' => 1,
                'section_item_id' => 3,
                'qty' => 22,
                'received_qty' => 12,
                'executed_qty' => 8,
                'expected_arrival' => '2025-02-28',
            ],
        ];

        foreach ($projectItems as $item) {
            ProjectItems::create($item);
        }
    }
}
