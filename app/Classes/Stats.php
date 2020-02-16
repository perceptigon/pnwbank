<?php

namespace App\Classes;

use App\Models\Loans;
use App\Models\Taxes;
use App\Models\MarketDeals;
use App\Models\Grants\IDGrants;
use App\Models\Grants\EntranceAid;
use App\Models\Grants\ActivityGrant;
use App\Models\Grants\CityGrantRequests;
use DateInterval;
use DateTime;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class Stats
{
    // Bank Stuff

    /**
     * Array that holds the money values and dates.
     *
     * @var array
     */
    public $money = [];

    /**
     * Array that holds the food values and dates.
     *
     * @var array
     */
    public $food = [];

    /**
     * Array that holds the coal values and dates.
     *
     * @var array
     */
    public $coal = [];

    /**
     * Array that holds the oil values and dates.
     *
     * @var array
     */
    public $oil = [];

    /**
     * Array that holds the uranium values and dates.
     *
     * @var array
     */
    public $uranium = [];

    /**
     * Array that holds the lead values and dates.
     *
     * @var array
     */
    public $lead = [];

    /**
     * Array that holds the iron values and dates.
     *
     * @var array
     */
    public $iron = [];

    /**
     * Array that holds the bauxite values and dates.
     *
     * @var array
     */
    public $bauxite = [];

    /**
     * Array that holds the gas values and dates.
     *
     * @var array
     */
    public $gas = [];

    /**
     * Array that holds the munitions values and dates.
     *
     * @var array
     */
    public $munitions = [];

    /**
     * Array that holds the steel values and dates.
     *
     * @var array
     */
    public $steel = [];

    /**
     * Array that holds the aluminum values and dates.
     *
     * @var array
     */
    public $aluminum = [];

    /**
     * Holds a collection of results from the stats database grouped by type and date.
     *
     * @var Collection
     */
    public $statsHistory;

    /**
     * Stores a date string from a month ago.
     *
     * @var string
     */
    public $monthAgo;

    /**
     * Stores the values and dates of the total loaned for 30 days.
     *
     * @var array
     */
    public $loanHistory = [];

    /**
     * Stores the values and dates of the total city grants sent for 30 days.
     *
     * @var array
     */
    public $grantHistory = [];

    /**
     * The total ever spent with everything.
     *
     * @var int
     */
    public $totalSpent = 0;

    // Loan Stuff

    /**
     * How much has been sent out in loans this month.
     *
     * @var int
     */
    public $loanMonthly = 0;

    /**
     * The total loaned all time.
     *
     * @var int
     */
    public $loanTotal = 0;

    /**
     * Current amount loaned.
     *
     * @var int
     */
    public $currentLoaned = 0;

    /**
     * How many loans have been approved.
     *
     * @var int
     */
    public $numLoans = 0;

    /**
     * How many loans have been denied.
     *
     * @var int
     */
    public $numdeniedLoans = 0;

    // City Grant Shit

    /**
     * How much we've spent on city grants this month.
     *
     * @var int
     */
    public $grantMonthly = 0;

    /**
     * The total money sent in city grants.
     *
     * @var int
     */
    public $totalCityGrantsSent = 0;

    /**
     * How many grants have been approved.
     *
     * @var int
     */
    public $cityGrantsApproved = 0;

    /**
     * How many grants have been denied.
     *
     * @var int
     */
    public $cityGrantsDenied = 0;

    // Market Stuff

    /**
     * How much money spent on market this month.
     *
     * @var int
     */
    public $marketMonthly = 0;

    /**
     * How much money has been spent in total on the market.
     *
     * @var int
     */
    public $marketTotal = 0;

    /**
     * How many transactions there's been.
     *
     * @var int
     */
    public $marketTransations = 0;

    /**
     * How many units of resources we've bought.
     *
     * @var int
     */
    public $unitsBought = 0;

    /**
     * What the most popular resource is.
     *
     * @var string
     */
    public $popularResource;

    // Tax Stuff

    /**
     * How much we've gotten in taxes all time.
     *
     * @var float
     */
    public $totalTax = 0.0;

    /**
     * How much we've gotten in taxes this week.
     *
     * @var float
     */
    public $weeklyTax = 0.0;

    // Entrance/ID stuff

    /**
     * How much we've spent on entrance aid this month.
     *
     * @var int
     */
    public $entranceMonthly = 0;

    /**
     * How much we've spent on activity grants this month.
     *
     * @var int
     */
    public $activityMonthly = 0;

    /**
     * How much we've spent on iron dome grants this month.
     *
     * @var int
     */
    public $idMonthly = 0;

    /**
     * Stats constructor.
     */
    public function __construct()
    {
        $this->monthAgo = \Carbon\Carbon::now()->subMonth()->toDateString();
        $this->get30DayStats();
    }

    /**
     * Load the stats needed for the dashboard page.
     */
    public function dashboard()
    {
        $this->sortBankValues();
        $this->getLoanData();
        $this->getCityGrantData();
        $this->getActivityGrantData();
        $this->getEntranceAidData();
        $this->getMarketData();
        $this->getTaxData();
        $this->getTotalSpent();
        $this->getIDData();
    }

    /**
     * Load the stats needed for the loans page.
     */
    public function loanPage()
    {
        $this->getLoanData();
    }

    /**
     * Load the stats needed for the city grants page.
     */
    public function cityGrantsPage()
    {
        $this->getCityGrantData();
    }

    /**
     * Load the stats needed for the market page.
     */
    public function marketPage()
    {
        $this->getMarketData();
        $this->sortBankValues();
    }

    /**
     * Query the stats table and group results by date and type for the last 30 days.
     */
    protected function get30DayStats()
    {
        $this->statsHistory = \App\Models\Stats::groupBy(\DB::raw('DATE(`date`)'))
            ->groupBy("type")
            ->where("date", ">", $this->monthAgo)
            ->get();
    }

    /**
     * Sort the bank values and store them in their respective variables.
     */
    protected function sortBankValues()
    {
        // Setup array for bank types to be sorted here
        $types = ["aluminum", "bauxite", "coal", "food", "gas", "iron", "lead", "money", "munitions", "oil", "steel"];
        foreach ($this->statsHistory as $his)
        {
            if (in_array($his->type, $types))
                array_push($this->{$his->type}, $his);
        }
    }

    /**
     * Sort the loan and grant histories in their respective variables for the line charts.
     */
    protected function sortHistories()
    {
        // Store histories in their respective array
        foreach ($this->statsHistory as $his)
        {
            switch ($his->type)
            {
                case "totalLoaned":
                    array_push($this->loanHistory, $his);
                    break;
                case "totalGrants":
                    array_push($this->grantHistory, $his);
                    break;
                default:
                    break;
            }
        }
    }

    /**
     * Get data for loans and store them in variables.
     */
    protected function getLoanData()
    {
        // Get total loaned ever
        $this->loanTotal = Loans::where("isApproved", true)->sum("originalAmount");

        // Get loan 30 day history
        foreach ($this->statsHistory as $his)
        {
            if ($his->type === "totalLoaned")
                array_push($this->loanHistory, $his);
        }

        // Get how much is currently loaned
        $this->currentLoaned = Loans::where("isActive", true)->sum("amount");

        // Get how many loans we've approved
        $this->numLoans = Loans::where("isApproved", true)->count();

        // Get how many loans we've denied
        $this->numdeniedLoans = Loans::where("isDenied", true)->count();

        // Get amount denied this month
        $this->loandeniedMonthly = Loans::where("isDenied", true)->where("timestamp", ">", $this->monthAgo)->sum("originalAmount");

        // Get amount sent this month
        $this->loanMonthly = Loans::where("isApproved", true)->where("timestamp", ">", $this->monthAgo)->sum("originalAmount");
    }

    /**
     * Get data for city grants and store them.
     */
    protected function getCityGrantData()
    {
        // Get city grant history for 30 days
        foreach ($this->statsHistory as $his)
        {
            if ($his->type === "totalGrants")
                array_push($this->grantHistory, $his);
        }

        // Get the amount spect on city grants this month
        $this->grantMonthly = CityGrantRequests::where("isSent", true)->where("timestamp", ">", $this->monthAgo)->sum("amount");

        // Count how many grants have been approved
        $this->cityGrantsApproved = CityGrantRequests::where("isSent", true)->count();

        // Count how many grants denied
        $this->cityGrantsDenied = CityGrantRequests::where("isDenied", true)->count();

        // Get total sent in city grants ever
        $this->totalCityGrantsSent = CityGrantRequests::where("isSent", true)->sum("amount");
    }

    /**
     * Get data for activity grants and store them.
     */
    protected function getActivityGrantData()
    {
        // Get amount spent this month
        $this->activityMonthly = ActivityGrant::where("isSent", true)->where("timestamp", ">", $this->monthAgo)->sum("amount");
    }

    /**
     * Get data for entrance aid and store them.
     */
    protected function getEntranceAidData()
    {
        $this->entranceMonthly = EntranceAid::where("isSent", true)->where("timestamp", ">", $this->monthAgo)->sum("amount");
    }

    /**
     * Get stats for taxes and store them.
     */
    protected function getTaxData()
    {
        // Get weekly tax
        $weekAgo = \Carbon\Carbon::now()->subWeek()->toDateString();
        $this->weeklyTax = Taxes::where("timestamp", ">", $weekAgo)->sum("money");

        // Get total taxes all time
        $this->totalTax = Taxes::sum("money");
    }

    /**
     * Get stats for the market and store them.
     */
    protected function getMarketData()
    {
        // How much has been spent on the market this month
        $this->marketMonthly = MarketDeals::where("isPaid", true)->where("timestamp", ">", $this->monthAgo)->sum("cost");

        // How much has been spent on the market in total
        $this->marketTotal = MarketDeals::where("isPaid", true)->sum("cost");

        // How many transactions there's been
        $this->marketTransations = MarketDeals::count();

        // How many units of stuff have been bought
        $this->unitsBought = MarketDeals::where("isPaid", true)->sum("amount");

        // Determine the most popular resource
        $query = MarketDeals::where("isPaid", true)->groupBy("resource")->sum("amount");

        $query = \App\Models\MarketDeals::select(
            "resource",
            \DB::raw("SUM(amount) as total")
        )->where("isPaid", true)->groupBy("resource")->get();
        $max = 0;
        foreach ($query as $row) {
            // We're gonna determine which resource is the largest by setting up a variable and comparing each one to the max... confusing but yeah you'll see lol
            if ($row->total > $max) {
                $max = $row->total;
                $this->popularResource = $row->resource;
            }
        }
    }

    protected function getIDData()
    {
        $this->idMonthly = IDGrants::where("isSent", true)->where("timestamp", ">", $this->monthAgo)->sum("amount");
    }

    /**
     * Calculate the total amount we've sent.
     */
    protected function getTotalSpent()
    {
        // TODO include everything else
        $this->totalSpent += $this->totalCityGrantsSent;
        $this->totalSpent += $this->marketTotal;
        $this->totalSpent += $this->loanTotal;
    }

    /**
     * @deprecated 3.1.0 Will throw exception. Replaced with everything else in this class
     * @throws \Exception
     */
    public function getStats()
    {
        throw new \Exception("Outdated function");
    }

    public static function spreadsheetExport($days) : string
    {
        // headers
        $string = "Date,Taxes,City Grants,Activity Grants,ID Grants,Entrance Grants\n";

        // do some date stuff
        $dateBeginning = new DateTime('today');
        $dateEnd = new DateTime('today');
        $dateEnd->add(new DateInterval('PT23H59M59S'));

        $counter = 0;

        while ($counter < $days)
        {
            $dateBeginning->sub(new DateInterval('P1D'));
            $dateEnd->sub(new DateInterval('P1D'));

            $taxes = DB::table('taxes')
                ->select('money')
                ->where('timestamp', '>', $dateBeginning)
                ->where('timestamp', '<', $dateEnd)
                ->sum('money');

            $cg = DB::table('citygrantrequests')
                ->where('timestamp', '>', $dateBeginning)
                ->where('timestamp', '<', $dateEnd)
                ->where('isSent', true)
                ->sum('amount');

            $ag = DB::table('activitygrants')
                ->select('amount')
                ->where('timestamp', '>', $dateBeginning)
                ->where('timestamp', '<', $dateEnd)
                ->where('isSent', true)
                ->sum('amount');

            $idg = DB::table('idgrants')
                ->select('amount')
                ->where('timestamp', '>', $dateBeginning)
                ->where('timestamp', '<', $dateEnd)
                ->where('isSent', true)
                ->sum('amount');

            $eg = DB::table('entranceaid')
                ->select('amount')
                ->where('timestamp', '>', $dateBeginning)
                ->where('timestamp', '<', $dateEnd)
                ->where('isSent', true)
                ->sum('amount');

            $string .= date_format($dateBeginning, 'Y-m-d');
            $string .= ",$taxes,$cg,$ag,$idg,$eg\n";

            $counter++;
        }

        return $string;
    }
}
