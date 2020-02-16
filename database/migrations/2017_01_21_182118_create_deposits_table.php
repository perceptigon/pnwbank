<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDepositsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("deposits", function(Blueprint $table) {
            $table->timestamps();
            $table->increments("id");
            $table->integer("accountID")->unsigned();
            $table->integer("code")->unsigned();
            $table->boolean("completed");
            $table->boolean("pending")->default(true);
            $table->boolean("expired");

            $table->foreign("accountID")->references("id")->on('accounts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop("deposits");
    }
}
