<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MMR extends Model
{
    public $table = "mmr";

    /**
     * Gets the MMR tier for a city. If there is no tier, throw an exception
     *
     * @param int $cityNum
     * @return mixed
     * @throws \Exception
     */
    public static function getCityMMR(int $cityNum)
    {
        $tier = self::where("cityNum", $cityNum)->first();

        if (is_null($tier))
            throw new \Exception("No MMR tier for city #".$cityNum);

        return $tier;
    }

    /**
     * Checks if a tier exists for a city number
     *
     * @param int $cityNum
     * @return bool
     */
    public static function checkIfTierExists(int $cityNum) : bool
    {
        $tier = self::where("cityNum", $cityNum)->first();

        if (is_null($tier))
            return false;

        return true;
    }
}
