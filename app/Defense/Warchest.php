<?php

namespace App\Defense;

use App\Classes\Nation;
use App\Models\MMR;

class Warchest
{
    protected $nation; // Nation Object

    // Standards
    /**
     * The money they should have.
     *
     * @var int
     */
    public $moneyStandard;

    /**
     * The food they should have.
     *
     * @var int
     */
    public $foodStandard;

    /**
     * The gas they should have.
     *
     * @var int
     */
    public $gasStandard;

    /**
     * The munitions they should have.
     *
     * @var int
     */
    public $munitionsStandard;

    /**
     * The steel they should have.
     *
     * @var int
     */
    public $steelStandard;

    /**
     * The aluminum they should have.
     *
     * @var int
     */
    public $aluminumStandard;

    /**
     * The soldiers they should have.
     *
     * @var int
     */
    public $soldiersStandard;

    /**
     * The uranium they should have.
     *
     * @var int
     */
    public $uraniumStandard;

    /**
     * The tanks they should have.
     *
     * @var int
     */
    public $tanksStandard;

    /**
     * The planes they should have.
     *
     * @var int
     */
    public $airforceStandard;

    /**
     * The ships they should have.
     *
     * @var int
     */
    public $navyStandard;

    /**
     * The spies they should have.
     *
     * @var int
     */
    public $spyStandard;

    // Current
    /**
     * The money that they currently have.
     *
     * @var int
     */
    public $money;

    /**
     * The food that they currently have.
     *
     * @var int
     */
    public $food;

    /**
     * The gas that they currently have.
     *
     * @var int
     */
    public $gas;

    /**
     * The munitions that they currently have.
     *
     * @var int
     */
    public $munitions;

    /**
     * The steel that they currently have.
     *
     * @var int
     */
    public $steel;

    /**
     * The aluminum that they currently have.
     *
     * @var int
     */
    public $aluminum;

    /**
     * The uranium that they currently have.
     *
     * @var int
     */
    public $uranium;

    /**
     * The soldiers that they currently have.
     *
     * @var int
     */
    public $soldiers;

    /**
     * The tanks that they currently have.
     *
     * @var int
     */
    public $tanks;

    /**
     * The planes that they currently have.
     *
     * @var int
     */
    public $airforce;

    /**
     * The ships that they currently have.
     *
     * @var int
     */
    public $navy;

    /**
     * Their MMR Score.
     *
     * @var int
     */
    public $mmrScore;

    /**
     * Store their number of cities.
     *
     * @var int
     */
    public $cities;

    /**
     * The nation's score.
     *
     * @var int
     */
    public $score;

    /**
     * The nation's signin.
     *
     * @var DefenseNations
     */
    public $signIn;

    /**
     * Hold the nation's tier
     *
     * @var MMR
     */
    public $tier;

    /**
     * Warchest constructor.
     *
     * Gets the nation and stores their standards
     * @param \App\Classes\Nation $nation
     * @throws \Exception
     */
    public function __construct(\App\Classes\Nation $nation = null)
    {
        if ($nation === null) // If $nation isn't provide, return an empty instance of this object
            return;

        $this->nation = $nation;
        $this->score = $nation->score;
        $this->cities = $nation->cities;

        $this->calcStandards();
    }

    /**
     * Gets and stores the nation's current resources by querying the defense database.
     */
    public function getCurrentResources()
    {
        $this->signIn = DefenseNations::where("nID", "=", $this->nation->nID)->firstOrFail();

        $this->money = $this->signIn->money;
        $this->uranium = $this->signIn->uranium;
        $this->food = $this->signIn->food;
        $this->gas = $this->signIn->gas;
        $this->munitions = $this->signIn->munitions;
        $this->steel = $this->signIn->steel;
        $this->aluminum = $this->signIn->aluminum;
        $this->soldiers = $this->signIn->soldiers;
        $this->tanks = $this->signIn->tanks;
        $this->airforce = $this->signIn->planes;
        $this->navy = $this->signIn->ships;
    }

    /**
     * Calculates what they should have.
     */
    public function calculateReqs()
    {
        // Calculate total percentage of them meeting their requirements
        $food = @($this->food / $this->foodStandard);
        $gas = @($this->gas / $this->gasStandard);
        $munitions = @($this->munitions / $this->munitionsStandard);
        $steel = @($this->steel / $this->steelStandard);
        $aluminum = @($this->aluminum / $this->aluminumStandard);
        $uranium = @($this->uranium / $this->uraniumStandard);
        $money = @($this->money / $this->moneyStandard);

        // Make sure none of them are greater than 1
        if ($food > 1)
            $food = 1;
        if ($gas > 1)
            $gas = 1;
        if ($munitions > 1)
            $munitions = 1;
        if ($steel > 1)
            $steel = 1;
        if ($aluminum > 1)
            $aluminum = 1;
        if ($uranium > 1)
            $uranium = 1;
        if ($money > 1)
            $money = 1;

        // Weigh the values
        $foodWeight = @($food * 0.05);
        if (is_nan($foodWeight))
            $foodWeight = 0;
        $moneyWeight = @($money * 0.00);
        if (is_nan($moneyWeight))
            $moneyWeight = 0;
        $gasWeight = @($gas * 0.2);
        if (is_nan($gasWeight))
            $gasWeight = 0;
        $munitionsWeight = @($munitions * 0.2);
        if (is_nan($munitionsWeight))
            $munitionsWeight = 0;
        $steelWeight = @($steel * 0.25);
        if (is_nan($steelWeight))
            $steelWeight = 0;
        $aluminumWeight = @($aluminum * 0.2);
        if (is_nan($aluminumWeight))
            $aluminumWeight = 0;
        $uraniumWeight = @($uranium * 0.1);
        if (is_nan($uraniumWeight))
            $uraniumWeight = 0;

        // For testing - echo "Food - $foodWeight <br> Gas - $gasWeight <br> Munitions - $munitionsWeight <br> Steel - $steelWeight <br> Aluminum - $aluminumWeight <br>";

        // Add all up and get an average
        $calculate = (($foodWeight + $gasWeight + $munitionsWeight + $steelWeight + $aluminumWeight + $uraniumWeight + $moneyWeight) * 100);

        $this->mmrScore = round($calculate, 2);
    }

    /**
     * Calculate the nation's MMR score by using a DefenseNations object.
     *
     * @param DefenseNations $defNation
     * @return float
     */
    public static function mmrScoreFromDefNations(DefenseNations $defNation) : float
    {
        $warchest = new self;
        $warchest->score = $defNation->score;
        $warchest->cities = $defNation->cities;

        $warchest->food = $defNation->food;
        $warchest->gas = $defNation->gas;
        $warchest->munitions = $defNation->munitions;
        $warchest->steel = $defNation->steel;
        $warchest->aluminum = $defNation->aluminum;
        $warchest->uranium = $defNation->uranium;
        $warchest->money = $defNation->money;

        $warchest->calcStandards();

        $warchest->calculateReqs();

        return $warchest->mmrScore;
    }

    /**
     * Calculate standards.
     */
    private function calcStandards()
    {
        $this->getTier();
        $this->calcGas();
        $this->calcMunitions();
        $this->calcSteel();
        $this->calcAluminum();
        $this->calcFood();
        $this->calcUranium();
        $this->calcMoney();
    }

    /**
     * Grabs the MMR tier for the nation
     */
    protected function getTier()
    {
        $this->tier = MMR::getCityMMR($this->cities);
    }

    public function calcMoney()
    {
        $this->moneyStandard = 0;
    }

    /**
     * Calculate gas standards.
     */
    public function calcGas()
    {
        $this->gasStandard = $this->tier->gas;
    }

    /**
     * Calculate munitions standards.
     */
    protected function calcMunitions()
    {
        $this->munitionsStandard = $this->tier->munitions;
    }

    /**
     * Calculate steel standards.
     */
    public function calcSteel()
    {
        $this->steelStandard = $this->tier->steel;
    }

    /**
     * Calculate aluminum standards.
     */
    protected function calcAluminum()
    {
        $this->aluminumStandard = $this->tier->aluminum;
    }

    /**
     * Calculate aluminum standards.
     */
    protected function calcUranium()
    {
        $this->uraniumStandard = $this->tier->uranium;
    }

    /**
     * Calculate food standards.
     */
    protected function calcFood()
    {
        $this->foodStandard = $this->tier->food;
    }

    /**
     * Determines if the nation has signed in or not.
     *
     * @return bool
     */
    public function checkIfSignedIn() : bool
    {
        if ($this->signIn->hasSignedIn)
            return true;
        else
            return false;
    }
}
