<?php

namespace App\Classes;

use App\Models\Grants\IDGrants;
use App\Models\Grants\irondomeGrants;
use App\Models\Grants\pbGrants;
use App\Models\Grants\mlpGrants;
use App\Models\Grants\EGRGrant;
use App\Models\Grants\nrfGrant;
use App\Models\Grants\NukeGrants;
use App\Models\Profile;
use Illuminate\Http\Request;
use App\Models\Grants\EntranceAid;
 use App\Models\Grants\BauxiteGrant;

/**
 * Class Verify.
 *
 * Laravel has a built in class for verification, however I decided to make my own for ease of use
 */
class Verify
{
    /**
     * Contains all of the errors.
     *
     * @var array
     */
    public $errors = [];

    /**
     * Contains the Nation object for the verification.
     *
     * @var Nation
     */
    protected $nation;

    /**
     * Stores the nation's profile.
     *
     * @var Profile
     */
    public $profile;

    /**
     * Stores the nation's MMR percentage requirement.
     *
     * @var int
     */
    private $mmrReq = 0;
    /**
     * Defines if they're eligible or not.
     *
     * @var bool
     */
    public $eligible = true;

    /**
     * Stores the member's forum profile.
     *
     * @var ForumProfile
     */
    protected $forumProfile;

    // City grant values
    /**
     * Which city grant the person is applying for.
     *
     * @var int
     */
    protected $cityGrantNum;

    // Loan values
    /**
     * The amount the loan they're requesting for is.
     *
     * @var int
     */
    protected $loanAmount;

    /**
     * The loan reason.
     *
     * @var string
     */
    protected $loanReason;

    /**
     * What the max loan they can take out is.
     *
     * @var int
     */
    protected $maxLoan;

    // Activity
    /**
     * What activity grant threshold they're applying for.
     *
     * @var int
     */
    public $threshold;

    /**
     * What their reward should is.
     *
     * @var int
     */
    public $activityAmount;

    // Market
    /**
     * The amount of resources the nation is selling.
     *
     * @var int
     */
    public $marketAmount;

    /**
     * The maximum amount that we are buying of that resource.
     *
     * @var int
     */
    public $marketMaxAmount;

    /**
     * Which resource the nation is trying to sell us.
     *
     * @var string
     */
    public $marketResource;

    /**
     * Verify constructor.
     *
     * @param Nation $nation
     */
    public function __construct(Nation $nation)
    {
        $this->nation = $nation;
        self::getProfile();
    }

    /**
     * Verifies City Grant requests.
     *
     * @return bool
     */
    public function requestCityGrant() : bool
    {
        $this->cityGrantNum = $this->nation->cities + 1;

        if ($this->nation->exists && $this->inBK() && $this->notApplicant() && $this->checkIfCityGrantExists())
        {
            $this->checkCityReq();
            $this->checkIfCityGrantPending();
            $this->checkLastCityGrant();
            $this->forumProfile = new ForumProfile($this->nation->nID);

            // bypass this check if it is grant 5 or below
            if (!($this->cityGrantNum <= 10))
            {
                $this->checkCityTimers();
                $this->checkIfGreen();
            }

            $this->checkWarchest($this->mmrReq);
        }

        if ($this->eligible)
            return true;

        return false;
    }

    /**
     * Verifies loan requests.
     *
     * @param int $amount
     * @param string $reason
     * @return bool
     * @throws \Exception
     */
    public function requestLoan(int $amount, string $reason) : bool
    {
        $this->loanAmount = $amount;
        $this->loanReason = $reason;
        if (empty($this->loanAmount) || empty($this->loanReason)) // Checking if the values are actually filled out
            throw new \Exception("Not all the values are filled out");
        $this->maxLoan = \App\Models\Settings::where("sKey", "maxLoan")->firstOrFail()->value;

        if ($this->nation->exists && $this->inBK() && $this->notApplicant()) // Check critical things first, then check the rest of shit
        {
            $this->checkActiveLoan();
            $this->checkLoanAmount();
            $this->lastLoanCheck();
            $this->forumProfile = new ForumProfile($this->nation->nID);
            $this->checkIfGreen();
        }

        if ($this->eligible)
            return true;

        return false;

    }

    /**
     * Verifies Entrance Aid requests.
     *
     * @return bool
     * @throws \Exception
     */
    public function requestEntranceAid() : bool
    {
        if ($this->nation->exists && $this->inBK() && $this->notApplicant())
        {
            $this->forumProfile = new ForumProfile($this->nation->nID);
            $this->notStudent();
            $this->checkIfPendingEntAid();
            $this->checkIfGottenEntrance();
            if ($this->eligible)
                return true;

            return false;
        }
    }

    /**
     * Verifies CIA requests.
     *
     * @param ForumProfile $forumProfile
     * @return bool
     */
    public function requestCIAGrant(ForumProfile $forumProfile) : bool
    {
        $this->forumProfile = $forumProfile;

        if ($this->nation->exists && $this->inBK() && $this->notApplicant())
        {
            $this->checkIfHasCIA();
            $this->checkCityTimers();
            $this->checkIfGottenIDGrant();
            $this->checkIfPendingIDGrant();
            $this->checkIfGreen();
        }

        if ($this->eligible)
            return true;

        return false;
    }

    /**
     * Verifies Iron Dome requests.
     *
     * @param ForumProfile $forumProfile
     * @return bool
     */
    public function requestirondomeGrant(ForumProfile $forumProfile) : bool
    {
        $this->forumProfile = $forumProfile;

        if ($this->nation->exists && $this->inBK() && $this->notApplicant())
        {
            $this->checkIfHasirondome();
            $this->checkIfnocia();
            $this->checkCityTimers();
            $this->checkIfGottenirondomeGrant();
            $this->checkIfPendingirondomeGrant();
            $this->checkIfGreen();
        }

        if ($this->eligible)
            return true;

        return false;
    }

        /**
         * Verifies Bauxite requests.
         *
         * @param ForumProfile $forumProfile
         * @return bool
         */
        public function requestbauxiteGrant(ForumProfile $forumProfile) : bool
        {
            $this->forumProfile = $forumProfile;

            if ($this->nation->exists && $this->inBK() && $this->notApplicant())
            {

                $this->checkIfGreen();
            }

            if ($this->eligible)
                return true;

            return false;
        }

    /**
     * Verifies mlp requests.
     *
     * @param ForumProfile $forumProfile
     * @return bool
     */
    public function requestmlpGrant(ForumProfile $forumProfile) : bool
    {
        $this->forumProfile = $forumProfile;

        if ($this->nation->exists && $this->inBK() && $this->notApplicant())
        {
            $this->checkIfHasmlp();
            $this->checkIfnocia();
            $this->checkIfnoirondome();
            $this->checkCityTimers();
            $this->checkIfGottenmlpGrant();
            $this->checkIfPendingmlpGrant();
            $this->checkIfGreen();
        }

        if ($this->eligible)
            return true;

        return false;
    }
    /**
     * Verifies nrf requests.
     *
     * @param ForumProfile $forumProfile
     * @return bool
     */
    public function requestnrfGrant(ForumProfile $forumProfile) : bool
    {
        $this->forumProfile = $forumProfile;

        if ($this->nation->exists && $this->inBK() && $this->notApplicant())
        {
            $this->checkIfHastheNRF();
            $this->checkIfnocia();
            $this->checkIfnoirondome();
            $this->checkIfnomlp();
            $this->checkIfnopb();
            $this->checkIfnocce();
            $this->checkCityTimers();
            $this->checkIfGottennrfGrant();
            $this->checkIfPendingnrfGrant();
            $this->checkIfGreen();
        }

        if ($this->eligible)
            return true;

        return false;
    }
    /**
     * Verifies pb requests.
     *
     * @param ForumProfile $forumProfile
     * @return bool
     */
    public function requestpbGrant(ForumProfile $forumProfile) : bool
    {
        $this->forumProfile = $forumProfile;

        if ($this->nation->exists && $this->inBK() && $this->notApplicant())
        {
            $this->checkIfHaspb();
            $this->checkIfnocia();
            $this->checkIfnoirondome();
            $this->checkIfnomlp();
            $this->checkCityTimers();
            $this->checkIfGottenpbGrant();
            $this->checkIfPendingpbGrant();
            $this->checkIfGreen();
        }

        if ($this->eligible)
            return true;

        return false;
    }
    /**
     * Verifies cce requests.
     *
     * @param ForumProfile $forumProfile
     * @return bool
     */
    public function requestcceGrant(ForumProfile $forumProfile) : bool
    {
        $this->forumProfile = $forumProfile;

        if ($this->nation->exists && $this->inBK() && $this->notApplicant())
        {
            $this->checkIfHascce();
            $this->checkIfnocia();
            $this->checkIfnoirondome();
            $this->checkIfnomlp();
            $this->checkIfnopb();
            $this->checkCityTimers();
            $this->checkIfGottencceGrant();
            $this->checkIfPendingcceGrant();
            $this->checkIfGreen();
        }

        if ($this->eligible)
            return true;

        return false;
    }

    public function requestEGRGrant(ForumProfile $forumProfile) : bool
    {
        $this->forumProfile = $forumProfile;

        if ($this->nation->exists && $this->inBK() && $this->notApplicant())
        {
            $this->checkIfHasEGR();
            $this->checkCityTimers();
            $this->checkIfGottenEGRGrant();
            $this->checkIfPendingEGRGrant();
            $this->checkIfGreen();
            $this->checkGasRefineries();
            $this->cityAmount(2);
        }

        if ($this->eligible)
            return true;

        return false;
    }

    public function requestNukeGrants(ForumProfile $forumProfile) : bool
    {
        $this->forumProfile = $forumProfile;

        if ($this->nation->exists && $this->inBK() && $this->notApplicant())
        {
            $this->checkIfHasNRF();
            $this->checkIfPendingNukeGrants();
            $this->checkIfGreen();
            $this->cityAmount(19);
        }

        if ($this->eligible)
            return true;

        return false;
    }

    /**
     * Verifies Activity Grant requests.
     *
     * @param ForumProfile $profile
     * @return bool
     */
    public function requestActivityGrant(ForumProfile $profile) : bool
    {
        $this->forumProfile = $profile;

        if ($this->nation->exists && $this->inBK() && $this->notApplicant())
        {
            $this->notStudent();
            $activity = new \App\Models\Grants\ActivityGrant();
            $activity->determineNextGrant($profile);
            $activity->determineReward();
            $this->checkIfGottenActivityGrant($activity->grant);
            $this->checkPendingActivity();
            $this->threshold = $activity->grant;
            $this->activityAmount = $activity->reward;
            $this->checkIfGreen();
        }

        if ($this->eligible)
            return true;

        return false;
    }

    public function reqBauxite()
    {
        if ($this->nation->exists && $this->inBK() && $this->notApplicant())
        {
            $this->checkPendingbauxite();
            $this->checkIfGreen();
        }

        if ($this->eligible)
            return true;

        return false;
    }


    public function reqOil()
    {
        if ($this->nation->exists && $this->inBK() && $this->notApplicant())
        {
            $this->checkPendingOil();
            $this->checkIfGreen();
            $this->checkGasRefineries();
            $this->cityAmount(2);
        }

        if ($this->eligible)
            return true;

        return false;
    }

    /**
     * Verifies market requests.
     *
     * @param Request $request
     * @return bool
     * @throws \Exception
     */
    public function reqMarket(Request $request) : bool
    {
        if ($this->nation->exists && $this->inBK() && $this->notApplicant())
        {
            // Get the total we will buy
            $this->marketResource = \App\Models\Market::getResourceInfo($request->resource);
            if ($this->marketResource->resource != $request->resource) // We check this just incase they messed with the resource name. If they did, we'll invalidate it here
                throw new \Exception("That resource doesn't exist");
            $this->checkMarketAmount($request->amount);
            $this->checkIfGreen();
        }

        if ($this->eligible)
            return true;

        return false;
    }

    /**
     * Gets the nation's profile and stores it.
     */
    private function getProfile()
    {
        $this->profile = \App\Models\Profile::getProfile($this->nation->nID);
    }

    /**
     * Checks if the nation is in Rothschilds & Co. by checking their alliance ID.
     *
     * @return bool
     */
    private function inBK() : bool
    {
        if ($this->nation->aID != 7399)
        {
            array_push($this->errors, "You must be in The Rothschild Family in order to be eligible");
            $this->eligible = false;

            return false;
        }

        return true;
    }

    /**
     * Checks if the nation is NOT an applicant by checking their alliancePosition.
     *
     * @return bool
     */
    private function notApplicant() : bool
    {
        if ($this->nation->alliancePosition > 1)
            return true;

        $this->eligible = false;
        array_push($this->errors, "Applicants are not eligible");

        return false;
    }

    private function cityAmount($number)
    {
        if ($this->nation->cities >= $number)
            return true;

        $this->eligible = false;
        array_push($this->errors, "You do not have enough cities.");

        return false;
    }

    /**
     * Checks if the city grant exists.
     *
     * @return bool
     */
    private function checkIfCityGrantExists() : bool
    {
        $number = \App\Models\Grants\CityGrant::where('grantNum', $this->cityGrantNum)->count();

        if ($number === 0)
        {
            $this->eligible = false;
            array_push($this->errors, "The city grant you requested does not exist. Please submit a contact request");

            return false;
        }

        return true;
    }

    /**
     * Checks if the nation has the required infPerCity, Iron Dome, NRF, MMR Score, and anything else needed for the city grant.
     */
    private function checkCityReq()
    {
        $gInfo = \App\Models\Grants\CityGrant::getGrantInfo($this->cityGrantNum);

        $this->mmrReq = $gInfo->mmrScore; // Save the MMR score for later

        $infPerCity = $this->nation->infra / $this->nation->cities;

        if (!$gInfo->enabled)
        {
            array_push($this->errors, "This City Grant is currently disabled.");
            $this->eligible = false;
        }

        if ($infPerCity < $gInfo->infPerCity)
        {
            array_push($this->errors, "You must have ".number_format($gInfo->infPerCity)." infra per city.");
            $this->eligible = false;
        }

        if ($gInfo->irondome && $this->nation->ironDome == 0 && $this->nation->intelAgency == 0)
        {
            array_push($this->errors, "You need either a CIA or Iron Dome.");
            $this->eligible = false;
        }

        if ($gInfo->NRF && $this->nation->nuclearResFacility == 0)
        {
            array_push($this->errors, "You must have the Nuclear Research Facility.");
            $this->eligible = false;
        }
    }

    /**
     * Checks if the nation has a city grant pending by using their profile.
     *
     * @return bool
     */
    private function checkIfCityGrantPending() : bool
    {
        if ($this->profile->grantPending)
        {
            $this->eligible = false;
            array_push($this->errors, "You already have a pending city grant");

            return false;
        }

        return true;
    }

    /**
     * Checks the last city grant the nation got by checking their profile.
     * This prevents someone from getting the same grant twice.
     *
     * @return bool
     */
    private function checkLastCityGrant() : bool
    {
        if ($this->profile->lastGrant == 0)
        {
            // If they've never requested a grant, it'll be set as 0. We should update that and set it as their current city #
            $this->profile->lastGrant = $this->nation->cities;
            $this->profile->save();
        }
        // Now check if they've already gotten this grant or a previous one
        if ($this->profile->lastGrant > $this->nation->cities)
        {
            $this->eligible = false;
            array_push($this->errors, "You've already gotten this grant");

            return false;
        }

        return true;
    }

    /**
     * Checks two city grant timers.
     *
     * Checks the timer from the last time they were sent a city grant. Ineligible if under 10 days
     * Checks the API's city/project timer. Ineligible if under 10 days
     *
     * @return bool
     */
    private function checkCityTimers() : bool
    {
        // First check the API's timer
        if ($this->nation->cityProjectTimer > 12)
        {
            $this->eligible = false;
            array_push($this->errors, "You've purchased a city or project in the last 9 days. Please wait ".($this->nation->cityProjectTimer - 12)." more turns.");

            return false;
        }

        // Now do our own timer based on their profile.

        // If their lastGrantDate is null, that means they haven't gotten a city grant before
        // If null, they shouldn't be denied for this reason so just skip the verification otherwise they will be denied

        /* Commented out because we don't want to check this anymore
        if ($this->profile->lastGrantDate != null)
        {
            $now = \Carbon\Carbon::now();
            $lastGrant = \Carbon\Carbon::parse($this->profile->lastGrantDate);
            $diff = $now->diffInDays($lastGrant);

            if ($diff < 10)
            {
                $this->eligible = false;
                array_push($this->errors, "You've gotten a city grant within the past 10 days.");
                return false;
            }
        }*/

        return true;
    }

    /**
     * Checks the nation's warchest and if they've signed in or not.
     *
     * @param $requirement
     * @return bool
     */
    private function checkWarchest($requirement) : bool
    {
        try
        {
            $defProfile = \App\Defense\DefenseProfiles::where("nID", "=", $this->nation->nID)->firstOrFail();

            // Check if signed in
            if (! $defProfile->hasSignedIn)
            {
                $this->eligible = false;
                array_push($this->errors, "You haven't signed in to the Defense Module yet. Please <a href='http://bank.blackbird.im/signin' target='_blank'>CLICK HERE</a> to complete your defense sign in. Once finished, come back here and make another request.");

                return false;
            }

            $warchest = new \App\Defense\Warchest($this->nation);
            $warchest->getCurrentResources();
            $warchest->calculateReqs();

            if ($warchest->mmrScore < $requirement)
            {
                $this->eligible = false;
                array_push($this->errors, "Sorry, you don't meet the required MMR score. <a href='http://bank.blackbird.im/defense/dashboard' target='_blank'>Click here</a> to see your MMR score.");

                return false;
            }

            return true;
        }
        catch (\Exception $ex)
        {
            $this->eligible = false;
            array_push($this->errors, "You haven't signed in to the Defense Module yet. Please <a href='http://bank.blackbird.im/signin' target='_blank'>CLICK HERE</a> to complete your defense sign in. Once finished, come back here and make another request.");

            return false;
        }

        return false;
    }

    /**
     * Checks if the nation has a current active loan by using their profile.
     *
     * @return bool
     */
    private function checkActiveLoan() : bool
    {
        if ($this->profile->loanActive)
        {
            $this->eligible = false;
            array_push($this->errors, "You already have an active/pending loan");

            return false;
        }

        return true;
    }

    /**
     * Checks to see if the nation has gotten a loan in the last 5 days by checking their profile.
     *
     * @return bool
     */
    private function lastLoanCheck() : bool // Check if they've gotten a loan in the last 5 days
    {
        if ($this->profile->lastLoan != null) // They've never gotten a loan so they've passed
        {
            $today = new \DateTime();
            $today->format("Y-m-d");
            $lastLoan = new \DateTime($this->profile->lastLoan);
            $diff = $today->diff($lastLoan);
            if ($diff->format('%a%') < 3)
            {
                $this->eligible = false;
                array_push($this->errors, "You cannot take out a loan for three days after your last loan");

                return false;
            }
        }

        return true;
    }

    /**
     * Makes sure the loan isn't above the maxium amount that can be loaned.
     *
     * @return bool
     */
    private function checkLoanAmount() : bool
    {
        if ($this->loanAmount > $this->maxLoan)
        {
            $this->eligible = false;
            array_push($this->errors, "You cannot loan that much");

            return false;
        }
        if ($this->loanAmount < 1)
        {
            $this->eligible = false;
            array_push($this->errors, "You asked for an amount less than one");

            return false;
        }

        return true;
    }

    /**
     * Checks their forumProfile to see if they're in the Knight group ID.
     *
     * @return bool
     */
    public function isKnight() : bool
    {
        if (in_array(7, $this->forumProfile->groups))
            return true;

        $this->eligible = false;
        array_push($this->errors, "You must be at least a Knight");

        return false;
    }

    /**
     * Checks to see if the nation is NOT a squire by checking the forumProfile for the squire group ID.
     *
     * @return bool
     */
    public function notSquire() : bool
    {
        if (in_array(133, $this->forumProfile->groups))
        {
            $this->eligible = false;
            array_push($this->errors, "Squires aren't eligible");

            return false;
        }

        return true;
    }

    /**
     * Checks to see if the nation is NOT a student by checking the forumProfile for the student group ID.
     *
     * @return bool
     */
    public function notStudent() : bool
    {
        if (in_array(77, $this->forumProfile->groups))
        {
            $this->eligible = false;
            array_push($this->errors, "Students aren't eligible");

            return false;
        }

        return true;
    }

    /**
     * Checks to see if the nation has already gotten the Entrance Aid.
     *
     * @return bool
     */
    private function checkIfGottenEntrance() : bool
    {
        if ($this->profile->entAid)
        {
            $this->eligible = false;
            array_push($this->errors, "You've already gotten entrance aid");

            return false;
        }

        return true;
    }

    private function checkPendingBauxite() : bool
    {
        if (Food::checkPendingReq($this->nation->nID))
        {
            // If they do have a pending entrance aid request
            $this->eligible = false;
            array_push($this->errors, "You already have a pending aid request");

            return false;
        }

        return true;
    }

    /**
     * Verify that the member doesn't already have a pending ent aid request.
     *
     * @return bool
     */
    private function checkIfPendingEntAid() : bool
    {
        if (EntranceAid::checkPendingReq($this->nation->nID))
        {
            // If they do have a pending entrance aid request
            $this->eligible = false;
            array_push($this->errors, "You already have a pending entrance aid request");

            return false;
        }

        return true;
    }
   private function checkIfPendingpb() : bool
    {
        if (pb::checkPendingReq($this->nation->nID))
        {
            // If they do have a pending entrance aid request
            $this->eligible = false;
            array_push($this->errors, "You already have a pending entrance aid request");

            return false;
        }

        return true;
    }

    /**
     * Checks to see if the nation already has the CIA.
     *
     * @return bool
     */
    private function checkIfHasCIA() : bool
    {
        if ($this->nation->intelAgency == 1)
        {
            $this->eligible = false;
            array_push($this->errors, "You already have a CIA.");

            return false;
        }

        return true;
    }
    /**
     * Checks to see if the nation does not have the ID
     *
     * @return bool
     */
    private function checkIfnoirondome() : bool
    {
        if ($this->nation->ironDome == 0)
        {
            $this->eligible = false;
            array_push($this->errors, "You Must get the Iron Dome First");

            return false;
        }

        return true;
    }
    /**
     * Checks to see if the nation does not have the CIA
     *
     * @return bool
     */
    private function checkIfnocia() : bool
    {
        if ($this->nation->intelAgency == 0)
        {
            $this->eligible = false;
            array_push($this->errors, "You Must get the CIA First");

            return false;
        }

        return true;
    }
    /**
     * Checks to see if the nation does not have the mlp
     *
     * @return bool
     */
    private function checkIfnomlp() : bool
    {
        if ($this->nation->missilePad == 0)
        {
            $this->eligible = false;
            array_push($this->errors, "You Must get the MLP First");

            return false;
        }

        return true;
    }
    /**
     * Checks to see if the nation does not have the mlp
     *
     * @return bool
     */
    private function checkIfnopb() : bool
    {
        if ($this->nation->propBureau == 0)
        {
            $this->eligible = false;
            array_push($this->errors, "You Must get the PB First");

            return false;
        }

        return true;
    }
    /**
     * Checks to see if the nation does not have the cce
     *
     * @return bool
     */
    private function checkIfnocce() : bool
    {
        if ($this->nation->cenCivEng == 0)
        {
            $this->eligible = false;
            array_push($this->errors, "You Must get the CCE First");

            return false;
        }

        return true;
    }
    /**
     * Checks to see if the nation already has the Iron Dome.
     *
     * @return bool
     */
    private function checkIfHasirondome() : bool
    {
        if ($this->nation->ironDome == 1)
        {
            $this->eligible = false;
            array_push($this->errors, "You already have a Iron Dome.");

            return false;
        }

        return true;
    }
    private function checkIfHaspb() : bool
    {
        if ($this->nation->propBureau == 1)
        {
            $this->eligible = false;
            array_push($this->errors, "You already have a PB.");

            return false;
        }

        return true;
    }
    private function checkIfHascce() : bool
    {
        if ($this->nation->cenCivEng == 1)
        {
            $this->eligible = false;
            array_push($this->errors, "You already have a CCE!");

            return false;
        }

        return true;
    }
    private function checkIfHasmlp() : bool
    {
        if ($this->nation->missilePad == 1)
        {
            $this->eligible = false;
            array_push($this->errors, "You already have a MLP.");

            return false;
        }

        return true;
    }

    private function checkIfHasEGR() : bool
    {
        if ($this->nation->emgGasReserve == 1)
        {
            $this->eligible = false;
            array_push($this->errors, "You already have an EGR.");

            return false;
        }

        return true;
    }
	
    private function checkIfHasNRF() : bool
    {
        if ($this->nation->nuclearResFacility == 0)
        {
            $this->eligible = false;
            array_push($this->errors, "You dont have the NRF.");

            return false;
        }

        return true;
    }
    private function checkIfHastheNRF() : bool
    {
        if ($this->nation->nuclearResFacility == 1)
        {
            $this->eligible = false;
            array_push($this->errors, "You already have the NRF.");

            return false;
        }

        return true;
    }
    /**
     * Checks to make sure their cityProjectTimer is 0.
     *
     * @deprecated This was a duplicate method that should never have been here. Don't use
     * @return bool
     */
    private function checkLastPurchase() : bool
    {
        if ($this->nation->cityProjectTimer > 0)
        {
            $this->eligible = false;
            array_push($this->errors, "You've already bought a city or project in the last 10 days");

            return false;
        }

        return true;
    }

    /**
     * Checks if the nation has already recieved the Iron Dome Grant.
     *
     * @return bool
     */
    private function checkIfGottenIDGrant() : bool
    {
        if ($this->profile->gottenIDGrant)
        {
            $this->eligible = false;
            array_push($this->errors, "You've already gotten the CIA grant");

            return false;
        }

        return true;
    }
    private function checkIfGottenpbGrant() : bool
    {
        if ($this->profile->gottenpbGrant)
        {
            $this->eligible = false;
            array_push($this->errors, "You've already gotten the pb grant");

            return false;
        }

        return true;
    }
    private function checkIfGottencceGrant() : bool
    {
        if ($this->profile->gottencceGrant)
        {
            $this->eligible = false;
            array_push($this->errors, "You've already gotten the cce grant");

            return false;
        }

        return true;
    }

    /**
     * Checks if the nation has already recieved the MLP Grant.
     *
     * @return bool
     */
    private function checkIfGottenmlpGrant() : bool
    {
        if ($this->profile->gottenmlpGrant)
        {
            $this->eligible = false;
            array_push($this->errors, "You've already gotten the MLP grant");

            return false;
        }

        return true;
    }
    private function checkIfGottennrfGrant() : bool
    {
        if ($this->profile->gottennrfGrant)
        {
            $this->eligible = false;
            array_push($this->errors, "You've already gotten the nrf grant");

            return false;
        }

        return true;
    }

    /**
     * Checks if the nation has already recieved the Iron Dome Grant.
     *
     * @return bool
     */
    private function checkIfGottenirondomeGrant() : bool
    {
        if ($this->profile->gottenirondomeGrant)
        {
            $this->eligible = false;
            array_push($this->errors, "You've already gotten the Iron Dome grant");

            return false;
        }

        return true;
    }

    private function checkIfGottenEGRGrant() : bool
    {
        if ($this->profile->gottenEGRGrant)
        {
            $this->eligible = false;
            array_push($this->errors, "You've already gotten the EGR grant");

            return false;
        }

        return true;
    }

    /**
     * Checks if the nation has a pending ID Grant
     *
     * @return bool
     */
    private function checkIfPendingIDGrant() : bool
    {
        if (IDGrants::checkIfPending($this->nation->nID))
        {
            $this->eligible = false;
            array_push($this->errors, "You have a pending CIA Grant");

            return false;
        }

        return true;
    }
    /**
     * Checks if the nation has a pending MLP Grant
     *
     * @return bool
     */
    private function checkIfPendingmlpGrant() : bool
    {
        if (mlpGrants::checkIfPending($this->nation->nID))
        {
            $this->eligible = false;
            array_push($this->errors, "You have a pending MLP Grant");

            return false;
        }

        return true;
    }
    private function checkIfPendingnrfGrant() : bool
    {
        if (mlpGrants::checkIfPending($this->nation->nID))
        {
            $this->eligible = false;
            array_push($this->errors, "You have a pending NRF Grant");

            return false;
        }

        return true;
    }

    /**
     * Checks if the nation has a pending Iron Dome Grant
     *
     * @return bool
     */
    private function checkIfPendingirondomeGrant() : bool
    {
        if (irondomeGrants::checkIfPending($this->nation->nID))
        {
            $this->eligible = false;
            array_push($this->errors, "You have a pending Iron Dome Grant");

            return false;
        }

        return true;
    }

    private function checkIfPendingEGRGrant() : bool
    {
        if (EGRGrant::checkIfPending($this->nation->nID))
        {
            $this->eligible = false;
            array_push($this->errors, "You have a pending EGR Grant");

            return false;
        }

        return true;
    }
    private function checkIfPendingpbGrant() : bool
    {
        if (EGRGrant::checkIfPending($this->nation->nID))
        {
            $this->eligible = false;
            array_push($this->errors, "You have a pending PB Grant");

            return false;
        }

        return true;
    }
    private function checkIfPendingcceGrant() : bool
    {
        if (EGRGrant::checkIfPending($this->nation->nID))
        {
            $this->eligible = false;
            array_push($this->errors, "You have a pending cce Grant");

            return false;
        }

        return true;
    }

    private function checkIfPendingNukeGrants() : bool
    {
        if (NukeGrants::checkIfPending($this->nation->nID))
        {
            $this->eligible = false;
            array_push($this->errors, "You have a pending Nuke Grant");

            return false;
        }

        return true;
    }
    /**
     * Checks to see if the nation has already recieved the activity grant for that threshold.
     *
     * @param $threshold
     * @return bool
     */
    private function checkIfGottenActivityGrant($threshold) : bool
    {
        if ($this->profile->lastActivityGrant >= $threshold)
        {
            $this->eligible = false;
            array_push($this->errors, "You've already gotten that activity grant");

            return false;
        }

        return true;
    }

    /**
     * Checks to see if the nation already has a pending activity grant request.
     *
     * @return bool
     */
    private function checkPendingActivity() : bool
    {
        if ($this->profile->pendingActivityGrant)
        {
            $this->eligible = false;
            array_push($this->errors, "You have a pending activity grant");

            return false;
        }

        return true;
    }

    private function checkPendingOil() : bool
    {
        if ($this->profile->pendingOilGrant)
        {
            $this->eligible = false;
            array_push($this->errors, "You have a pending oil grant");

            return false;
        }

        return true;
    }

    /**
     * Checks to make sure the nation isn't submitting a stupid amount to sell resources.
     *
     * @param $amount
     * @return bool
     */
    private function checkMarketAmount($amount) : bool
    {
        if ($amount == 0) {
            $this->eligible = false;
            array_push($this->errors, "You submitted a value of 0");

            return false;
        }
        if ($amount < 0)
        {
            $this->eligible = false;
            array_push($this->errors, "You submitted a value less than 0");

            return false;
        }
        if ($amount > $this->marketResource->amount)
        {
            $this->eligible = false;
            array_push($this->errors, "You're trying to sell more than we are offering");

            return false;
        }

        return true;
    }

    /**
     * Verifies that the nation is green
     *
     * @return bool
     */
    public function checkIfGreen() : bool
    {
        if ($this->nation->color != "Green")
        {
            $this->eligible = false;
            array_push($this->errors, "Your color must be Green in order to be eligible");

            return false;
        }

        return true;
    }

    public function checkGasRefineries() : bool
    {
        foreach ($this->nation->cityIDs as $cityID)
        {
            $city = new City($cityID);
            if ($city->oilWell > 1)
            {
                array_push($this->errors, "You must have max oil refineries.");
                return false;
            }
        }

        return true;
    }
}
