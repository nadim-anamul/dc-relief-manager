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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->boolean('is_approved')->default(false)->after('organization_type_id');
            $table->string('temp_password')->nullable()->after('is_approved');
            $table->timestamp('temp_password_expires_at')->nullable()->after('temp_password');
            $table->boolean('must_change_password')->default(false)->after('temp_password_expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone',
                'is_approved',
                'temp_password',
                'temp_password_expires_at',
                'must_change_password'
            ]);
        });
    }
};
