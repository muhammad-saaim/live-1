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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->text('question'); // Question title or text
            $table->text('description')->nullable(); // Optional description
            //$table->enum('type', ['single_choice', 'multiple_choice', 'true_false', 'text']); // Question type
            //$table->json('options')->nullable(); // JSON field for storing multiple-choice options
            //$table->string('correct_answer')->nullable(); // Field to store the correct answer(s)
            $table->integer('points')->default(1); // Points for this question
            $table->boolean('is_active')->default(true); // Indicates if the question is active
            $table->foreignId('survey_id')->nullable()->constrained()->onDelete('cascade'); // Foreign key to the survey or quiz
            $table->foreignId('type_id')->nullable()->constrained()->onDelete('cascade'); // Foreign key to the survey or quiz
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
