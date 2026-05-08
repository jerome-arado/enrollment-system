<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enrollment_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollment_id')->constrained()->onDelete('cascade');
            $table->string('label');               // e.g. "Form 137", "Birth Certificate"
            $table->string('original_name');       // original filename shown to user
            $table->string('path');                // storage path
            $table->string('mime_type');           // application/pdf, etc.
            $table->unsignedBigInteger('size');    // bytes
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('remarks')->nullable();   // admin notes per document
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enrollment_documents');
    }
};