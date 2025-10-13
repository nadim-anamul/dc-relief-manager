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
		Schema::create('upazilas', function (Blueprint $table) {
			$table->id();
			$table->foreignId('zilla_id')->constrained()->onDelete('cascade');
			$table->string('name');
			$table->string('name_bn')->nullable(); // Bengali name
			$table->boolean('is_active')->default(true);
			$table->timestamps();

			$table->index(['zilla_id', 'name']);
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('upazilas');
	}
};