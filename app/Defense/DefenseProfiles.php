<?php

namespace App\Defense;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DefenseProfiles extends Model
{
    protected $connection = "defense";
    protected $table = "profiles";
    public $timestamps = false;

    public static function getProfile(int $nID)
    {
        try
        {
            $profile = self::where("nID", $nID)->firstOrFail();
        }
        catch (ModelNotFoundException $e)
        {
            $profile = self::createProfile($nID);
        }

        return $profile;
    }

    /**
     * Creates a defense profile
     *
     * @param int $nID
     * @return DefenseProfiles
     */
    public static function createProfile(int $nID)
    {
        $profile = new self;
        $profile->nID = $nID;
        $profile->save();

        return $profile;
    }

    public function signIn()
    {

    }
}
