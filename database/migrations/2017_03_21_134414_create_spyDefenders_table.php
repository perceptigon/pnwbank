<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpyDefendersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('defense')->create('spyDefenders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('nID');
            $table->string('nName');
            $table->string('nRuler');
            $table->integer('maxSpies');
            $table->integer('score');
            $table->integer('soldiers');
            $table->integer('tanks');
            $table->integer('aircraft');
            $table->integer('ships');
            $table->string('alliance');
            $table->integer('slots');
            $table->boolean('cia');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('spyDefenders');
    }
}
