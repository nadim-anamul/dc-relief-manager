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
		Schema::table('projects', function (Blueprint $table) {
			$table->text('ministry_address')->nullable()->after('remarks')->comment('ছাড়প্রদানকারী মন্ত্রণালয়ের ঠিকানা');
			$table->string('office_order_number')->nullable()->after('ministry_address')->comment('অফিস আদেশ নং');
			$table->date('office_order_date')->nullable()->after('office_order_number')->comment('অফিস আদেশ তারিখ');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('projects', function (Blueprint $table) {
			$table->dropColumn(['ministry_address', 'office_order_number', 'office_order_date']);
		});
	}
};
