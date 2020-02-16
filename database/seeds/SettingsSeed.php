<?php

use Illuminate\Database\Seeder;

class SettingsSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("settings")->insert([
            [
                "sKey" => "warMode",
                "value" => 0,
            ],
            [
                "sKey" => "devMode",
                "value" => 1,
            ],
            [
                "sKey" => "loanSystem",
                "value" => 0,
            ],
            [
                "sKey" => "maxLoan",
                "value" => 1,
            ],
            [
                "sKey" => "loanDuration",
                "value" => 1,
            ],
            [
                "sKey" => "cityGrantSystem",
                "value" => 0,
            ],
            [
                "sKey" => "allianceMarketSystem",
                "value" => 0,
            ],
            [
                "sKey" => "oilSystem",
                "value" => 0,
            ],
            [
                "sKey" => "entranceAidSystem",
                "value" => 0,
            ],
            [
                "sKey" => "entranceAidAmount",
                "value" => 1,
            ],
            [
                "sKey" => "activityGrantSystem",
                "value" => 0,
            ],
            [
                "sKey" => "idGrantSystem",
                "value" => 0,
            ],
            [
                "sKey" => "idGrantAmount",
                "value" => 1,
            ],
        ]);
    }
}
