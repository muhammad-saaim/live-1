<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersSurveysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_surveys', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('survey_id');
            $table->boolean('is_completed')->default(false); // Whether the user has completed the survey
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('survey_id')->references('id')->on('surveys')->onDelete('cascade');

            // Unique constraint for user_id and survey_id combination
            $table->unique(['user_id', 'survey_id']);

            // Indexes for faster querying
            $table->index('user_id');
            $table->index('survey_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_surveys');
    }
}
