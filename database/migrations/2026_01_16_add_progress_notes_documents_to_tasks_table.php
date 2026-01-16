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
        Schema::table('tasks', function (Blueprint $table) {
            $table->integer('progress')->default(0)->after('status'); // Progress percentage 0-100
            $table->text('notes')->nullable()->after('progress'); // Task notes/comments from intern
            $table->json('documents')->nullable()->after('notes'); // Store document file paths
            $table->timestamp('started_at')->nullable()->after('documents'); // When task was started
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn(['progress', 'notes', 'documents', 'started_at']);
        });
    }
};
