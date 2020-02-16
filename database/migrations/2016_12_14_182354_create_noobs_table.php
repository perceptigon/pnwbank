<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNoobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('ia')->create('noobs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('nation_id');
            $table->string('nation_name');
            $table->string('nation_ruler');
            $table->integer('forum_id');
            $table->string('forum_name');
            $table->integer('forum_mask')->default(3);
            $table->string('notes')->default('');
            $table->integer('carebear_id')->nullable();
            $table->boolean('member')->default(false);
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
        Schema::dropIfExists('noobs');
    }
}
