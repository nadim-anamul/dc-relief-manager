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
        // Remove code columns from all administrative division tables
        Schema::table('zillas', function (Blueprint $table) {
            $table->dropUnique(['code']);
            $table->dropColumn('code');
        });

        Schema::table('upazilas', function (Blueprint $table) {
            $table->dropIndex(['zilla_id', 'code']);
            $table->dropUnique(['code']);
            $table->dropColumn('code');
            // Add new index for name-based uniqueness
            $table->index(['zilla_id', 'name']);
        });

        Schema::table('unions', function (Blueprint $table) {
            $table->dropIndex(['upazila_id', 'code']);
            $table->dropUnique(['code']);
            $table->dropColumn('code');
            // Add new index for name-based uniqueness
            $table->index(['upazila_id', 'name']);
        });

        Schema::table('wards', function (Blueprint $table) {
            $table->dropIndex(['union_id', 'code']);
            $table->dropUnique(['code']);
            $table->dropColumn('code');
            // Add new index for name-based uniqueness
            $table->index(['union_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore code columns to all administrative division tables
        Schema::table('zillas', function (Blueprint $table) {
            $table->string('code')->unique()->after('name_bn');
        });

        Schema::table('upazilas', function (Blueprint $table) {
            $table->string('code')->unique()->after('name_bn');
            $table->dropIndex(['zilla_id', 'name']);
            $table->index(['zilla_id', 'code']);
        });

        Schema::table('unions', function (Blueprint $table) {
            $table->string('code')->unique()->after('name_bn');
            $table->dropIndex(['upazila_id', 'name']);
            $table->index(['upazila_id', 'code']);
        });

        Schema::table('wards', function (Blueprint $table) {
            $table->string('code')->unique()->after('name_bn');
            $table->dropIndex(['union_id', 'name']);
            $table->index(['union_id', 'code']);
        });
    }
};
