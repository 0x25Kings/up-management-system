<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('startups', function (Blueprint $table) {
            $table->decimal('payment_amount', 10, 2)->nullable()->after('moa_expiry');
            $table->string('payment_duration')->nullable()->after('payment_amount'); // monthly, quarterly, semi_annual, annual
            $table->date('payment_start_date')->nullable()->after('payment_duration');
            $table->date('payment_due_date')->nullable()->after('payment_start_date');
            $table->date('next_payment_due')->nullable()->after('payment_due_date');
            $table->boolean('payment_reminder_sent')->default(false)->after('next_payment_due');
            $table->boolean('moa_expiry_reminder_sent')->default(false)->after('payment_reminder_sent');
        });
    }

    public function down(): void
    {
        Schema::table('startups', function (Blueprint $table) {
            $table->dropColumn([
                'payment_amount',
                'payment_duration',
                'payment_start_date',
                'payment_due_date',
                'next_payment_due',
                'payment_reminder_sent',
                'moa_expiry_reminder_sent',
            ]);
        });
    }
};
