<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    public $timestamps = false;

    /**
     * Get a nation's profile. If they don't have one, create it.
     *
     * @param int $nID
     * @return Profile
     */
    public static function getProfile(int $nID) : Profile
    {
        try // Try to fetch their profile. If you can't, catch the exception and create one
        {
            $profile = \App\Models\Profile::where("nationID", "=", $nID)->firstOrFail();
        }
        catch (\Exception $ex)
        {
            $profile = new self();
            $profile->nationID = $nID;
            $profile->save();
        }

        return $profile;
    }

    /**
     * Reset the profile's city grant timer which would allow them to bypass the 10 day waiting period for another grant.
     *
     * @param int $nID
     */
    public static function resetCityGrantTimer(int $nID)
    {
        $profile = self::getProfile($nID);
        $profile->lastGrantDate = null;
        $profile->save();
    }

    /**
     * Reset the last grant that they've gotten. This would allow someone who get the same grants again.
     *
     * @param int $nID
     */
    public static function resetCityGrantNumber(int $nID)
    {
        $profile = self::getProfile($nID);
        $profile->lastGrant = 0;
        $profile->save();
    }
}
