<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateToV3 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Make changes to users table
        Schema::table("users", function ($table) {
            if (! Schema::hasColumn('users', 'remember_token')) {
                $table->rememberToken();
            }
            if (! Schema::hasColumn('users', 'created_at') && ! Schema::hasColumn('users', 'updated_at')) {
                $table->timestamps();
            }
            $table->renameColumn("level", "isAdmin");
            $table->string("permissions")->default('a:7:{s:5:"loans";s:2:"no";s:6:"grants";s:2:"no";s:6:"market";s:2:"no";s:8:"settings";s:2:"no";s:2:"so";s:2:"no";s:5:"users";s:2:"no";s:5:"taxes";s:2:"no";}')->change();
        });

        // Settings
        if (! Schema::hasTable("settings"))
        {
            Schema::create("settings", function (Blueprint $table) {
                $table->increments("id");
                $table->string("sKey");
                $table->integer("value");
            });
        }

        // Rename grants table
        Schema::rename("grants", "citygrantrequests");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop("settings");
        Schema::rename("citygrantrequests", "grants");
        Schema::table("users", function ($table) {
            $table->dropColumn(["remember_token", "created_at", "updated_at"]);
            $table->renameColumn("isAdmin", "level");
        });
    }
}
