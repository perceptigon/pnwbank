<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOilgrantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oilgrants', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('nID');
            $table->string('leader');
            $table->string('nationName');
            $table->boolean('isPending');
            $table->boolean('isSent');
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
        Schema::dropIfExists('oilgrants');
    }
}
