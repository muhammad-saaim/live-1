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
        Schema::create('surveys', function (Blueprint $table) {
            $table->id(); // Auto-incrementing ID
            $table->foreignId('model_id')->constrained('survey_models')->onDelete('cascade');
            $table->string('title'); // Title of the survey
            $table->text('description')->nullable(); // Description of the survey
            $table->boolean('is_active')->default(true); // Status of the survey (active/inactive)
            $table->boolean('is_default')->default(false);
            $table->json('applies_to')->nullable(); //'Individual','Group','Family'
            $table->json('targets')->nullable();// User is evaluator, Admin is evaluator, User is evaluatee, Admin is evaluatee
            $table->timestamps(); // Created at and updated at columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surveys');
    }
};
