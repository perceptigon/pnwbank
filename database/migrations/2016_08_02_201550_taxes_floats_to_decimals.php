<?php

use Illuminate\Database\Migrations\Migration;

class TaxesFloatsToDecimals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('taxes', function ($table) {
            $table->decimal('money', 10, 2)->unsigned()->change();
            $table->decimal('food', 10, 2)->unsigned()->change();
            $table->decimal('coal', 10, 2)->unsigned()->change();
            $table->decimal('oil', 10, 2)->unsigned()->change();
            $table->decimal('uranium', 10, 2)->unsigned()->change();
            $table->decimal('lead', 10, 2)->unsigned()->change();
            $table->decimal('iron', 10, 2)->unsigned()->change();
            $table->decimal('bauxite', 10, 2)->unsigned()->change();
            $table->decimal('gas', 10, 2)->unsigned()->change();
            $table->decimal('munitions', 10, 2)->unsigned()->change();
            $table->decimal('steel', 10, 2)->unsigned()->change();
            $table->decimal('aluminum', 10, 2)->unsigned()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('taxes', function ($table) {
            $table->float('money', 10, 2)->change();
            $table->float('food', 10, 2)->change();
            $table->float('coal', 10, 2)->change();
            $table->float('oil', 10, 2)->change();
            $table->float('uranium', 10, 2)->change();
            $table->float('lead', 10, 2)->change();
            $table->float('iron', 10, 2)->change();
            $table->float('bauxite', 10, 2)->change();
            $table->float('gas', 10, 2)->change();
            $table->float('munitions', 10, 2)->change();
            $table->float('steel', 10, 2)->change();
            $table->float('aluminum', 10, 2)->change();
        });
    }
}
