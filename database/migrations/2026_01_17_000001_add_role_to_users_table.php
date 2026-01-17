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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['super_admin', 'team_leader', 'user'])->default('user')->after('password');
            $table->foreignId('school_id')->nullable()->after('role')->constrained('schools')->nullOnDelete();
        });

        // Migrate existing admin users to super_admin role
        DB::statement("UPDATE users SET role = 'super_admin' WHERE is_admin = 1");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['school_id']);
            $table->dropColumn(['role', 'school_id']);
        });
    }
};
