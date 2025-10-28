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
        Schema::create('project_equipment', function (Blueprint $table) {
            $table->id();
            // project_id
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            // equipment_id
            $table->foreignId('equipment_id')->constrained('equipments')->cascadeOnDelete();
            // qty
            $table->integer('qty');
            $table->enum('status', ['available', 'unavailable', 'delivered', 'not_delivered'])->default('unavailable');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_equipment');
    }
};
