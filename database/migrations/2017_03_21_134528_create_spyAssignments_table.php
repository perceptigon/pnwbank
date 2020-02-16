<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpyAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('defense')->create('spyAssignments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('attacker_id');
            $table->integer('defender_id');
            $table->integer('type');
            $table->integer('round');
            $table->boolean('success');
            $table->text('results');
            $table->boolean('sent');
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
        Schema::dropIfExists('spyAssignments');
    }
}
