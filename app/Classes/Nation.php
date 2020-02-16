<?php

namespace App\Classes;

use App\Exceptions\UserErrorException;
use App\Models\Log;

class Nation
{
    /**
     * Array of the nation's city IDs.
     *
     * @var array
     */
    public $cityIDs = [];

    /**
     * The timer for the nation's city/project.
     *
     * @var int
     */
    public $cityProjectTimer;

    /**
     * The nation's ID.
     *
     * @var int
     */
    public $nID;

    /**
     * The nation's name.
     *
     * @var string
     */
    public $nationName;

    /**
     * The nation's pre name. EX: "Republic of".
     *
     * @var string
     */
    public $preName;

    /**
     * Which continent the nation is on.
     *
     * @var string
     */
    public $continent;

    /**
     * The nation's social policy.
     *
     * @var string
     */
    public $socialPolicy;

    /**
     * The nation's color.
     *
     * @var string
     */
    public $color;

    /**
     * The amount of minutes the nation since last activity.
     *
     * @var int
     */
    public $minsInactive;

    /**
     * The nation's unique ID.
     *
     * @var string
     */
    public $uniqueID;

    /**
     * The nation's government.
     *
     * @var string
     */
    public $government;

    /**
     * The nation's domestic policy.
     *
     * @var string
     */
    public $domesticPolicy;

    /**
     * The nation's war policy.
     *
     * @var string
     */
    public $warPolicy;

    /**
     * When the nation was founded.
     *
     * Datetime Format: Y-m-d hh:ss:ii
     *
     * @var string
     */
    public $founded;

    /**
     * How old in days the nation is.
     *
     * @var string
     */
    public $age;

    /**
     * The nation's alliance name.
     *
     * @var string
     */
    public $alliance;

    /**
     * Alliance Position
     * 1 = Applicant
     * 2 = Member
     * 3 = Officer
     * 4 = Heir
     * 5 = Leader.
     * @var int
     */
    public $alliancePosition;

    /**
     * The nation's alliance ID.
     *
     * @var int
     */
    public $aID;

    /**
     * The URL to the nation's flag.
     *
     * @var string
     */
    public $flagURL;

    /**
     * The leader of the nation.
     *
     * @var string
     */
    public $leader;

    /**
     * The leader's title.
     *
     * EX: "Emperor"
     *
     * @var string
     */
    public $title;

    /**
     * The nation's economic policy.
     *
     * @var string
     */
    public $ecoPolicy;

    /**
     * The nation's approval rating.
     *
     * @var float
     */
    public $approvalRating;

    /**
     * The nation's rank based on their score compared to every nation.
     *
     * @var int
     */
    public $nationRank;

    /**
     * The string displayed when displaying the nation's rank.
     *
     * Format: {Nation's Rank} of {Total Nations} Nations
     *
     * @var string
     */
    public $nationRankString;

    /**
     * The total amount of nations in the game.
     *
     * @var int
     */
    public $totalNations;

    /**
     * The amount of cities the nation has.
     *
     * @var int
     */
    public $cities;

    /**
     * The nation's latitude.
     *
     * @var float
     */
    public $latitude;

    /**
     * The nation's longitude.
     *
     * @var float
     */
    public $longitude;

    /**
     * The nation's score.
     *
     * @var float
     */
    public $score;

    /**
     * The nation's population.
     *
     * @var int
     */
    public $population;

    /**
     * The GDP of the nation.
     *
     * @var float
     */
    public $gdp;

    /**
     * The total infra in a nation.
     *
     * @var float
     */
    public $infra;

    /**
     * How much land the nation has.
     *
     * @var int
     */
    public $land;

    /**
     * The nation's soldiers.
     *
     * @var int
     */
    public $soldiers;

    /**
     * The nation's soldiers lost.
     *
     * @var int
     */
    public $soldiersLost;

    /**
     * The amount of soldiers this nation has killed.
     *
     * @var int
     */
    public $soldiersKilled;

    /**
     * The nation's tanks.
     *
     * @var int
     */
    public $tanks;

    /**
     * The nation's tanks lost.
     *
     * @var int
     */
    public $tanksLost;

    /**
     * How many tanks this nation has killed.
     *
     * @var int
     */
    public $tanksKilled;

    /**
     * The nation's aircraft.
     *
     * @var int
     */
    public $aircraft;

    /**
     * The nation's aircraft lost.
     *
     * @var int
     */
    public $aircraftLost;

    /**
     * How many aircraft the nation has killed.
     *
     * @var int
     */
    public $aircraftKilled;

    /**
     * The nation's ships.
     *
     * @var int
     */
    public $ships;

    /**
     * The nation's ships lost.
     *
     * @var int
     */
    public $shipsLost;

    /**
     * The nation's ships killed.
     *
     * @var int
     */
    public $shipsKilled;

    /**
     * The many missiles the nation has.
     *
     * @var int
     */
    public $missiles;

    /**
     * The many missiles the nation has launched.
     *
     * @var int
     */
    public $missilesLaunched;

    /**
     * The many missiles the nation has eaten.
     *
     * @var int
     */
    public $missilesEaten;

    /**
     * The many nukes the nation has.
     *
     * @var int
     */
    public $nukes;

    /**
     * The many nukes the nation has launched.
     *
     * @var int
     */
    public $nukesLaunched;

    /**
     * The many nukes the nation has eaten.
     *
     * @var int
     */
    public $nukesEaten;

    /**
     * How much infra this nation has killed.
     *
     * @var int
     */
    public $infraDestroyed;

    /**
     * If the nation has the iron works project.
     *
     * @var bool
     */
    public $ironWorks;

    /**
     * If the nation has the bauxite works project.
     *
     * @var bool
     */
    public $bauxiteWorks;

    /**
     * If the nation has the arms stockpile project.
     *
     * @var bool
     */
    public $armsStockpile;

    /**
     * If the nation has the emergency gas reserve project.
     *
     * @var bool
     */
    public $emgGasReserve;

    /**
     * If the nation has the mass irrigation project.
     *
     * @var bool
     */
    public $massIrrigation;

    /**
     * If the nation has the international trade center project.
     *
     * @var bool
     */
    public $intTradeCenter;

    /**
     * If the nation has the missile launch project.
     *
     * @var bool
     */
    public $missilePad;

    /**
     * If the nation has the nuclear research facility project.
     *
     * @var bool
     */
    public $nuclearResFacility;

    /**
     * If the nation has the iron dome project.
     *
     * @var bool
     */
    public $ironDome;

    /**
     * If the nation has the vital defense system project.
     *
     * @var bool
     */
    public $vitalDefSys;

    /**
     * If the nation has the intelligence agency project.
     *
     * @var bool
     */
    public $intelAgency;

    /**
     * If the nation has the uranium enrichment project.
     *
     * @var bool
     */
    public $uraniumEnrichment;

    /**
     * If the nation has the propaganda bureau project.
     *
     * @var bool
     */
    public $propBureau;

    /**
     * If the nation has the central civil engineering project.
     *
     * @var bool
     */
    public $cenCivEng;

    /**
     * Whether the nation exists or not.
     *
     * @var bool
     */
    public $exists;

    /**
     * The nation's military score.
     *
     * @var int
     */
    public $milScore;

    /**
     * Nation constructor.
     *
     * Grabs the nation from the PW API and sets everything as a property of this object
     *
     * @param int $nID
     * @throws UserErrorException
     */
    public function __construct(int $nID)
    {
        $client = new PWClient();
        $json = \json_decode($client->getPage("https://politicsandwar.com/api/nation/id={$nID}&key=".env("PW_API_KEY")));

        if (isset($json->error))
        {
            $this->exists = false;
            throw new UserErrorException($json->error);
        }

        if (! isset($json->leadername)) // Checking if the format is kind of the same TODO better validation here
        {
            Log::createLog("system", "FATAL: Couldn't parse nation JSON");
            throw new UserErrorException("Couldn't parse JSON file");
        }
        // Now set everything. This gon be ugly
        foreach ($json->cityids as $cID)
            array_push($this->cityIDs, intval($cID)); // Convert the string of the ID to the actual int
        $this->cityProjectTimer = $json->cityprojecttimerturns;
        $this->nID = intval($json->nationid);
        $this->nationName = $json->name;
        $this->preName = $json->prename;
        $this->continent = $json->continent;
        $this->socialPolicy = $json->socialpolicy;
        $this->color = $json->color;
        $this->minsInactive = $json->minutessinceactive;
        $this->uniqueID = $json->uniqueid;
        $this->government = $json->government;
        $this->domesticPolicy = $json->domestic_policy;
        $this->warPolicy = $json->war_policy;
        $this->founded = $json->founded;
        $this->age = $json->daysold;
        $this->alliance = $json->alliance;
        $this->alliancePosition = intval($json->allianceposition);
        $this->aID = intval($json->allianceid);
        $this->flagURL = $json->flagurl;
        $this->leader = $json->leadername;
        $this->title = $json->title;
        $this->ecoPolicy = $json->ecopolicy;
        $this->approvalRating = floatval($json->approvalrating);
        $this->nationRank = intval($json->nationrank);
        $this->nationRankString = $json->nationrankstrings;
        $this->totalNations = $json->nrtotal;
        $this->cities = $json->cities;
        $this->latitude = floatval($json->latitude);
        $this->longitude = floatval($json->longitude);
        $this->score = floatval($json->score);
        $this->population = intval($json->population);
        $this->gdp = floatval($json->gdp);
        $this->infra = floatval($json->totalinfrastructure);
        $this->land = $json->landarea;
        $this->soldiers = intval($json->soldiers);
        $this->soldiersLost = intval($json->soldiercasualties);
        $this->soldiersKilled = intval($json->soldierskilled);
        $this->tanks = intval($json->tanks);
        $this->tanksLost = intval($json->tankcasualties);
        $this->tanksKilled = intval($json->tankskilled);
        $this->aircraft = intval($json->aircraft);
        $this->aircraftLost = intval($json->aircraftcasualties);
        $this->aircraftKilled = intval($json->aircraftkilled);
        $this->ships = intval($json->ships);
        $this->shipsLost = intval($json->shipcasualties);
        $this->shipsKilled = intval($json->shipskilled);
        $this->missiles = intval($json->missiles);
        $this->missilesLaunched = intval($json->missilelaunched);
        $this->missilesEaten = intval($json->missileseaten);
        $this->nukes = intval($json->nukes);
        $this->nukesLaunched = intval($json->nukeslaunched);
        $this->nukesEaten = intval($json->nukeseaten);
        $this->infraDestroyed = floatval($json->infdesttot);
        $this->ironWorks = boolval($json->ironworks);
        $this->bauxiteWorks = boolval($json->bauxiteworks);
        $this->armsStockpile = boolval($json->armsstockpile);
        $this->emgGasReserve = boolval($json->emgasreserve);
        $this->massIrrigation = boolval($json->massirrigation);
        $this->intTradeCenter = boolval($json->inttradecenter);
        $this->missilePad = boolval($json->missilelpad);
        $this->nuclearResFacility = boolval($json->nuclearresfac);
        $this->ironDome = boolval($json->irondome);
        $this->vitalDefSys = boolval($json->vitaldefsys);
        $this->intelAgency = boolval($json->intagncy);
        $this->uraniumEnrichment = boolval($json->uraniumenrich);
        $this->propBureau = boolval($json->propbureau);
        $this->cenCivEng = boolval($json->cenciveng);
        $this->exists = true;
    }

    /**
     * Calculates the military score for this nation.
     *
     * @return int
     */
    public function calcMilScore() : int
    {
        $this->milScore = 0;
        $this->milScore += $this->soldiers * 0.0005;
        $this->milScore += $this->tanks * 0.05;
        $this->milScore += $this->aircraft * 0.5;
        $this->milScore += $this->ships * 2;
        $this->milScore += $this->missiles * 5;
        $this->milScore += $this->nukes * 15;

        return $this->milScore;
    }

    /**
     * Static function to calculate military score in case you don't want to grab a nation object.
     *
     * @param int $soldiers
     * @param int $tanks
     * @param int $aircraft
     * @param int $ships
     * @param int $missiles
     * @param int $nukes
     * @return int
     */
    public static function staticCalcMilScore(int $soldiers, int $tanks, int $aircraft, int $ships, int $missiles, int $nukes) : int
    {
        $score = 0;
        $score += $soldiers * 0.0005;
        $score += $tanks * 0.05;
        $score += $aircraft * 0.5;
        $score += $ships * 2;
        $score += $missiles * 5;
        $score += $nukes * 15;

        return $score;
    }
}
