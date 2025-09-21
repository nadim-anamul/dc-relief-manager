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
        Schema::create('relief_items', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Rice", "Wheat", "Cash", "Medical Supplies"
            $table->string('name_bn')->nullable(); // Bengali name
            $table->string('type'); // 'monetary', 'food', 'medical', 'shelter', 'other'
            $table->string('unit'); // 'BDT', 'metric_ton', 'kg', 'liter', 'piece', 'box'
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('relief_items');
    }
};
