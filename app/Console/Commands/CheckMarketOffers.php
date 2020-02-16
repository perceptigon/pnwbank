<?php

namespace App\Console\Commands;

use App\Classes\PWClient;
use App\Models\MarketDeals;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

class CheckMarketOffers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'market:checkOffers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for offers from the alliance market';

    protected $client;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->client = new PWClient();
        $this->client->login();

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        echo "Running market \n";
        $offers = MarketDeals::getPendingOffers();
        $this->expireDeals($offers);
        // Get offers again after expiring shit
        $offers = MarketDeals::getPendingOffers();
        //PWFunctions::login();
        $content = new \simple_html_dom($this->client->getPage("https://politicsandwar.com/alliance/id=4937&display=bank"));

        for ($xpath = 2; $xpath < 52; $xpath++) {
            // Get Note
            foreach ($content->find('//*[@id="scrollme"]/table/tbody/tr['.$xpath.']/td[2]/img') as $x) {
                $x = $x->title;
                echo "$x \n";
                $note = preg_replace("/[^0-9]/", "", $x);
                // Get timestamp
                foreach ($content->find('//*[@id="scrollme"]/table/tbody/tr['.$xpath.']/td[2]/text()') as $y) {
                    $then = $y->plaintext;
                }
                $timestamp = strtotime($then);
                // Get three hours in the future. Four to convert to GMT (no daylight saving) and one hour before it. (PW doesn't observe DLS)
                // Change to +10,800 during daylight saving
                // Change to +14,400 during standard time
                // We only want to check the results that are from the last hour
                // This shit determines if it's daylight saving or not
                $dt = new \DateTime();
                if ($dt->format("I") == 1)
                    $add = 10800;
                else
                    $add = 14400;
                $now = time() + $add;
                echo "Now - $now <br>";
                echo "Then - $timestamp <br>";
                if ($now < $timestamp) {
                    echo "Passed timestamp <br>";
                    foreach ($offers as $offer) {
                        echo "Code - $offer->code \n";
                        echo "Note - $note \n";
                        if ($note == $offer->code) {
                            // Because the offer expires after an hour, check to see if it has been an hour. If so, deny the offer
                            // Now you need to figure out which resource it was
                            $resX = self::getResX($offer->resource);
                            echo "$resX \n";
                            echo "switch \n";
                            foreach ($content->find('//*[@id="scrollme"]/table/tbody/tr['.$xpath.']/td['.$resX.']') as $m) {
                                $noDot = substr($m, 0, strpos($m, "."));
                                $value = preg_replace("/[^0-9]/", "", $noDot);
                                echo "$value - Value \n";
                                echo "$offer->code Paid \n";
                                $offer->markPaid($value, $this->client);
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * Expires loans that are older than a couple hours.
     *
     * @param Collection $offers
     */
    private function expireDeals(Collection $offers)
    {
        foreach ($offers as $offer)
        {
            $expire = time() - 7200;
            $dealTimestamp = strtotime($offer->timestamp);
            if ($dealTimestamp < $expire)
            {
                echo "$offer->code expired \n";
                $offer->expireDeal($this->client);
            }
        }
    }

    private function getResX(string $resource) : int
    {
        switch ($resource) {
            case "food":
                $resX = 7;
                break;
            case "coal":
                $resX = 8;
                break;
            case "oil":
                $resX = 9;
                break;
            case "uranium":
                $resX = 10;
                break;
            case "lead":
                $resX = 11;
                break;
            case "iron":
                $resX = 12;
                break;
            case "bauxite":
                $resX = 13;
                break;
            case "gasoline":
                $resX = 14;
                break;
            case "munitions":
                $resX = 15;
                break;
            case "steel":
                $resX = 16;
                break;
            case "aluminum":
                $resX = 17;
                break;
            default:
                throw new \Exception("Error: Could not determine resource x path");
        }

        return $resX;
    }
}
