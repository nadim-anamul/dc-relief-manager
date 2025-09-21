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
		Schema::create('relief_applications', function (Blueprint $table) {
			$table->id();
			$table->string('organization_name'); // Organization name
			$table->foreignId('organization_type_id')->nullable()->constrained()->onDelete('set null'); // Organization type reference
			$table->date('date'); // Application date
			$table->foreignId('zilla_id')->constrained()->onDelete('cascade'); // Zilla reference
			$table->foreignId('upazila_id')->constrained()->onDelete('cascade'); // Upazila reference
			$table->foreignId('union_id')->constrained()->onDelete('cascade'); // Union reference
			$table->foreignId('ward_id')->constrained()->onDelete('cascade'); // Ward reference
			$table->string('subject'); // Application subject
			$table->foreignId('relief_type_id')->constrained()->onDelete('cascade'); // Relief type reference
			$table->string('applicant_name'); // Applicant name
			$table->string('applicant_designation')->nullable(); // Applicant designation
			$table->string('applicant_phone'); // Applicant phone number
			$table->text('applicant_address'); // Applicant address
			$table->text('organization_address'); // Organization address
			$table->decimal('amount_requested', 15, 2); // Amount requested
			$table->text('details'); // Application details
			$table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); // Application status
			$table->string('application_file')->nullable(); // File path for uploaded application
			$table->timestamps();

			// Indexes for better performance
			$table->index(['status', 'date']);
			$table->index(['relief_type_id', 'status']);
			$table->index(['organization_type_id', 'status']);
			$table->index(['zilla_id', 'upazila_id', 'union_id', 'ward_id']);
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('relief_applications');
	}
};