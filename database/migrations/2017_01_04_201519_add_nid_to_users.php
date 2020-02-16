<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNidToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Because the users table is so old and it just doesn't fucking with with anything
        // we're gonna drop the users table and then re-create it so it fucking works with foreign keys

        Schema::drop("users");

        Schema::create("users", function(Blueprint $table) {
            // Recreate the table
            $table->increments("id")->unsigned();
            $table->timestamps();
            $table->string("username", 30);
            $table->string("email");
            $table->string("password");
            $table->rememberToken();
            $table->boolean("isAdmin");
            $table->string("title")->default("member");
            $table->json("permissions")->nullable();

            // Now add the new shit
            $table->integer("nID");
            $table->string("verifyToken");
            $table->boolean("isVerified");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop("users");

        Schema::create("users", function(Blueprint $table) {
            // Recreate the table
            $table->increments("id")->unsigned();
            $table->timestamps();
            $table->string("username", 30);
            $table->string("email");
            $table->string("password");
            $table->rememberToken();
            $table->boolean("isAdmin");
            $table->string("title")->default("member");
            $table->json("permissions");
        });
    }
}
