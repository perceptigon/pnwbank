<?php

namespace App\Models\Grants;

use App\Classes\PWBank;
use App\Jobs\SendMoney;
use App\Models\Log;
use App\Models\Profile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Collection;


class NukeGrants extends Model
{
    use DispatchesJobs;

    protected $table = "nukesgrant";

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
        $bank->money = 12250000;
        $bank->aluminum = 5250;
        $bank->gasoline = 2500;
        $bank->uranium = 1750;
        $bank->note = "Nukes Grant";

        $message = "Hi $grant->leader, \n \n Your Nukes grant has been sent to you. Thank you!";

        dispatch(new SendMoney($bank, $grant->leader, "Your Nukes Grant has been Approved!", $message));

        $grant->isPending = false;
        $grant->isSent = true;
        $grant->save();

        $profile = Profile::getProfile($grant->nID);
        $profile->pendingnukesGrant = false;
        $profile->save();

        Log::createLog("nukes", "Approved - $grant->id");
    }

    public static function denyGrant(int $gID)
    {
        $grant = self::getRequest($gID);
        $grant->isPending = false;
        $grant->save();

        $profile = Profile::getProfile($grant->nID);
        $profile->pendingnukesGrant = false;
        $profile->save();

        Log::createLog("nukes", "Denied - $grant->id");
        // TODO send them a message
    }
}
