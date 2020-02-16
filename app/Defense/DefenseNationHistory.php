<?php

namespace App\Defense;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class DefenseNationHistory extends Model
{
    protected $connection = "defense";
    protected $table = "nationhistory";
    public $timestamps = false;
    protected $guarded = [];

    /**
     * Return a nation's 30 day history.
     *
     * @param int $nID
     * @return Collection
     */
    public static function getNation30DayHistory(int $nID) : Collection
    {
        $date = \Carbon\Carbon::now()->subMonth()->toDateString();

        return self::groupBy(\DB::raw('DATE(`date`)'))
            ->groupBy("leader")
            ->having("nID", "=", $nID)
            ->having("date", ">", $date)
            ->get();
    }

    /**
     * Get the history for a member
     *
     * @param int $nID
     * @return Collection
     */
    public static function getAllMemberHistory(int $nID) : Collection
    {
        return self::where("nID", $nID)->get();
    }

    /**
     * Get the history of the total amount of cities for 30 days.
     *
     * @return Collection
     */
    public static function getCity30DayHistory() : Collection
    {
        $date = \Carbon\Carbon::now()->subMonth()->toDateString();

        return self::select(\DB::raw("sum(cities) as amount, `date` as date"))
            ->groupBy(\DB::raw('DATE(`date`)'))
            ->where("date", ">", $date)
            ->get();
    }
}
