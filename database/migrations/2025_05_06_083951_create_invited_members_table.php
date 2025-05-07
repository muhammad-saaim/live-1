<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvitedMembersTable extends Migration
{
    public function up()
    {
        Schema::create('invited_members', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inviter_id'); // links to members.id
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('relation')->nullable(); // e.g., brother, sister, etc.
            $table->enum('invite', ['send'])->nullable()->default(null);
            $table->string('status')->default('pending');
            $table->timestamps();
        
            $table->foreign('inviter_id')->references('id')->on('users')->onDelete('cascade');
        });
        
    }

    public function down()
    {
        Schema::dropIfExists('invited_members');
    }
}
