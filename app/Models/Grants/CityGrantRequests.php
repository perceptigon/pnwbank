<?php

namespace App\Models\Grants;

use App\Jobs\SendMoney;
use App\Models\Profile;
use App\Classes\PWClient;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\DispatchesJobs;

class CityGrantRequests extends Model
{
    use DispatchesJobs;

    protected $table = "citygrantrequests";
    public $timestamps = false;

    /**
     * Add a pending city grant request.
     *
     * @param \App\Classes\Nation $nation
     * @param int $cityNum
     * @param int $amount
     * @return CityGrantRequests
     */
    public static function addPendGrant(\App\Classes\Nation $nation, int $cityNum, int $amount) : CityGrantRequests
    {
        $grant = new self();

        $grant->nationID = $nation->nID;
        $grant->nationName = $nation->nationName;
        $grant->leader = $nation->leader;
        $grant->cityNum = $cityNum;
        $grant->amount = $amount;

        $grant->save();

        return $grant; // Might as well return it. I need it for the manual grant anyway
    }

    /**
     * Get all pending city grant requests.
     *
     * @return Collection
     */
    public static function getPendingGrants() : Collection
    {
        return self::where("isPending", true)->get();
    }

    /**
     * Count all pending city grant requests.
     *
     * @return null|int
     */
    public static function countPendReqs()
    {
        $num = self::where("isPending", true)->count();

        return $num > 0 ? $num : null;
    }

    /**
     * Get information of a request by it's ID.
     *
     * @param int $gID
     * @return CityGrantRequests
     */
    public static function getReqInfo(int $gID) : CityGrantRequests
    {
        return self::where("id", $gID)->firstOrFail();
    }

    /**
     * Approve a city grant request.
     *
     * @param int $gID
     * @return bool
     * @throws \Exception
     */
    public static function approveGrant(int $gID) : bool
    {
        $grant = self::getReqInfo($gID);
        if ($grant->isSent === true)
            throw new \Exception("That grant has already been sent");
        if (! self::sendGrant($grant))
            return false;

        // Update grant info
        $grant->isPending = false;
        $grant->isSent = true;
        $grant->save();

        // Update profile
        $profile = \App\Models\Profile::getProfile($grant->nationID);
        $profile->lastGrant = $grant->cityNum;
        $profile->lastGrantDate = date("Y-m-d");
        $profile->grantPending = false;
        $profile->save();

        \App\Models\Grants\GrantHistory::createCityLog($grant);

        \App\Models\Log::createLog("cityGrant", "Approved Grant {$grant->id}");

        return true;
    }

    /**
     * Send the money for a city grant.
     *
     * @param CityGrantRequests $grant
     * @return bool
     * @throws \Exception
     */
    public static function sendGrant(CityGrantRequests $grant) : bool
    {
        $bank = new \App\Classes\PWBank();
        $bank->recipient = $grant->nationName;
        $bank->money = $grant->amount;
        $bank->note = "City Grant {$grant->cityNum}";
        if (! $bank->checkIfFundsAvailable())
            return false;

        $message = "Hi {$grant->leader}, \n \n Your request for city grant {$grant->cityNum} has been approved. $".number_format($grant->amount)." has been sent to you. You should buy the city ASAP.";

        dispatch(new SendMoney($bank, $grant->leader, "Your City Grant has been Approved!", $message));

        return true;
    }

    /**
     * Deny a city grant request.
     *
     * @param int $gID
     * @throws \Exception
     */
    public static function denyGrant(int $gID)
    {
        $grant = self::getReqInfo($gID);
        $grant->isPending = false;
        $grant->isDenied = true;
        $grant->save();

        // Update profile to set pending city grant off
        $profile = Profile::getProfile($grant->nationID);
        $profile->grantPending = false;
        $profile->save();

        $client = new PWClient();
        $client->login();
        $message = "Hi {$grant->leader}, \n \n Your request for city grant {$grant->cityNum} has been denied.";
        $client->sendMessage($grant->leader, "Your Grant has been denied", $message);
        \App\Models\Log::createLog("cityGrant", "Denied grant - {$grant->id}");
    }

    /**
     * Counts how much we've sent them in city grants.
     *
     * @param int $nID
     * @return int
     */
    public static function getTotalMemberSent(int $nID) : int
    {
        return self::where("isSent", true)->where("nationID", $nID)->sum("amount");
    }

    /**
     * Get the last 5 city grants sent to the member.
     *
     * @param int $nID
     * @return Collection
     */
    public static function getLastFiveGrants(int $nID) : Collection
    {
        return self::where("nationID", $nID)
            ->orderBy("timestamp", "desc")
            ->take(5)
            ->get();
    }
}
