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
            // Add startup_id if it doesn't exist
            if (!Schema::hasColumn('startup_submissions', 'startup_id')) {
                $table->foreignId('startup_id')->nullable()->after('id')->constrained('startups')->onDelete('cascade');
            }
            
            // Add title for document uploads
            if (!Schema::hasColumn('startup_submissions', 'title')) {
                $table->string('title')->nullable()->after('type');
            }
            
            // Add moa_duration for MOA requests
            if (!Schema::hasColumn('startup_submissions', 'moa_duration')) {
                $table->string('moa_duration')->nullable()->after('moa_details');
            }
            
            // Add payment_method for finance submissions
            if (!Schema::hasColumn('startup_submissions', 'payment_method')) {
                $table->string('payment_method')->nullable()->after('amount');
            }
            
            // Add payment_date for finance submissions
            if (!Schema::hasColumn('startup_submissions', 'payment_date')) {
                $table->date('payment_date')->nullable()->after('payment_method');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('startup_submissions', function (Blueprint $table) {
            $table->dropForeign(['startup_id']);
            $table->dropColumn(['startup_id', 'title', 'moa_duration', 'payment_method', 'payment_date']);
        });
    }
};
