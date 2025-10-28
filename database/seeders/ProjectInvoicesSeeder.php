<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\ProjectInvoice;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectInvoicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // التأكد من وجود مشاريع ومستخدمين
        if (Project::count() == 0 || User::count() == 0) {
            $this->command->info('يجب إنشاء مشاريع ومستخدمين أولاً');
            return;
        }

        $projects = Project::all();
        $admin = User::first(); // أول مستخدم في النظام

        foreach ($projects->take(3) as $project) {
            // إنشاء فاتورة معتمدة
            ProjectInvoice::create([
                'project_id' => $project->id,
                'invoice_number' => 'INV-' . $project->po_num . '-001',
                'amount' => $project->project_cost * 0.3, // 30% من سعر المشروع
                'payment_file' => 'invoices/sample-payment-1.pdf',
                'status' => 'approved',
                'notes' => 'الدفعة الأولى - معتمدة',
                'approved_at' => now(),
                'approved_by' => $admin->id,
            ]);

            // إنشاء فاتورة قيد المراجعة
            ProjectInvoice::create([
                'project_id' => $project->id,
                'invoice_number' => 'INV-' . $project->po_num . '-002',
                'amount' => $project->project_cost * 0.25, // 25% من سعر المشروع
                'payment_file' => 'invoices/sample-payment-2.pdf',
                'status' => 'pending',
                'notes' => 'الدفعة الثانية - قيد المراجعة',
            ]);

            // إنشاء فاتورة مرفوضة للمشروع الأول فقط
            if ($project->id === $projects->first()->id) {
                ProjectInvoice::create([
                    'project_id' => $project->id,
                    'invoice_number' => 'INV-' . $project->po_num . '-003',
                    'amount' => $project->project_cost * 0.15,
                    'payment_file' => 'invoices/sample-payment-3.pdf',
                    'status' => 'rejected',
                    'notes' => 'فاتورة غير صحيحة - مرفوضة',
                    'approved_at' => now(),
                    'approved_by' => $admin->id,
                ]);
            }
        }

        $this->command->info('تم إنشاء فواتير المشاريع بنجاح');
    }
}
