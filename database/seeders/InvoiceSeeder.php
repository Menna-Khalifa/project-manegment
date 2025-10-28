<?php

namespace Database\Seeders;

use App\Models\Invoice;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 30 random invoices
        Invoice::factory()->count(10)->paid()->create();
        Invoice::factory()->count(10)->unpaid()->create();
        Invoice::factory()->count(10)->overdue()->create();
    }
}
