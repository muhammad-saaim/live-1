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
        Schema::create('relations', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. "father", "mother", "brother"
            $table->string('inverse_name')->nullable(); // e.g. "son", "daughter", "sibling"
            $table->enum('gender', ['male', 'female', 'other'])->nullable(); // Optional: helps with pronoun use
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('relations');
    }
};
