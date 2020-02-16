<?php

namespace App\Models\Grants;

use App\Classes\PWBank;
use App\Jobs\SendMoney;
use App\Models\Log;
use App\Models\Profile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Collection;


class OilGrant extends Model
{
    use DispatchesJobs;

    protected $table = "oilgrants";

    public static function getPendReqs() : Collection
    {
        return self::where("isPending", true)->get();
    }

    public static function countPendReqs()
    {
        $num = self::where("isPending", true)->count();

        return $num > 0 ? $num : null;
    }

    public static function checkIfPending(int $nID) : bool
    {
        if (self::where("nID", $nID)->where("isPending", true)->exists())
            return true;

        return false;
    }

    public static function getRequest(int $gID) : self
    {
        return self::where("id", $gID)->firstOrFail();
    }

    public static function acceptGrant(int $gID)
    {
        $grant = self::getRequest($gID);

        $bank = new PWBank();
        $bank->recipient = $grant->nationName;
        $bank->oil = 5000;
        $bank->note = "Oil Grant";

        $message = "Hi $grant->leader, \n \n Your oil grant has been sent to you. Thank you!";

        dispatch(new SendMoney($bank, $grant->leader, "Your Oil Grant has been Approved!", $message));

        $grant->isPending = false;
        $grant->isSent = true;
        $grant->save();

        $profile = Profile::getProfile($grant->nID);
        $profile->pendingOilGrant = false;
        $profile->save();

        Log::createLog("oil", "Approved - $grant->id");
    }

    public static function denyGrant(int $gID)
    {
        $grant = self::getRequest($gID);
        $grant->isPending = false;
        $grant->save();

        $profile = Profile::getProfile($grant->nID);
        $profile->pendingOilGrant = false;
        $profile->save();

        Log::createLog("oil", "Denied - $grant->id");
        // TODO send them a message
    }
}
