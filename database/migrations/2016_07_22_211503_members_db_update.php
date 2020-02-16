<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MembersDbUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->mediumInteger("nID")->index();
            $table->string("name")->index();
            $table->string("leader")->index();
            $table->integer("score")->unsigned();
            $table->integer("activity");
            $table->smallInteger("cities")->unsigned();
            $table->integer("infra")->unsigned();
            $table->integer("land")->unsigned();
            $table->smallInteger("ironWorks")->unsigned();
            $table->smallInteger("baxuiteWorks")->unsigned();
            $table->smallInteger("armsStockpile")->unsigned();
            $table->smallInteger("gasReserve")->unsigned();
            $table->smallInteger("massIrrigation")->unsigned();
            $table->smallInteger("intTradeCenter")->unsigned();
            $table->smallInteger("mlp")->unsigned();
            $table->smallInteger("irondome")->unsigned();
            $table->smallInteger("vitalDefSys")->unsigned();
            $table->smallInteger("cia")->unsigned();
            $table->smallInteger("uraniumEnrich")->unsigned();
            $table->smallInteger("propBureau")->unsigned();
            $table->smallInteger("cenCivEng")->unsigned();
        });

        Schema::create('memberHistory', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->mediumInteger("nID")->index();
            $table->string("name")->index();
            $table->string("leader")->index();
            $table->integer("score")->unsigned();
            $table->integer("activity");
            $table->smallInteger("cities")->unsigned();
            $table->integer("infra")->unsigned();
            $table->integer("land")->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop("members");
        Schema::drop("memberHistory");
    }
}
