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
        Schema::create('personal_info', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('biography');
            $table->string('full_name');
            $table->string('city')->nullable();
            $table->string('birthdate')->nullable();
            $table->string('blood_type')->nullable();
            $table->string('graduated_department')->nullable();
            $table->string('graduated_school')->nullable();
            $table->string('graduated_year')->nullable();
            $table->string('phone')->nullable();
            $table->string('profession')->nullable();
            $table->string('current_job')->nullable();
            $table->string('past_job')->nullable();
            $table->string('hobies')->nullable();
            $table->string('pet_type')->nullable();
            $table->string('skill_1')->nullable();
            $table->string('skill_2')->nullable();
            $table->string('skill_3')->nullable();
            // I dont know how to store images
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal_info');
    }
};
