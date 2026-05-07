<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->unsignedTinyInteger('age');
            $table->text('address');
            $table->date('birthdate');
            $table->enum('course', ['BSIT', 'BSIS', 'BSCS']);
            $table->enum('year', ['1st', '2nd', '3rd', '4th']);
            $table->string('profile_picture')->nullable();
            $table->enum('status', ['pending', 'enrolled', 'disapproved'])->default('pending');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};