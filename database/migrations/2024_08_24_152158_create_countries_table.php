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
        Schema::create('countries', function (Blueprint $table) {
            $table->id(); // Auto-incrementing ID
            $table->string('name');
            $table->string('code', 2);
            $table->integer('phone');
            $table->string('symbol');
            $table->string('capital');
            $table->string('currency');
            $table->string('continent');
            $table->string('continent_code', 2);
            $table->string('alpha_3', 3);
            $table->timestamps(); // Created at and updated at columns
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
