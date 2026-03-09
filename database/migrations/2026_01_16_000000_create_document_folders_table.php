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
        Schema::create('document_folders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('intern_id');
            $table->string('name');
            $table->string('color')->default('#3B82F6'); // Default blue color
            $table->text('description')->nullable();
            $table->unsignedBigInteger('parent_folder_id')->nullable(); // For nested folders
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('intern_id')->references('id')->on('interns')->onDelete('cascade');
            $table->foreign('parent_folder_id')->references('id')->on('document_folders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_folders');
    }
};
