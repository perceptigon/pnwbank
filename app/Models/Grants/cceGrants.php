<?php

namespace App\Models\Grants;

use App\Models\Log;
use App\Classes\PWBank;
use App\Jobs\SendMoney;
use App\Models\Profile;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\DispatchesJobs;

class cceGrants extends Model
{
    use DispatchesJobs;

    protected $table = "ccegrants";

    /**
     * Return all pending cce Grants.
     *
     * @return Collection
     */
    public static function getPendReqs() : Collection
    {
        return self::where("isPending", true)->get();
    }

    /**
     * Count all pending cce Grants.
     *
     * @return null|int
     */
    public static function countPendReqs()
    {
        $num = self::where("isPending", true)->count();

        return $num > 0 ? $num : null;
    }

    /**
     * Return an cce Grant request.
     *
     * @param int $gID
     * @return cceGrants
     */
    public static function getRequest(int $gID) : self
    {
        return self::where("id", $gID)->firstOrFail();
    }

    /**
     * Accept an cce Grant.
     *
     * @param int $gID
     * @throws \Exception
     */
    public static function acceptGrant(int $gID)
    {
        $grant = self::getRequest($gID);

        $bank = new PWBank();
        $bank->recipient = $grant->nationName;
        $bank->money = 3000000;
        $bank->oil = 1000;
        $bank->iron = 1000;
        $bank->bauxite = 1000;
        $bank->note = "cce Grant";

        $message = "Hi $grant->leader, \n \n Your cce grant has been sent to you. Thank you!";

        dispatch(new SendMoney($bank, $grant->leader, "Your cce Grant has been Approved!", $message));

        $grant->isPending = false;
        $grant->isSent = true;
        $grant->save();

        $profile = Profile::getProfile($grant->nID);
        $profile->gottencceGrant = true;
        $profile->save();

        Log::createLog("cce", "Approved - $grant->id");
    }

    /**
     * Deny an cce Grant.
     *
     * @param int $gID
     */
    public static function denyGrant(int $gID)
    {
        $grant = self::getRequest($gID);
        $grant->isPending = false;
        $grant->save();
        Log::createLog("cce", "Denied - $grant->id");
        // TODO send them a message
    }

    /**
     * Checks if a nation ID has a pending cce Grant.
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
