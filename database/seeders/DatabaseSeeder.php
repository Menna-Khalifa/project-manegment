<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Brand;
use App\Models\InvoiceAmer;
use App\Models\LandingPageSection;
use App\Models\ProjectAmer;
use App\Models\ProjectAmerItem;
use App\Models\Store;
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

    //     // إنشاء 5 brands
    // Brand::factory()->count(20)->create();
    
    // // إنشاء 15 stores
    // Store::factory()->count(15)->create();
    
    // // إنشاء 30 projects
    // ProjectAmer::factory()->count(30)->create();
    
    // // إنشاء 120 project items
    // ProjectAmerItem::factory()->count(120)->create();
    
    // // إنشاء 30 invoices
    // InvoiceAmer::factory()->count(30)->create();
    }
}
