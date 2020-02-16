<?php

namespace App\Models\Grants;

use App\Models\Log;
use App\Classes\PWBank;
use App\Jobs\SendMoney;
use App\Models\Profile;
use App\Classes\ForumProfile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\DispatchesJobs;

class ActivityGrant extends Model
{
    use DispatchesJobs;

    protected $table = "activitygrants";
    public $timestamps = false;

    /**
     * Store the member's forum profile.
     *
     * @var ForumProfile
     */
    protected $profile;

    /**
     * Which grant/threshold they are getting.
     *
     * @var int
     */
    public $grant;

    /**
     * How much money they should get for their reward.
     *
     * @var int
     */
    public $reward;

    /**
     * Determine the next grant/threshold.
     *
     * @param ForumProfile $profile
     */
    public function determineNextGrant(ForumProfile $profile)
    {
        $this->profile = $profile;
        if ($this->profile->posts >= 20 && $this->profile->posts < 100) // Check for the 20 grant threshold
            $this->grant = 20;
        elseif ($this->profile->posts > 100)
            $this->grant = floor($this->profile->posts / 100) * 100;
        else
            throw new \Exception("You are not eligible for any activity grant.");
    }

    /**
     * Determine their reward for their grant/threshold.
     *
     * @throws \Exception
     */
    public function determineReward()
    {
        if ($this->grant === 20)
            $this->reward = 50000;
        elseif (($this->grant % 1000) === 0) // 1000 threshold
            $this->reward = 1000000;
        elseif (($this->grant % 100 === 0)) // For every 100 posts
            $this->reward = 500000;
        else
            throw new \Exception("Couldn't determine reward"); // This should never happen
    }

    /**
     * Get all pending activity grant requests.
     *
     * @return mixed
     */
    public static function getPendReqs()
    {
        return self::where("isPending", true)->get();
    }

    /**
     * Count all pending AG requests.
     *
     * @return null
     */
    public static function countPendReqs()
    {
        $num = self::where("isPending", true)->count();

        return $num > 0 ? $num : null;
    }

    /**
     * @param int $gID
     * @return mixed
     */
    public static function getRequest(int $gID)
    {
        return self::where("id", $gID)->firstOrFail();
    }

    /**
     * Approve an Activity Grant.
     *
     * @param int $gID
     * @throws \Exception
     */
    public static function approveGrant(int $gID)
    {
        $grant = self::getRequest($gID);

        $bank = new PWBank();
        $bank->recipient = $grant->nationName;
        $bank->money = $grant->amount;
        $bank->note = $grant->threshold." Activity Grant";

        $message = "Hi $grant->leader, \n \n Your activity grant for the threshold ".number_format($grant->threshold)." has been sent to you. Thank you!";

        dispatch(new SendMoney($bank, $grant->leader, "Your Activity Grant has been Approved!", $message));

        $grant->isPending = false;
        $grant->isSent = true;
        $grant->save();

        $profile = Profile::getProfile($grant->nID);
        $profile->pendingActivityGrant = false;
        $profile->lastActivityGrant = $grant->threshold;
        $profile->save();

        Log::createLog("activity", "Approved - $grant->id");
    }

    /**
     * Deny an activity grant.
     *
     * @param int $gID
     */
    public static function denyGrant(int $gID)
    {
        $grant = self::getRequest($gID);
        $grant->isPending = false;
        $grant->save();

        $profile = Profile::getProfile($grant->nID);
        $profile->pendingActivityGrant = false;
        $profile->save();
        // TODO send them a message
        Log::createLog("activity", "Denied - $grant->id");
    }

    /**
     * Counts the amount of money sent in activity grants to the nation.
     *
     * @param int $nID
     * @return int
     */
    public static function getTotalMemberSent(int $nID) : int
    {
        return self::where("isSent", true)->where("nID", $nID)->sum("amount");
    }

    /**
     * Get the last 5 activity grants sent to the member.
     *
     * @param int $nID
     * @return Collection
     */
    public static function getLastFiveGrants(int $nID) : Collection
    {
        return self::where("nID", $nID)
            ->orderBy("timestamp", "desc")
            ->take(5)
            ->get();
    }
}
