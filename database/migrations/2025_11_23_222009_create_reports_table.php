<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
           Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->enum('report_type', ['start_up_report', 'work_completed', 'sites_refer_report']);
            $table->date('report_date');
            
            $table->foreignId('store_id')->nullable()->constrained('stores')->onDelete('cascade');
            $table->string('store_name')->nullable();
            $table->string('store_city')->nullable();
            $table->foreignId('project_amer_id')->nullable()->constrained('project_amers')->onDelete('cascade');
            
            $table->json('checklist_items')->nullable();
            $table->json('custom_fields')->nullable(); // للحقول المخصصة
            $table->json('units')->nullable(); // للـ units data
            $table->json('images')->nullable(); // لتخزين مسارات الصور
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
