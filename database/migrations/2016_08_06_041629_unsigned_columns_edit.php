<?php

use Illuminate\Database\Migrations\Migration;

class UnsignedColumnsEdit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('activitygrants', function ($table) {
            $table->integer('threshold')->unsigned()->change();
            $table->integer('amount')->unsigned()->change();
            $table->integer('nID')->unsigned()->change();
        });
        Schema::table('citygrantrequests', function ($table) {
            $table->integer('cityNum')->unsigned()->change();
            $table->integer('nationID')->unsigned()->change();
            $table->integer('amount')->unsigned()->change();
        });
        Schema::table('citygrants', function ($table) {
            $table->integer('grantNum')->unsigned()->change();
            $table->integer('amount')->unsigned()->change();
            $table->integer('infPerCity')->unsigned()->change();
            $table->integer('mmrScore')->unsigned()->change();
        });
        Schema::table('entranceaid', function ($table) {
            $table->integer('nID')->unsigned()->change();
            $table->integer('amount')->unsigned()->change();
        });
        Schema::table('granthistory', function ($table) {
            $table->integer('nationID')->unsigned()->change();
            $table->integer('amount')->unsigned()->change();
        });
        Schema::table('idgrants', function ($table) {
            $table->integer('nID')->unsigned()->change();
            $table->integer('amount')->unsigned()->change();
        });
        Schema::table('loans', function ($table) {
            $table->integer('code')->unsigned()->change();
            $table->integer('nationID')->unsigned()->change();
            $table->integer('amount')->unsigned()->change();
            $table->integer('originalamount')->unsigned()->change();
            $table->integer('score')->unsigned()->change();
            $table->integer('duration')->unsigned()->change();
        });
        Schema::table('market', function ($table) {
            $table->integer('amount')->unsigned()->change();
            $table->integer('ppu')->unsigned()->change();
        });
        Schema::table('marketdeals', function ($table) {
            $table->integer('code')->unsigned()->change();
            $table->integer('nationID')->unsigned()->change();
            $table->integer('amount')->unsigned()->change();
            $table->integer('ppu')->unsigned()->change();
            $table->integer('cost')->unsigned()->change();
        });
        Schema::table('profiles', function ($table) {
            $table->integer('nationID')->unsigned()->change();
            $table->integer('lastGrant')->unsigned()->change();
        });
        Schema::table('so', function ($table) {
            $table->integer('money')->unsigned()->change();
        });
        Schema::table('stats', function ($table) {
            $table->bigInteger('value')->unsigned()->change();
        });
        Schema::table('taxes', function ($table) {
            $table->bigInteger('nID')->unsigned()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Nothing :/
    }
}
