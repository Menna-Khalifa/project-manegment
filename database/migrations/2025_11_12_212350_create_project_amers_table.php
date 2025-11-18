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
        Schema::create('project_amers', function (Blueprint $table) {
            $table->id();
            $table->string('po_num');
            $table->enum('dept', ['project', 'facility', 'maintenance', 'other']);
            $table->enum('region', ['western_province', 'central_province', 'eastern_province', 'general']);
            $table->foreignId('store_id')->constrained('stores');
            $table->foreignId('user_id')->constrained('users');
            $table->text('po_file')->nullable();
            $table->enum('priority', ['high', 'medium', 'low']);
            $table->date('date');
            $table->enum('request_status', ['new_order', 'cancelled', 'under_working','completed', 'on_hold'])->default('new_order');
            $table->decimal('amount', 15, 2);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_amers');
    }
};
