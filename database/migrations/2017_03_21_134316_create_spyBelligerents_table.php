<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpyBelligerentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('defense')->create('spyBelligerents', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('aID');
            $table->string('aName');
            $table->boolean('is_attacking');
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
        Schema::dropIfExists('spyBelligerents');
    }
}
