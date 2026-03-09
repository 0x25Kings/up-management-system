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
        Schema::table('startup_submissions', function (Blueprint $table) {
            // Admin uploaded MOA document
            if (!Schema::hasColumn('startup_submissions', 'admin_moa_document_path')) {
                $table->string('admin_moa_document_path')->nullable()->after('admin_notes');
            }
            if (!Schema::hasColumn('startup_submissions', 'admin_moa_document_filename')) {
                $table->string('admin_moa_document_filename')->nullable()->after('admin_moa_document_path');
            }
            if (!Schema::hasColumn('startup_submissions', 'admin_moa_uploaded_at')) {
                $table->timestamp('admin_moa_uploaded_at')->nullable()->after('admin_moa_document_filename');
            }
            if (!Schema::hasColumn('startup_submissions', 'admin_moa_uploaded_by')) {
                $table->foreignId('admin_moa_uploaded_by')->nullable()->after('admin_moa_uploaded_at')->constrained('users')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('startup_submissions', function (Blueprint $table) {
            $table->dropForeign(['admin_moa_uploaded_by']);
            $table->dropColumn([
                'admin_moa_document_path',
                'admin_moa_document_filename',
                'admin_moa_uploaded_at',
                'admin_moa_uploaded_by'
            ]);
        });
    }
};
