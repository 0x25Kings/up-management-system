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
        Schema::create('startup_submissions', function (Blueprint $table) {
            $table->id();
            $table->string('tracking_code')->unique();
            $table->string('company_name');
            $table->string('contact_person');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->enum('type', ['document', 'moa', 'finance']);
            
            // For document submissions
            $table->string('document_type')->nullable();
            $table->string('file_path')->nullable();
            $table->string('original_filename')->nullable();
            
            // For MOA requests
            $table->string('moa_purpose')->nullable();
            $table->text('moa_details')->nullable();
            
            // For finance submissions
            $table->string('invoice_number')->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->string('payment_proof_path')->nullable();
            
            // Common fields
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'under_review', 'approved', 'rejected', 'completed'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('startup_submissions');
    }
};
