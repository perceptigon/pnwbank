<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("accountLogs", function(Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer("accountID")->unsigned();
            $table->string("editor");
            $table->decimal("money", 10, 2);
            $table->decimal("coal", 10, 2);
            $table->decimal("lead", 10, 2);
            $table->decimal("oil", 10, 2);
            $table->decimal("uranium", 10, 2);
            $table->decimal("iron", 10, 2);
            $table->decimal("bauxite", 10, 2);
            $table->decimal("gas", 10, 2);
            $table->decimal("munitions", 10, 2);
            $table->decimal("steel", 10, 2);
            $table->decimal("aluminum", 10, 2);
            $table->decimal("food", 10, 2);

            $table->foreign("accountID")->references("id")->on("accounts");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop("accountLogs");
    }
}
