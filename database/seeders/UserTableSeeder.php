<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Admin;
use App\Models\Group;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // حذف جميع البيانات من الجداول
        DB::table('users')->delete();

        // الحصول على المجموعات
        $mainGroup = Group::where('name', 'المشرفين الرئيسيين')->first();
        $contentGroup = Group::where('name', 'مشرفي المحتوى')->first();
        $usersGroup = Group::where('name', 'مشرفي المستخدمين')->first();
        $reportsGroup = Group::where('name', 'مشرفي التقارير')->first();

        // إنشاء المشرف الرئيسي
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('123456789'),
            'phone' => '01234567890',
            'group_id' => $mainGroup->id,
            'type' => 'admin',
            'status' => 'active',
        ]);

        // إنشاء مشرفين إضافيين باستخدام factory
        User::factory()
            ->count(2)
            ->create(['group_id' => $contentGroup->id]); // مشرفي محتوى

        User::factory()
            ->count(2)
            ->create(['group_id' => $usersGroup->id]); // مشرفي مستخدمين

        User::factory()
            ->count(1)
            ->create(['group_id' => $reportsGroup->id]); // مشرف تقارير

        // إنشاء مشرفين نشطين
        User::factory()
            ->active()
            ->adminType()
            ->count(3)
            ->create(['group_id' => $mainGroup->id]);

        // إنشاء مشرفين غير نشطين
        User::factory()
            ->active()
            ->userType()
            ->count(2)
            ->create(['group_id' => $mainGroup->id]);
    }
}
