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
        // Check and add columns only if they don't exist
        if (!Schema::hasColumn('document_folders', 'allowed_intern_ids')) {
            Schema::table('document_folders', function (Blueprint $table) {
                $table->json('allowed_intern_ids')->nullable()->after('allowed_users');
            });
        }
        if (!Schema::hasColumn('document_folders', 'allowed_team_leader_ids')) {
            Schema::table('document_folders', function (Blueprint $table) {
                $table->json('allowed_team_leader_ids')->nullable()->after('allowed_intern_ids');
            });
        }
        if (!Schema::hasColumn('document_folders', 'allowed_startup_ids')) {
            Schema::table('document_folders', function (Blueprint $table) {
                $table->json('allowed_startup_ids')->nullable()->after('allowed_team_leader_ids');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('document_folders', function (Blueprint $table) {
            $columns = [];
            if (Schema::hasColumn('document_folders', 'allowed_intern_ids')) {
                $columns[] = 'allowed_intern_ids';
            }
            if (Schema::hasColumn('document_folders', 'allowed_team_leader_ids')) {
                $columns[] = 'allowed_team_leader_ids';
            }
            if (Schema::hasColumn('document_folders', 'allowed_startup_ids')) {
                $columns[] = 'allowed_startup_ids';
            }
            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
