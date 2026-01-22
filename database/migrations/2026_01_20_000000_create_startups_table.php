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
        Schema::create('startups', function (Blueprint $table) {
            $table->id();
            $table->string('startup_code')->unique();
            $table->string('password');
            $table->string('company_name');
            $table->string('contact_person');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('room_number')->nullable();
            $table->string('address')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->enum('moa_status', ['none', 'pending', 'active', 'expired'])->default('none');
            $table->date('moa_expiry')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // Add startup_id to startup_submissions table
        Schema::table('startup_submissions', function (Blueprint $table) {
            $table->foreignId('startup_id')->nullable()->after('id')->constrained('startups')->nullOnDelete();
        });

        // Add startup_id to room_issues table
        Schema::table('room_issues', function (Blueprint $table) {
            $table->foreignId('startup_id')->nullable()->after('id')->constrained('startups')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('room_issues', function (Blueprint $table) {
            $table->dropForeign(['startup_id']);
            $table->dropColumn('startup_id');
        });

        Schema::table('startup_submissions', function (Blueprint $table) {
            $table->dropForeign(['startup_id']);
            $table->dropColumn('startup_id');
        });

        Schema::dropIfExists('startups');
    }
};
