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
		Schema::create('economic_years', function (Blueprint $table) {
			$table->id();
			$table->string('name'); // e.g., "2023-2024"
			$table->string('name_bn')->nullable(); // Bengali name
			$table->date('start_date'); // Start date of economic year
			$table->date('end_date'); // End date of economic year
			$table->boolean('is_active')->default(true);
			$table->boolean('is_current')->default(false); // Only one can be current
			$table->timestamps();

			// Ensure only one current economic year
			$table->index(['is_current']);
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('economic_years');
	}
};