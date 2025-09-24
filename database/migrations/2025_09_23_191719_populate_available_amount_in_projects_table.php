<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Populate available_amount with allocated_amount values for existing projects
        DB::statement('UPDATE projects SET available_amount = allocated_amount WHERE available_amount = 0');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reset available_amount to 0
        DB::statement('UPDATE projects SET available_amount = 0');
    }
};
