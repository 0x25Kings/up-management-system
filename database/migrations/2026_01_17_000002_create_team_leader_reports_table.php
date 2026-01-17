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
        Schema::create('team_leader_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_leader_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->string('title');
            $table->enum('report_type', ['weekly', 'monthly', 'performance', 'attendance', 'custom'])->default('weekly');
            $table->text('summary');
            $table->text('accomplishments')->nullable();
            $table->text('challenges')->nullable();
            $table->text('recommendations')->nullable();
            $table->date('period_start')->nullable();
            $table->date('period_end')->nullable();
            $table->json('intern_highlights')->nullable(); // JSON array of intern performance highlights
            $table->json('task_statistics')->nullable(); // JSON for task completion stats
            $table->json('attachments')->nullable();
            $table->enum('status', ['draft', 'submitted', 'reviewed', 'acknowledged'])->default('draft');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('admin_feedback')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_leader_reports');
    }
};
