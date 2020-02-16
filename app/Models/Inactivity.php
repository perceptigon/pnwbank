<?php

namespace App\Models;

use App\Classes\Nation;
use App\Classes\PWClient;
use Illuminate\Database\Eloquent\Model;

class Inactivity extends Model
{
    protected $guarded = [];

    public $table = "inactivity";

    /**
     * Sets a nation to inactive mode
     *
     * @param int $nID
     * @param string $leader
     * @param PWClient $client
     */
    public static function setMemberInactive(int $nID, string $leader, PWClient $client)
    {
        $inactive = self::create([
            "nID" => $nID,
            "leader" => $leader,
            "isInactive" => true
        ]);

        $client->modifyMemberTaxBracket($nID, 203);

        $inactive->notifyMember($nID, $client);
    }

    /**
     * Notify the member that they are in inactive status
     *
     * @param int $nID
     * @param PWClient $client
     */
    protected function notifyMember(int $nID, PWClient $client)
    {
        $nation = new Nation($nID);

        $message = "Hi!\n\n Due to you being inactive, your nation has been moved to a 100% tax rate. In order for you to be moved back to the regular tax rate, please click [link=".url("nation/removeInactive/{$this->id}")."]here[/link].";

        $client->sendMessage($nation->leader, "Inactivity Notice", $message);
    }

    /**
     * Sets a member active again
     *
     * @param int $nID
     * @param PWClient $client
     */
    public function setMemberActive(PWClient $client)
    {
        $this->isInactive = false;
        $this->save();

        $client->modifyMemberTaxBracket($this->nID, 78);
    }

    /**
     * Removes nations that have been removed from BK or set to applicants
     */
    public function cleanUp()
    {
        $inactiveNations = $this->where("isInactive", true)->get();

        foreach ($inactiveNations as $nat)
        {
            try
            {
                $nation = new Nation($nat->nID);
            }
            catch (\Exception $e) // Nation doesn't exist
            {
                $this->isInactive = false;
                $this->save();
                continue;
            }

            if ($nation->alliancePosition == 1) // Nation is applicant
            {
                $this->isInactive = false;
                $this->save();
                continue;
            }

            if ($nation->aID != 4937) // Nation is not in BK
            {
                $this->isInactive = false;
                $this->save();
                continue;
            }
        }
    }

    /**
     * Checks by nation ID if a member is set to inactive
     *
     * @param int $nID
     * @return bool
     */
    public static function isMemberInactive(int $nID) : bool
    {
        $inactive = self::where("nID", $nID)->where("isInactive", true)->get();

        if ($inactive->count() > 0)
            return true;
        else
            return false;
    }
}
