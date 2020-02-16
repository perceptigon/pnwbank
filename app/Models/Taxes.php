<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class Taxes extends Model
{
    public $timestamps = false;

    protected $fillable = [
        "nID", "money", "food", "coal", "oil", "uranium", "lead", "iron", "bauxite", "gas", "munitions", "steel", "aluminum",
    ];
    // Custom properties for tax shit
    /**
     * An array of dates (yyyy-mm-dd) of the last 30 days.
     *
     * @var array
     */
    public $dates = [];

    /**
     * Contains the amount of money gotten in taxes.
     *
     * @var array
     */
    public $taxMoney = [];

    /**
     * Contains the amount of steel gotten in taxes.
     *
     * @var array
     */
    public $taxSteel = [];

    /**
     * Contains the amount of gas gotten in taxes.
     *
     * @var array
     */
    public $taxGas = [];

    /**
     * Contains the amount of munitions gotten in taxes.
     *
     * @var array
     */
    public $taxMunitions = [];

    /**
     * Contains the amount of aluminum gotten in taxes.
     *
     * @var array
     */
    public $taxAluminum = [];

    public $taxCoal = [];

    public $taxOil = [];

    public $taxUranium = [];

    public $taxLead = [];

    public $taxIron = [];

    public $taxBauxite = [];

    /**
     * Gets the tax history for 30 days.
     */
    public function getTaxHistory() // This doesn't use the tax database at all, BUUUUT because it has to do with taxes we'll put it here
    {
        $monthAgo = \Carbon\Carbon::now()->subMonth()->toDateString();
        $query = \App\Models\Stats::where("date", ">", $monthAgo)->orderBy("date")->get();
        $types = ["taxMoney", "taxGas", "taxMunitions", "taxSteel", "taxAluminum", "taxCoal", "taxOil", "taxUranium", "taxLead", "taxIron", "taxBauxite"];
        foreach ($query as $q)
        {
            if (in_array($q->type, $types))
            {
                array_push($this->{$q->type}, $q);
                $this->{$q->type."Total"} += $q->value;
            }
        }
    }

    /**
     * Counts how much from taxes we've collected from that nation.
     *
     * @param int $nID
     * @return int
     */
    public static function totalMemberTaxed(int $nID) : float
    {
        return self::where("nID", $nID)->sum("money");
    }

    public static function getMemberTaxHistory(int $nID) : Collection
    {
        $date = \Carbon\Carbon::now()->subMonth()->toDateString();

        return self::select(\DB::raw("sum(money) as amount, `timestamp` as date"))
            ->groupBy(\DB::raw('DATE(`timestamp`)'))
            ->where("nID", $nID)
            ->where("timestamp", ">", $date)
            ->get();
    }

    /**
     * Get all the tax history for this nation
     *
     * @param int $nID
     * @return Collection
     */
    public static function getAllMemberTaxHistory(int $nID) : Collection
    {
        return self::select(\DB::raw("sum(money) as money, `timestamp` as timestamp"))
            ->groupBy(\DB::raw('DATE(`timestamp`)'))
            ->where("nID", $nID)
            ->get();
    }

    /**
     * Gets every tax record for a nation. Basically, the tax record for every fucking turn ever.
     *
     * @param int $nID
     * @return mixed
     */
    public static function getLiterallyEverythingHolyFuckForNation(int $nID)
    {
        return self::where("nID", $nID)->get();
    }
}
