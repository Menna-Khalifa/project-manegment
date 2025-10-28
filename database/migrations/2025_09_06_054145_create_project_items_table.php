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
        Schema::create('project_items', function (Blueprint $table) {
            $table->id();
            // project_id
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            // section_id
            $table->foreignId('section_id')->constrained('sections')->cascadeOnDelete();
            // section_item_id
            $table->foreignId('section_item_id')->constrained('section_items')->cascadeOnDelete();
            $table->integer('qty');
            $table->integer('received_qty');
            $table->integer('executed_qty');
            $table->date('expected_arrival')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_items');
    }
};
