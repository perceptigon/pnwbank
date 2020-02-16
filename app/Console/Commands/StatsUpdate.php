<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Loans;
use App\Models\Stats;
use App\Models\Taxes;
use App\Classes\PWClient;
use App\Models\MarketDeals;
use Illuminate\Console\Command;
use App\Models\Grants\CityGrantRequests;

class StatsUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stats:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the stats for the stuff';

    /**
     * Store today's date yyyy-mm-dd.
     *
     * @var string
     */
    protected $today;

    /**
     * Store the PWClient to login and send messages.
     *
     * @var PWClient
     */
    protected $client;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->today = date("Y-m-d");
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
        $this->getTotalLoansOut();
        $this->getTotalGrants();
        $this->totalSpentMarket();
        $this->getBankTotals();
        $this->taxYesterday();
    }

    /**
     * Function to get the total loans currently out.
     *
     * @return void
     */
    protected function getTotalLoansOut()
    {
        $sum = Loans::where("isActive", true)->sum("amount");

        Stats::create([
           "date" => $this->today,
            "type" => "totalLoaned",
            "value" => $sum,
        ]);
    }

    /**
     * Get the total amount of city grants ever sent.
     *
     * @return void
     */
    protected function getTotalGrants()
    {
        $sum = CityGrantRequests::where("isSent", true)->sum("amount");

        Stats::create([
            "date" => $this->today,
            "type" => "totalGrants",
            "value" => $sum,
        ]);
    }

    /**
     * Get the total amount of money spent on the market.
     *
     * @return void
     */
    protected function totalSpentMarket()
    {
        $sum = MarketDeals::where("isPaid", true)->sum("amount");

        Stats::create([
            "date" => $this->today,
            "type" => "totalMarket",
            "value" => $sum,
        ]);
    }

    /**
     * Get the total amount of money/resources in the in-game bank.
     *
     * @return void
     * @throws \Exception
     */
    protected function getBankTotals()
    {
        $resources = ["money", "food", "coal", "oil", "uranium", "lead", "iron", "bauxite", "munitions", "steel", "aluminum"];

        $json = \json_decode(file_get_contents("http://politicsandwar.com/api/alliance-bank/?allianceid=4937&key=".env("PW_API_KEY")));

        if ($json->success == false)
            throw new \Exception("Couldn't pull alliance bank API");

        $bank = $json->alliance_bank_contents;

        foreach ($resources as $resource)
        {
            Stats::create([
                    "date" => $this->today,
                    "type" => $resource,
                    "value" => $bank[0]->{$resource},
            ]);
        }

        // Gas is special because I store it as 'gas' and on the API is 'gasoline' lol i'm dumb
        Stats::create([
            "date" => $this->today,
            "type" => "gas",
            "value" => $bank[0]->gasoline,
        ]);
    }

    /**
     * Get the total amount of taxes paid yesterday.
     *
     * @return void
     */
    protected function taxYesterday()
    {
        $yesterday = Carbon::now()->subDay()->toDateString();
        $resources = ["Money", "Food", "Coal", "Oil", "Uranium", "Lead", "Iron", "Bauxite", "Gas", "Munitions", "Steel", "Aluminum"];
        $taxes = Taxes::select(\DB::raw('
            SUM(money) AS Money,
            SUM(food) as Food,
            SUM(coal) as Coal,
            SUM(oil) as Oil,
            SUM(uranium) as Uranium,
            SUM(lead) as Lead,
            SUM(iron) as Iron,
            SUM(bauxite) as Bauxite,
            SUM(gas) as Gas,
            SUM(munitions) as Munitions,
            SUM(steel) as Steel,
            SUM(aluminum) as Aluminum'
        ))
            ->where("timestamp", "LIKE", "$yesterday%")
            ->first();

        foreach ($resources as $resource)
        {
            Stats::create([
                "date" => $yesterday,
                "type" => "tax".$resource,
                "value" => $taxes->$resource,
            ]);
        }
    }
}
