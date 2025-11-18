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
        Schema::create('project_amer_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_amer_id')->constrained()->onDelete('cascade');
            $table->foreignId('project_type_id')->constrained()->onDelete('cascade');
            $table->foreignId('project_model_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('project_capacity_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('project_volt_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('brand_id')->nullable()->constrained()->onDelete('cascade');
            $table->integer('qty');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_amer_items');
    }
};
