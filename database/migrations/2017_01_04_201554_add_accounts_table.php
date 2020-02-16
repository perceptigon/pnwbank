<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("accounts", function(Blueprint $table) {
            $table->increments("id")->unsigned();
            $table->timestamps();
            $table->integer("nID");
            $table->string("name");
            $table->decimal("money", 12, 2)->unsigned();
            $table->decimal("coal", 12, 2)->unsigned();
            $table->decimal("oil", 12, 2)->unsigned();
            $table->decimal("uranium", 12, 2)->unsigned();
            $table->decimal("iron", 12, 2)->unsigned();
            $table->decimal("bauxite", 12, 2)->unsigned();
            $table->decimal("gas", 12, 2)->unsigned();
            $table->decimal("munitions", 12, 2)->unsigned();
            $table->decimal("steel", 12, 2)->unsigned();
            $table->decimal("aluminum", 12, 2)->unsigned();
            $table->decimal("food", 12, 2)->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop("accounts");
    }
}
