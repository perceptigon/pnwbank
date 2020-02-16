<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInactivityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("inactivity", function(Blueprint $table) {
            $table->increments("id");
            $table->timestamps();
            $table->integer("nID")->unsigned();
            $table->string("leader");
            $table->boolean("isInactive")->comment("If true, this nation is currently inactive");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop("inactivity");
    }
}
