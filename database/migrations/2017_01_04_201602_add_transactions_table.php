<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("transactions", function(Blueprint $table) {
            $table->increments("id");
            $table->timestamps();
            $table->integer("fromAccountID")->unsigned()->comment("Null if this is not from an account")->nullable();
            $table->integer("toAccountID")->unsigned()->comment("Null if this is not to an account")->nullable();
            $table->boolean("fromAccount")->comment("Is this transaction sent from an account?");
            $table->boolean("toAccount")->comment("Is this transaction sent to an account?");
            $table->string("fromName", 30)->comment("If this is not from an account, who is it from?")->nullable();
            $table->string("toName", 30)->comment("If this is not to an account, who is it to?")->nullable();
            $table->decimal("money", 10, 2)->unsigned();
            $table->decimal("coal", 10, 2)->unsigned();
            $table->decimal("oil", 10, 2)->unsigned();
            $table->decimal("uranium", 10, 2)->unsigned();
            $table->decimal("iron", 10, 2)->unsigned();
            $table->decimal("bauxite", 10, 2)->unsigned();
            $table->decimal("gas", 10, 2)->unsigned();
            $table->decimal("munitions", 10, 2)->unsigned();
            $table->decimal("steel", 10, 2)->unsigned();
            $table->decimal("aluminum", 10, 2)->unsigned();
            $table->decimal("food", 10, 2)->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop("transactions");
    }
}
