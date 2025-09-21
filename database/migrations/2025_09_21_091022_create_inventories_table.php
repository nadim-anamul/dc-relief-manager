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
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('relief_item_id')->constrained()->onDelete('cascade');
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->decimal('current_stock', 15, 3)->default(0); // Current available quantity
            $table->decimal('reserved_stock', 15, 3)->default(0); // Reserved for approved applications
            $table->decimal('total_received', 15, 3)->default(0); // Total received in this project
            $table->decimal('total_distributed', 15, 3)->default(0); // Total distributed
            $table->decimal('unit_price', 15, 2)->nullable(); // Current market price per unit
            $table->date('last_updated')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->unique(['relief_item_id', 'project_id']); // One inventory record per item per project
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
