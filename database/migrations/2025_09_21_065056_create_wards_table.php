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
		Schema::create('wards', function (Blueprint $table) {
			$table->id();
			$table->foreignId('union_id')->constrained()->onDelete('cascade');
			$table->string('name');
			$table->string('name_bn')->nullable(); // Bengali name
			$table->string('code')->unique();
			$table->boolean('is_active')->default(true);
			$table->timestamps();

			$table->index(['union_id', 'code']);
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('wards');
	}
};