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
        Schema::table('schools', function (Blueprint $table) {
            $table->date('interns_start_date')->nullable()->after('notes');
            $table->boolean('is_finished')->default(false)->after('interns_start_date');
            $table->timestamp('finished_at')->nullable()->after('is_finished');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->dropColumn(['interns_start_date', 'is_finished', 'finished_at']);
        });
    }
};
