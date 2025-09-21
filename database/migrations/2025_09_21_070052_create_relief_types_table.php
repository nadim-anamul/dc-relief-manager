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
		Schema::create('relief_types', function (Blueprint $table) {
			$table->id();
			$table->string('name'); // e.g., "Rice", "Dal", "Oil"
			$table->string('name_bn')->nullable(); // Bengali name
			$table->text('description')->nullable(); // Description in English
			$table->text('description_bn')->nullable(); // Description in Bengali
			$table->string('unit')->nullable(); // Unit of measurement (kg, liter, etc.)
			$table->string('unit_bn')->nullable(); // Unit in Bengali
			$table->string('color_code')->nullable(); // Hex color code for UI
			$table->boolean('is_active')->default(true);
			$table->integer('sort_order')->default(0); // For ordering in lists
			$table->timestamps();

			$table->index(['is_active', 'sort_order']);
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('relief_types');
	}
};