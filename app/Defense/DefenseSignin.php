<?php

namespace App\Defense;

use App\Classes\Nation;
use App\Classes\PWClient;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;


class DefenseSignin extends Model
{
    protected $connection = "defense";
    protected $table = "signins";
    public $timestamps = false;

    /**
     * Get a member's last 10 sign ins
     *
     * @param int $nID
     * @return Collection
     */
    public static function getLast10Signins(int $nID) : Collection
    {
        $result = self::where("nationID", $nID)
            ->orderBy("timestamp", "desc")
            ->take(10)
            ->get();

        return $result->reverse(); // Reverse the collection so it is in the right order
    }

    /**
     * Get sign ins for a nation for one year
     *
     * @param int $nID
     * @return Collection
     */
    public static function getLastYearSignins(int $nID) : Collection
    {
        $date = \Carbon\Carbon::now()->subYear()->toDateString();

        return self::where("nationID", $nID)
            ->where("timestamp", ">=", $date)
            ->orderBy("timestamp", "asc")
            ->get();
    }

    /**
     * Get all sign ins for the nation
     *
     * @param int $nID
     * @return Collection
     */
    public static function getAllSignInsForNation(int $nID) : Collection
    {
        return self::where("nationID", $nID)
            ->orderBy("timestamp", "asc")
            ->get();
    }

    public static function doSignIn(Request $request)
    {
        // Get nation
        $nation = new Nation($request->nID);
        // Get defense profile
        $profile = DefenseProfiles::getProfile($request->nID);

        if ($nation->aID != 7399)
            throw new \Exception("You are not in Rothschild Family");
        if ($nation->alliancePosition < 2)
            throw new \Exception("Applicants do not need to sign in");
        // Now to check the 24 hour rule
        if ($profile->lastSignIn != null) // If null, this is their first sign in
        {
            $cb = new \Carbon\Carbon($profile->lastSignIn);

            $hours = $cb->diffInHours(\Carbon\Carbon::now());

            if ($hours < 24)
            {
                $diff = $cb->addHours(24);
                throw new \Exception("You may only sign in once every 24 hours. Sign in again at {$diff->toDateTimeString()}");
            }

        }

        $signin = new self;
        $signin->leader = $nation->leader;
        $signin->nation = $nation->nationName;
        $signin->score = $nation->score;
        $signin->money = $request->money;
        $signin->steel = $request->steel;
        $signin->munitions = $request->munitions;
        $signin->gas = $request->gas;
        $signin->aluminum = $request->aluminum;
        $signin->nationID = $nation->nID;
        $signin->irc = $request->discord == "yes" ? 1 : 0;
        $signin->updateDays = \serialize($request->update);
        $signin->food = $request->food;
        $signin->uranium = $request->uranium;
        $signin->spies = $request->spies;
        $signin->save();

        // Update profile
        $profile->lastSignIn = Carbon::now();
        $profile->hasSignedIn = 1;
        $profile->inBK = 1;
        $profile->save();

        // Update nations
        $defNation = DefenseNations::getNation($nation->nID);
        $defNation->leader = $nation->leader;
        $defNation->nation = $nation->nationName;
        $defNation->score = $nation->score;
        $defNation->cities = $nation->cities;
        $defNation->soldiers = $nation->soldiers;
        $defNation->tanks = $nation->tanks;
        $defNation->planes = $nation->aircraft;
        $defNation->missiles = $nation->missiles;
        $defNation->nukes = $nation->nukes;
        $defNation->ships = $nation->ships;
        $defNation->steel = $request->steel;
        $defNation->gas = $request->gas;
        $defNation->aluminum = $request->aluminum;
        $defNation->munitions = $request->munitions;
        $defNation->money = $request->money;
        $defNation->food = $request->food;
        $defNation->uranium = $request->uranium;
        $defNation->spies = $request->spies;
        $defNation->inBK = true;
        $defNation->hasSignedIn = 1;
        $defNation->save();

        return $signin;
    }
}
