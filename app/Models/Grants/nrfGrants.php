<?php

namespace App\Models\Grants;

use App\Models\Log;
use App\Classes\PWBank;
use App\Jobs\SendMoney;
use App\Models\Profile;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\DispatchesJobs;

class nrfGrants extends Model
{
    use DispatchesJobs;

    protected $table = "nrfgrants";

    /**
     * Return all pending nrf Grants.
     *
     * @return Collection
     */
    public static function getPendReqs() : Collection
    {
        return self::where("isPending", true)->get();
    }

    /**
     * Count all pending nrf Grants.
     *
     * @return null|int
     */
    public static function countPendReqs()
    {
        $num = self::where("isPending", true)->count();

        return $num > 0 ? $num : null;
    }

    /**
     * Return an nrf Grant request.
     *
     * @param int $gID
     * @return nrfGrants
     */
    public static function getRequest(int $gID) : self
    {
        return self::where("id", $gID)->firstOrFail();
    }

    /**
     * Accept an nrf Grant.
     *
     * @param int $gID
     * @throws \Exception
     */
    public static function acceptGrant(int $gID)
    {
        $grant = self::getRequest($gID);

        $bank = new PWBank();
        $bank->recipient = $grant->nationName;
        $bank->money = 50000000;
        $bank->steel = 5000;
        $bank->gasoline = 7500;
        $bank->note = "nrf Grant";

        $message = "Hi $grant->leader, \n \n Your nrf grant has been sent to you. Thank you!";

        dispatch(new SendMoney($bank, $grant->leader, "Your nrf Grant has been Approved!", $message));

        $grant->isPending = false;
        $grant->isSent = true;
        $grant->save();

        $profile = Profile::getProfile($grant->nID);
        $profile->gottennrfGrant = true;
        $profile->save();

        Log::createLog("nrf", "Approved - $grant->id");
    }

    /**
     * Deny an nrf Grant.
     *
     * @param int $gID
     */
    public static function denyGrant(int $gID)
    {
        $grant = self::getRequest($gID);
        $grant->isPending = false;
        $grant->save();
        Log::createLog("nrf", "Denied - $grant->id");
        // TODO send them a message
    }

    /**
     * Checks if a nation ID has a pending nrf Grant.
     *
     * @param int $nID
     * @return bool
     */
    public static function checkIfPending(int $nID) : bool
    {
        if (self::where("nID", $nID)->where("isPending", true)->exists())
            return true;

        return false;
    }
}
