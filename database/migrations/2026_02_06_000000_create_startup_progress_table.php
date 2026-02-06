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
        Schema::create('startup_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('startup_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->enum('milestone_type', ['development', 'funding', 'partnership', 'launch', 'achievement', 'other'])->default('other');
            $table->string('file_path')->nullable();
            $table->string('original_filename')->nullable();
            $table->enum('status', ['submitted', 'reviewed', 'acknowledged'])->default('submitted');
            $table->text('admin_comment')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('startup_progress');
    }
};
