<?php

namespace App\Models\Grants;

use App\Models\Log;
use App\Classes\PWBank;
use App\Jobs\SendMoney;
use App\Models\Profile;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\DispatchesJobs;

class irondomeGrants extends Model
{
    use DispatchesJobs;

    protected $table = "irondomegrants";

    /**irondomeGrants
     * Return all pending Iron Dome Grants.
     *
     * @return Collection
     */
    public static function getPendReqs() : Collection
    {
        return self::where("isPending", true)->get();
    }

    /**
     * Count all pending ID Grants.
     *
     * @return null|int
     */
    public static function countPendReqs()
    {
        $num = self::where("isPending", true)->count();

        return $num > 0 ? $num : null;
    }

    /**
     * Return an Iron Dome Grant request.
     *
     * @param int $gID
     * @return IDGrants
     */
    public static function getRequest(int $gID) : self
    {
        return self::where("id", $gID)->firstOrFail();
    }

    /**
     * Accept an Iron Dome Grant.
     *
     * @param int $gID
     * @throws \Exception
     */
    public static function acceptGrant(int $gID)
    {
        $grant = self::getRequest($gID);

        $bank = new PWBank();
        $bank->recipient = $grant->nationName;
        $bank->money = 6000000;
        $bank->aluminum = 500;
        $bank->steel = 1250;
        $bank->gasoline = 500;
        $bank->note = "Iron Dome Grant";

        $message = "Hi $grant->leader, \n \n Your Iron Dome grant has been sent to you. Thank you!";

        dispatch(new SendMoney($bank, $grant->leader, "Your Iron Dome Grant has been Approved!", $message));

        $grant->isPending = false;
        $grant->isSent = true;
        $grant->save();

        $profile = Profile::getProfile($grant->nID);
        $profile->gottenirondomeGrant = true;
        $profile->save();

        Log::createLog("id", "Approved - $grant->id");
    }

    /**
     * Deny an ID Grant.
     *
     * @param int $gID
     */
    public static function denyGrant(int $gID)
    {
        $grant = self::getRequest($gID);
        $grant->isPending = false;
        $grant->save();
        Log::createLog("iron dome", "Denied - $grant->id");
        // TODO send them a message
    }

    /**
     * Checks if a nation ID has a pending ID Grant.
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
