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
            $table->decimal('quantity', 15, 2)->after('relief_type_id')->comment('Quantity of relief type for this project');
            $table->dropColumn(['start_date', 'end_date']); // Remove dates as economic year handles this
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('quantity');
            $table->date('start_date')->after('budget')->nullable();
            $table->date('end_date')->after('start_date')->nullable();
        });
    }
};
