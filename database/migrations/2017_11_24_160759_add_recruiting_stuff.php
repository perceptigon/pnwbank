<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRecruitingStuff extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('recruiting')->table('status', function (Blueprint $table) {
            $table->text("value")->nullable();
        });


        // We're gonna add some data here. Idc if it's not the right practice. Fuck you.
        DB::connection("recruiting")->table("status")->insert([
            [
                "name" => "recruitTopic",
                "status" => null,
                "value" => "Recruiting Topic"
            ],
            [
                "name" => "recruitMessage",
                "status" => null,
                "value" => "Recruiting Message"
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('recruiting')->table('status', function (Blueprint $table) {
            $table->dropColumn("value");
        });
    }
}
