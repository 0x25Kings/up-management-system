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
        Schema::table('attendances', function (Blueprint $table) {
            $table->decimal('overtime_hours', 5, 2)->default(0)->after('hours_worked');
            $table->decimal('undertime_hours', 5, 2)->default(0)->after('overtime_hours');
            $table->boolean('overtime_approved')->default(false)->after('undertime_hours');
            $table->unsignedBigInteger('approved_by')->nullable()->after('overtime_approved');
            $table->timestamp('approved_at')->nullable()->after('approved_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn(['overtime_hours', 'undertime_hours', 'overtime_approved', 'approved_by', 'approved_at']);
        });
    }
};
