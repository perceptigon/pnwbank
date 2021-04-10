<?php
/**
 * Created by PhpStorm.
 * User: shane
 * Date: 8/6/17
 * Time: 2:14 PM
 */

namespace App\Classes;


class MMR
{
    public static function soldiers(int $cities) : int
    {
        if ($cities <= 10) $soldiers = 6000 * $cities;
        else $soldiers = 15000 * $cities;

        return $soldiers;
    }

    public static function tanks(int $cities) : int
    {
        if ($cities <= 10) $tanks = 250 * $cities;
        else $tanks = 1250 * $cities;

        return $tanks;
    }

    public static function planes(int $cities) : int
    {
        $planes = 75 * $cities;
        return $planes;
    }

    public static function spies(int $cities) : int
    {
        switch ($cities)
        {
            case 1:
                $planes = 25;
                break;
            case 2:
                $planes = 25;
                break;
            case 3:
                $planes = 25;
                break;
            case 4:
                $planes = 25;
                break;
            case 5:
                $planes = 25;
                break;
            case 6:
                $planes = 25;
                break;
            case 7:
                $planes = 25;
                break;
            case 8:
                $planes = 50;
                break;
            case 9:
                $planes = 50;
                break;
            case 10:
                $planes = 50;
                break;
            default:
                $planes = 60;
        }

        return $planes;
    }

    public static function ships(int $cities) : int
    {
        $ships = 15 * $cities;
        return $ships;
    }

    public static function missiles(int $cities) : int
    {
        $missiles = 0;
        return $missiles;
    }

    public static function nukes(int $cities) : int
    {
        $nukes = 0;
        return $nukes;
    }
}
    