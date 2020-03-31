<?php

namespace App\Console\Commands;

use App\Models\Taxes;
use App\Classes\PWClient;
use Illuminate\Console\Command;

class TaxCollect extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tax:collect';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Collect Tax Records';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $client = new PWClient();
        $client->login();
        $content = new \simple_html_dom($client->postData([
            "maximum" => 200,
            "minimum" => 0,
            "search" => "Go",
        ], "https://politicsandwar.com/alliance/id=7399&display=banktaxes", true));

        // Add two hours to get two hours behind PW. You should not have to change this for daylight savings.
        $time = time() + 7200;
        $total = 0;
        for ($row = 2; $row < 202; $row++) {
            // Reset the variables for each row
            $money = 0;
            $food = 0;
            $coal = 0;
            $oil = 0;
            $uranium = 0;
            $lead = 0;
            $iron = 0;
            $bauxite = 0;
            $gas = 0;
            $munitions = 0;
            $steel = 0;
            $aluminum = 0;

            // Let's check to see if the tax is today's
            $inputTime = $content->find('//*[@id="scrollme"]/table/tbody/tr['.$row.']/td[2]');
            $inputTime = $inputTime[0]->plaintext;
            $inputTime = strtotime($inputTime);
            // Check if the tax record is from the correct date
            if ($inputTime > $time) {
                // Let's get the nation first cuz it won't work in the loop
                $nation = $content->find('//*[@id="scrollme"]/table/tbody/tr['.$row.']/td[3]/a');
                // Grab the href of the anchor
                $tempString = $nation[0]->href;
                // Remove everything but the numbers in the href
                $tempID = intval(preg_replace('/[^0-9]+/', '', $tempString), 10);
                $nationID = $tempID;
                //echo "<br>$nationID<br>";
                // Now let's run a loop to get all of their values
                for ($col = 5; $col < 17; $col++) {
                    foreach ($content->find('//*[@id="scrollme"]/table/tbody/tr['.$row.']/td['.$col.']') as $x) {
                        // Let's remove everything but the numbers and then remove everything after the dot cuz gotta save it as an int
                        //$noDot = substr($x->plaintext, 0, strpos($x->plaintext, "."));
                        $value = preg_replace("/[^0-9.]/", "", $x->plaintext);
                        // Now let's determine which resource this is
                        switch ($col) {
                            case 5:
                                $res = 'money';
                                $money = $value;
                                break;
                            case 6:
                                $res = 'food';
                                $food = $value;
                                break;
                            case 7:
                                $res = 'coal';
                                $coal = $value;
                                break;
                            case 8:
                                $res = 'oil';
                                $oil = $value;
                                break;
                            case 9:
                                $res = 'uranium';
                                $uranium = $value;
                                break;
                            case 10:
                                $res = 'lead';
                                $lead = $value;
                                break;
                            case 11:
                                $res = 'iron';
                                $iron = $value;
                                break;
                            case 12:
                                $res = 'bauxite';
                                $bauxite = $value;
                                break;
                            case 13:
                                $res = 'gas';
                                $gas = $value;
                                break;
                            case 14:
                                $res = 'munitions';
                                $munitions = $value;
                                break;
                            case 15:
                                $res = 'steel';
                                $steel = $value;
                                break;
                            case 16:
                                $res = 'aluminum';
                                $aluminum = $value;
                                break;
                            default:
                                $res = 'error';
                        }

                        //echo "$res : $value <br>";
                        $total += $money;
                    }
                }
                Taxes::create([
                    "nID" => $nationID,
                    "money" => $money,
                    "food" => $food,
                    "coal" => $coal,
                    "oil" => $oil,
                    "uranium" => $uranium,
                    "lead" => $lead,
                    "iron" => $iron,
                    "bauxite" => $bauxite,
                    "gas" => $gas,
                    "munitions" => $munitions,
                    "steel" => $steel,
                    "aluminum" => $aluminum,
                ]);
            }
        }

        foreach ($content->find("td") as $x)
        {
            echo $x;
        }
    }
}
