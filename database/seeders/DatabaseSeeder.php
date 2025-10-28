<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\LandingPageSection;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // define seeder file
        $this->call([
            GroupSeeder::class,
            UserTableSeeder::class,
            PermissionTableSeeder::class,
            SectionSeeder::class,
            SectionItemSeeder::class,
            EquipmentSeeder::class,
            ProjectSeeder::class,
            ProjectItemSeeder::class,
            ProjectTeamSeeder::class,
            ProjectEquipmentSeeder::class,
            ProjectInvoicesSeeder::class,
        ]);
    }
}
