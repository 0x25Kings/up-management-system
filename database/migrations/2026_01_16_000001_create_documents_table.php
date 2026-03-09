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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('intern_id');
            $table->unsignedBigInteger('folder_id')->nullable();
            $table->string('name');
            $table->string('file_path');
            $table->string('file_size'); // Store as string for display (e.g., "2.5 MB")
            $table->string('file_type'); // mime type
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('intern_id')->references('id')->on('interns')->onDelete('cascade');
            $table->foreign('folder_id')->references('id')->on('document_folders')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
