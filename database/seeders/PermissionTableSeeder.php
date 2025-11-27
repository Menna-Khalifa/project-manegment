<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Team; // استبدل بالمسار الصحيح لموديل الفريق الخاص بك
use App\Models\User; // استبدل بالمسار الصحيح لموديل المستخدم الخاص بك

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // تعطيل قيود المفاتيح الخارجية
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // حذف جميع البيانات من الجداول
        DB::table('permissions')->delete();
        DB::table('role_has_permissions')->delete();
        DB::table('model_has_permissions')->delete();
        DB::table('roles')->delete();
        DB::table(table: 'model_has_roles')->delete();
        DB::table('teams')->delete(); // حذف الفرق إذا كانت موجودة

        // إعادة تفعيل قيود المفاتيح الخارجية
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // قائمة بالفرق والأذونات التابعة لها
        $teamsAndPermissions = [
            'roles_and_permissions' => [
                'roles_list',
                'add_role',
                'show_role',
                'edit_role',
                'delete_role',
            ],
            'groups' => [
                'groups_list',
                'add_group',
                'edit_group',
                'delete_group',
            ],
            'admins' => [
                'admins_list',
                'add_admin',
                'show_admin',
                'edit_admin',
                'edit_status_admin',
                'assign_role_admin',
                'delete_admin',
            ],
            'users' => [
                'users_list',
                'add_user',
                'show_user',
                'edit_user',
                'edit_status_user',
                'assign_role_user',
                'delete_user',
            ],
            'sections' => [
                'sections_list',
                'add_section',
                'edit_section',
                'delete_section',
            ],
            'section_items' => [
                'section_items_list',
                'add_section_item',
                'edit_section_item',
                'delete_section_item',
            ],
            'equipments' => [
                'equipments_list',
                'add_equipment',
                'edit_equipment',
                'delete_equipment',
            ],
            'projects' => [
                'projects_list',
                'add_project',
                'show_project',
                'edit_project',
                'delete_project',
            ],
            'project_items' => [
                'project_items_list',
                'add_project_item',
                'show_project_item',
                'edit_project_item',
                'edit_received_project_item',
                'edit_executed_project_item',
                'delete_project_item',
            ],
            'project_teams' => [
                'project_teams_list',
                'add_project_team',
                'edit_project_team',
                'show_project_team',
                'delete_project_team',
                'transfer_project_team',
            ],
            'project_equipments' => [
                'project_equipments_list',
                'add_project_equipment',
                'show_project_equipment',
                'edit_project_equipment',
                'edit_status_project_equipment',
                'delete_project_equipment',
            ],
            'invoices' => [
                'invoices_list',
                'add_invoice',
                'edit_invoice',
                'show_invoice',
                'view_payment_invoice',
                'delete_invoice',
                'approve_invoice',
            ],
            'brands' => [
                'brands_list',
                'add_brand',
                'edit_brand',
                'delete_brand',
            ],
            'stores' => [
                'stores_list',
                'add_store',
                'edit_store',
                'delete_store',
            ],
            'project_amers' => [
                'project_amers_list',
                'add_project_amers',
                'show_project_amers',
                'edit_project_amers',
                'delete_project_amers',
                'download_project_amers',
            ],
            'project_types' => [
                'project_types_list',
                'add_project_type',
                'edit_project_type',
                'delete_project_type',
            ],
            'project_type_maintenances' => [
                'project_type_maintenances_list',
                'add_project_type_maintenance',
                'edit_project_type_maintenance',
                'delete_project_type_maintenance',
            ],
            'project_capacities' => [
                'project_capacities_list',
                'add_project_capacity',
                'edit_project_capacity',
                'delete_project_capacity',
            ],
            'project_volts' => [
                'project_volts_list',
                'add_project_volt',
                'edit_project_volt',
                'delete_project_volt',
            ],
            'project_models' => [
                'project_models_list',
                'add_project_model',
                'edit_project_model',
                'delete_project_model',
            ],
            'invoices_amer' => [
                'invoices_amer_list',
                'add_invoice_amer',
                'edit_invoice_amer',
                'show_invoice_amer',
                'approve_invoice_amer',
                'delete_invoice_amer',
            ],
            'reports' => [
                'reports_list',
                'add_report',
                'edit_report',
                'show_report',
                'download_report',
                'delete_report',
            ],
        ];

        // إنشاء الفرق وأضف الصلاحيات المرتبطة بها
        foreach ($teamsAndPermissions as $teamName => $permissions) {
            // إنشاء الفريق
            $team = Team::create(['name' => $teamName]);

            // إنشاء الصلاحيات وربطها بالفريق
            foreach ($permissions as $permissionName) {
                $permission = Permission::firstOrCreate([
                    'name' => $permissionName,
                    'team_id' => $team->id, // ربط الصلاحية بالفريق
                    'guard_name' => 'web',
                ]);
            }
        }

        // إنشاء دور جديد وربطه بجميع الأذونات
        $role = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $role->syncPermissions(Permission::all());

        // تعيين الدور للمستخدم الأول
        $user = user::first();
        if ($user) {
            $user->assignRole($role);
            $this->command->info("Role 'admin' has been assigned to the first user ({$user->name}).");
        } else {
            $this->command->error('No users found in the database.');
        }
    }
}
