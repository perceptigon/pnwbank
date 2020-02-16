<?php

namespace App\Models\Grants;

use Illuminate\Database\Eloquent\Model;

class GrantHistory extends Model
{
    public $timestamps = false;
    protected $table = "granthistory";

    /**
     * Create a log of the city grant.
     *
     * @param CityGrantRequests $grant
     */
    public static function createCityLog(CityGrantRequests $grant)
    {
        $log = new self;
        $log->nationID = $grant->nationID;
        $log->leader = $grant->leader;
        $log->grantNum = $grant->cityNum;
        $log->amount = $grant->amount;
        $log->save();
    }
}
