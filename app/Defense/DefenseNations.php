<?php

namespace App\Defense;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class DefenseNations extends Model
{
    protected $connection = "defense";
    protected $table = "nations";
    public $timestamps = false;
    protected $guarded = [];

    protected $appends = ["mmr"];

    /**
     * Returns a breakdown of how many nations we have at number of cities.
     *
     * @return Collection
     */
    public static function getCityBreakdown() : Collection
    {
        return self::select(\DB::raw("cities, count(`cities`) as count"))
            ->groupBy("cities")
            ->where("inBK", true)
            ->get();
    }

    /**
     * Get all nations in Cam.
     *
     * @return Collection
     */
    public static function getBKNations() : Collection
    {
        return self::where("inBK", true)
            ->orderBy("score", "desc")
            ->get();
    }

    /**
     * Grabs a nation by it's nation ID.
     *
     * @param int $nID
     * @return DefenseNations
     */
    public static function getNation(int $nID) : self
    {
        try
        {
            return self::where("nID", $nID)->firstOrFail();
        }
        catch (\Exception $e)
        {
            // We don't have an entry for them, so we'll create one and send it back to be updated
            return new self;
        }
    }

    /**
     * Grabs all of the nations that haven't yet signed in.
     *
     * @return Collection
     */
    public static function getNationsHaventSignedIn() : Collection
    {
        return self::where("inBK", true)->where("hasSignedIn", false)->get();
    }

    public function getMMRAttribute()
    {
        return Warchest::mmrScoreFromDefNations($this);
    }

    /**
     * Gets nation within a score range.
     *
     * @param int $score
     * @return Collection
     */
    public static function getNationsInRange(int $score) : Collection
    {
        $maxScore = $score * 1.33;
        $lowScore = $score * (4 / 7);

        return self::whereBetween("score", [$lowScore, $maxScore])
            ->where("inBK", true)
            ->get();
    }
}
