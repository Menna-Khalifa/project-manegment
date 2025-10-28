<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $typeOptions = ['government', 'commercial', 'residential'];

        $projects = [
            [
                'po_num' => 'PO-2025-001',
                'name' => 'مشروع تطوير النظام الإداري',
                'description' => 'تطوير نظام إداري متكامل لإدارة العمليات',
                'start_date' => '2025-01-15',
                'end_date' => '2025-06-15',
                'type' => $typeOptions[array_rand($typeOptions)],
                'status' => 'active',
                'project_cost' => '150000',
            ],
            [
                'po_num' => 'PO-2025-002',
                'name' => 'مشروع تطوير تطبيق المحمول',
                'description' => 'تطوير تطبيق محمول لإدارة الخدمات',
                'start_date' => '2025-02-01',
                'end_date' => '2025-08-01',
                'type' => $typeOptions[array_rand($typeOptions)],
                'status' => 'active',
                'project_cost' => '200000',
            ],
            [
                'po_num' => 'PO-2024-025',
                'name' => 'مشروع موقع التجارة الإلكترونية',
                'description' => 'تطوير منصة تجارة إلكترونية شاملة',
                'start_date' => '2024-10-01',
                'end_date' => '2024-12-31',
                'type' => $typeOptions[array_rand($typeOptions)],
                'status' => 'completed',
                'project_cost' => '120000',
            ],
            [
                'po_num' => 'PO-2025-003',
                'name' => 'مشروع نظام إدارة المخزون',
                'description' => 'تطوير نظام متطور لإدارة المخزون والمبيعات',
                'start_date' => '2025-03-01',
                'end_date' => '2025-09-01',
                'type' => $typeOptions[array_rand($typeOptions)],
                'status' => 'pending',
                'project_cost' => '180000',
            ],
            [
                'po_num' => 'PO-2025-004',
                'name' => 'مشروع منصة التعلم الإلكتروني',
                'description' => 'تطوير منصة تعليمية تفاعلية',
                'start_date' => '2025-01-20',
                'end_date' => '2025-07-20',
                'type' => $typeOptions[array_rand($typeOptions)],
                'status' => 'cancelled',
                'project_cost' => '160000',
            ],
        ];

        foreach ($projects as $project) {
            Project::create($project);
        }
    }
}
