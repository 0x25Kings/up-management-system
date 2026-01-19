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
        Schema::table('document_folders', function (Blueprint $table) {
            $table->unsignedBigInteger('intern_id')->nullable()->change();
            $table->unsignedBigInteger('created_by_admin')->nullable()->after('intern_id');
            $table->enum('folder_type', ['admin', 'intern', 'shared'])->default('intern')->after('created_by_admin');
            $table->json('allowed_users')->nullable()->after('folder_type'); // Store user types: ['intern', 'team_leader', 'startup']
            $table->string('storage_path')->nullable()->after('allowed_users'); // Physical path in storage
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('document_folders', function (Blueprint $table) {
            $table->dropColumn(['created_by_admin', 'folder_type', 'allowed_users', 'storage_path']);
        });
    }
};
