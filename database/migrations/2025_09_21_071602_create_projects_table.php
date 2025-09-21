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
		Schema::create('projects', function (Blueprint $table) {
			$table->id();
			$table->string('name'); // Project name
			$table->foreignId('economic_year_id')->constrained()->onDelete('cascade'); // Economic year reference
			$table->foreignId('relief_type_id')->constrained()->onDelete('cascade'); // Relief type reference
			$table->decimal('budget', 15, 2); // Project budget
			$table->date('start_date'); // Project start date
			$table->date('end_date'); // Project end date
			$table->text('remarks')->nullable(); // Additional remarks/notes
			$table->timestamps();

			// Indexes for better performance
			$table->index(['economic_year_id', 'relief_type_id']);
			$table->index(['start_date', 'end_date']);
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('projects');
	}
};