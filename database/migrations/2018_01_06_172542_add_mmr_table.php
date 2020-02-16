<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMmrTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("mmr", function(Blueprint $table) {
            $table->increments("id");
            $table->timestamps();
            $table->tinyInteger("cityNum")->unsigned();
            $table->integer("money")->unsigned();
            $table->integer("food")->unsigned();
            $table->integer("uranium")->unsigned();
            $table->integer("gas")->unsigned();
            $table->integer("munitions")->unsigned();
            $table->integer("steel")->unsigned();
            $table->integer("aluminum")->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop("mmr");
    }
}
