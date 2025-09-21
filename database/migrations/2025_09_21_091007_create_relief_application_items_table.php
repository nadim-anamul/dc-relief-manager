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
        Schema::create('relief_application_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('relief_application_id')->constrained()->onDelete('cascade');
            $table->foreignId('relief_item_id')->constrained()->onDelete('cascade');
            $table->decimal('quantity_requested', 15, 3); // Can handle large quantities like metric tons
            $table->decimal('quantity_approved', 15, 3)->nullable(); // Approved quantity
            $table->decimal('unit_price', 15, 2)->nullable(); // Price per unit (for monetary items)
            $table->decimal('total_amount', 15, 2)->nullable(); // Total amount (quantity * unit_price)
            $table->text('remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('relief_application_items');
    }
};
