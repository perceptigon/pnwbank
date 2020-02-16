<?php

namespace App\Models\Grants;

use App\Models\Log;
use App\Classes\PWBank;
use App\Jobs\SendMoney;
use App\Models\Profile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\DispatchesJobs;

class EntranceAid extends Model
{
    use DispatchesJobs;

    protected $table = "entranceaid";
    public $timestamps = false;

    /**
     * Get all pending entrance aid requests.
     *
     * @return Collection
     */
    public static function getPendReqs() : Collection
    {
        return self::where("isPending", true)->get();
    }

    /**
     * Count all pending EA requests.
     *
     * @return null|int
     */
    public static function countPendReqs()
    {
        $num = self::where("isPending", true)->count();

        return $num > 0 ? $num : null;
    }

    /**
     * Get an EA Grant by it's ID.
     *
     * @param int $gID
     * @return mixed
     */
    public static function getGrant(int $gID)
    {
        return self::where("id", $gID)->firstOrFail();
    }

    /**
     * Approve an EA Grant.
     *
     * @param int $gID
     * @throws \Exception
     */
    public static function approveGrant(int $gID)
    {
        $grant = self::getGrant($gID);

        $bank = new PWBank();
        $bank->recipient = $grant->nationName;
        $bank->money = $grant->amount;
        $bank->note = "Entrance Aid";

        $message = "Hi $grant->leader, \n \n Your entrance aid has been sent to you. Thank you!";

        dispatch(new SendMoney($bank, $grant->leader, "Your Entrance Aid has been Approved!", $message));

        // Update profile and entrance aid database
        $grant->isPending = false;
        $grant->isSent = true;
        $grant->save();

        $profile = Profile::getProfile($grant->nID);
        $profile->entAid = true;
        $profile->save();

        Log::createLog("entrance", "Approved - $grant->id");
    }

    /**
     * Deny an EA Grant.
     *
     * @param int $gID
     */
    public static function denyGrant(int $gID)
    {
        $grant = self::getGrant($gID);
        $grant->isPending = false;
        $grant->save();
        Log::createLog("entrance", "Denied - $grant->id");
    }

    /**
     * Checks if a member has a pending entrance aid requests.
     *
     * Returns true for yes, false for no
     *
     * @param int $nID
     * @return bool
     */
    public static function checkPendingReq(int $nID)
    {
        if (self::where("nID", $nID)->where("isPending", true)->exists())
            return true;

        return false;
    }
}
