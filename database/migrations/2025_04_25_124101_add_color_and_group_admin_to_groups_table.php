<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColorAndGroupAdminToGroupsTable extends Migration
{
    public function up()
    {
        Schema::table('groups', function (Blueprint $table) {
            $table->string('color')->nullable()->after('description'); // Adjust 'name' to the appropriate existing column
            $table->unsignedBigInteger('group_admin')->nullable()->after('name');

            // Optional: If group_admin references a user or another table
            $table->foreign('group_admin')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('groups', function (Blueprint $table) {
            $table->dropColumn(['color', 'group_admin']);
        });
    }
}
