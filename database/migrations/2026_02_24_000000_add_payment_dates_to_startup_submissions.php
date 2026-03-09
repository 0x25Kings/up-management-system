<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('startup_submissions', function (Blueprint $table) {
            if (!Schema::hasColumn('startup_submissions', 'payment_start_date')) {
                $table->date('payment_start_date')->nullable()->after('admin_moa_uploaded_by');
            }
            if (!Schema::hasColumn('startup_submissions', 'payment_end_date')) {
                $table->date('payment_end_date')->nullable()->after('payment_start_date');
            }
            if (!Schema::hasColumn('startup_submissions', 'rejection_remarks')) {
                $table->text('rejection_remarks')->nullable()->after('payment_end_date');
            }
        });
    }

    public function down(): void
    {
        Schema::table('startup_submissions', function (Blueprint $table) {
            $columns = ['payment_start_date', 'payment_end_date', 'rejection_remarks'];
            foreach ($columns as $col) {
                if (Schema::hasColumn('startup_submissions', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
