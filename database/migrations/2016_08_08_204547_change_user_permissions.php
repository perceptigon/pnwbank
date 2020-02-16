<?php

use Illuminate\Database\Migrations\Migration;

class ChangeUserPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function ($table) {
            $table->dropColumn('permissions');
        });
        Schema::table('users', function ($table) {
            $table->json('permissions')->nullable();
            $table->string("title")->default("Member")->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function ($table) {
            $table->string('permissions')->change();
            $table->string("title")->default("")->change();
        });
    }
}
