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
		Schema::table('relief_applications', function (Blueprint $table) {
			$table->decimal('approved_amount', 15, 2)->nullable()->after('amount_requested'); // Amount approved by admin
			$table->foreignId('project_id')->nullable()->constrained()->onDelete('set null')->after('relief_type_id'); // Project to deduct budget from
			$table->text('admin_remarks')->nullable()->after('details'); // Admin remarks/comments
			$table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null')->after('admin_remarks'); // User who approved
			$table->timestamp('approved_at')->nullable()->after('approved_by'); // When it was approved
			$table->foreignId('rejected_by')->nullable()->constrained('users')->onDelete('set null')->after('approved_at'); // User who rejected
			$table->timestamp('rejected_at')->nullable()->after('rejected_by'); // When it was rejected
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table('relief_applications', function (Blueprint $table) {
			$table->dropForeign(['project_id']);
			$table->dropForeign(['approved_by']);
			$table->dropForeign(['rejected_by']);
			$table->dropColumn([
				'approved_amount',
				'project_id',
				'admin_remarks',
				'approved_by',
				'approved_at',
				'rejected_by',
				'rejected_at'
			]);
		});
	}
};