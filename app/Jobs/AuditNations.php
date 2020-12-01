<?php

namespace App\Jobs;

use App\Classes\City;
use App\Classes\Nation;
use App\Classes\Verify;
use App\Classes\PWClient;
use App\Defense\Warchest;
use App\Classes\PWFunctions;
use App\Defense\DefenseNations;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AuditNations extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * The message that will be sent to the nation.
     *
     * @var string
     */
    protected $message;

    /**
     * Holds the nation object for the current active nation.
     *
     * @var Nation
     */
    protected $nation;

    /**
     * Holds the city object for the current active city.
     *
     * @var City
     */
    protected $city;

    /**
     * If the nation needs an audit or not.
     *
     * @var bool
     */
    protected $nationAudit;

    /**
     * Holds the message for each city's audit.
     *
     * @var string
     */
    protected $cityMessage;

    /**
     * If that city needs to be audited.
     *
     * @var bool
     */
    protected $cityAudit;

    /**
     * Holds the DefenseNations object. Bascially contains MMR stuff.
     *
     * @var DefenseNations
     */
    protected $mmr;

    /**
     * Execute the job.
     *
     * @see \App\Classes\Nation
     * @see \App\Classes\City
     * @throws \Exception Some of these functions called here can throw an exception. We'll catch them and send me a message with the stack
     * @return void
     */
    public function handle()
    {
        $nIDs = PWFunctions::getAllianceNationIDs(7399); // Get all nation IDs in BK

        $client = new PWClient();
        $client->login(); // Login to PW

        try
        {
            foreach ($nIDs as $nID) // Loop over every nation
            {
                $this->nation = new Nation($nID);

                if ($this->nation->alliancePosition == 1)
                    continue; // If they're an applicant, just go to the next iteration

                $this->message = "Hi {$this->nation->leader}, \n \n Your nation has been audited. We've come up with the following suggestions: \n \n";
                $this->nationAudit = false;
                $this->message .= "[b]Overall Nation[/b]: \n";

                // Run the overall nation audit
                $this->runNationAudit();

                // If they don't need a nation audit, say their shit looks nice
                if (! $this->nationAudit)
                    $this->message .= "Nothing! \n";

                // Get all their cities and do an audit
                foreach ($this->nation->cityIDs as $cID)
                {
                    $this->city = new City($cID);
                    // Run the audit on this nation
                    $this->runCityAudit();

                    if ($this->cityAudit) // If they need an audit for the city, add the city audit to the message. If not, no need to display it
                        $this->message .= $this->cityMessage;
                }

                try
                {
                    $this->runMMRAudit();
                }
                catch (ModelNotFoundException $e)
                {
                    continue; // This exception will be thrown if it can't find the nation in the database. So just skip over them. Fuck them.
                }

                if (! $this->needsAudit) // If they don't need an audit there's no reason to spam them with a bunch of shit
                {
                    $this->message = "Hi {$this->nation->leader}, \n \n We've audited your nation and we couldn't find anything to bug you about. Great job! \n";
                }

                $this->message .= "\n If you need further help or a more specialized audit, go [link=https://bkpw.net/topic/1761-how-to-request-audit/]here[/link]";

                $client->sendMessage($this->nation->leader, "Your nation has been audited", $this->message);
            } // End nation foreach
        }
        catch (\Exception $e)
        {
            $client->sendMessage("Whizzy", "Audit Nations Error", $e);
        }
    }
    
    /**
     * Calls the needed functions for an overall nation audit.
     *
     * @return void
     */
    protected function runNationAudit()
    {
        $this->checkIfEligibleForCityGrant();
    }

    /**
     * Calls the needed functions for a city audit.
     *
     * @return void
     */
    protected function runCityAudit()
    {
        $this->cityMessage = ""; // Reset the city message
        $this->cityAudit = false; // Reset this too
        $this->cityMessage .= "\n [b]{$this->city->name}[/b]: \n";
        // Run all of the checks
        $this->checkFarms();
        $this->checkUraniumMines();
        $this->checkLand();
        $this->checkNuclearPower();
        $this->checkCommerce();
        $this->countImprovementSlots();
        $this->checkPowered();
        $this->checkInfra();
        $this->checkWindPower();
        $this->checkPollution();
        $this->checkPoliceStation();
        $this->checkSupermarket();
        $this->checkPopulationDensity();
        $this->checkDisease();
    }

    /**
     * Calls the needed functions when doing an MMR audit.
     *
     * @return void
     */
    protected function runMMRAudit()
    {
        $this->mmrAudit = false; // So we can later determine if we tell them they've been a good boy
        $this->getMMRInfo();
        $this->determineMMRMessage();
    }

    /**
     * Determines if the nation is eligible for another city grant.
     *
     * Uses the same verification as actually submitting a request, so if they are eligible here they should be when applying for one
     *
     * @return void
     */
    protected function checkIfEligibleForCityGrant()
    {
        $verify = new Verify($this->nation);
        if ($verify->requestCityGrant())
        {
            $this->needsCityAudit();
            $this->message .= "-- You can buy a new city. Please go [link=".url("grants/city")."]here[/link] to apply for a city grant \n";
        }
    }

    /**
     * Checks for farms.
     *
     * If the city has less than 2500 land and has more than 1 farm
     *
     * @return void
     */
    protected function checkFarms()
    {
        if ($this->city->land < 2500 && $this->city->farm > 0)
        {
            $this->needsCityAudit();
            $this->cityMessage .= "-- You should not have farms in your city because of how cheap food is. Once you get over 2,500 land then you should have farms \n";
        }
    }

    /**
     * Checks for nations producting uranium without the uranium enrichment project.
     *
     * @return void
     */
    protected function checkUraniumMines()
    {
        if ($this->city->uraniumMine == 1 && $this->nation->uraniumEnrichment == 0)
        {
            $this->needsCityAudit();
            $this->cityMessage .= "-- You should not produce uranium because it is so cheap unless you have the Uranium Enrichment Project. \n";
        }
    }

    /**
     * Checks if the city has less than 500 miles of land.
     *
     * @return void
     */
    protected function checkLand()
    {
        if ($this->city->land < 500)
        {
            $this->needsCityAudit();
            $this->cityMessage .= "-- You need to buy more land in your city. Get it to at least 500 \n";
        }
    }

    /**
     * Checks for nuclear power.
     *
     * If the city has more than 1,000 infra and no nuclear plants, they should have a nuclear power plant
     *
     * @return void
     */
    protected function checkNuclearPower()
    {
        if ($this->city->infra > 1000 && $this->city->nuclearPower == 0)
        {
            $this->needsCityAudit();
            $this->cityMessage .= "-- You should switch to Nuclear Power for the best use of your improvement slots \n";
        }
    }

    /**
     * Checks for commerce.
     *
     * If the city has less than 100% commerce and its infra is less than 1,000
     *
     * @return void
     */
    protected function checkCommerce()
    {
        if ($this->city->commerce < 100 && $this->city->infra >= 1000)
        {
            $this->needsCityAudit();
            $this->cityMessage .= "-- You should try to get your commerce to 100% to get more money \n";
        }
    }

    /**
     * Verifies the city is using 100% of its improvement slots.
     *
     * @return void
     */
    protected function countImprovementSlots()
    {
        if ($this->city->countImprovementSlots() < $this->city->totalImprovementSlots())
        {
            $this->needsCityAudit();
            $this->cityMessage .= "-- You are not using all of your improvement slots. You need to use all of them for maximum efficiency \n";
        }
    }

    /**
     * Makes sure the city is powered.
     *
     * @return void
     */
    protected function checkPowered()
    {
        if ($this->city->powered != "Yes")
        {
            $this->needsCityAudit();
            $this->cityMessage .= "-- [b]Your city is not powered. Power your city ASAP[/b] \n";
        }
    }

    /**
     * Makes sure that the city's infra is bought in increments of 100.
     *
     * @return void
     */
    protected function checkInfra()
    {
        if ($this->city->infra % 100 != 0)
        {
            $this->needsCityAudit();
            $this->cityMessage .= "-- Your infra isn't being bought in increments of 100. Buy up to the closest 100 and then only buy infra in increments of 100 to make the most of your money \n";
        }
    }

    /**
     * Makes sure there are no wind power improvements in the city.
     *
     * @return void
     */
    protected function checkWindPower()
    {
        if ($this->city->windPower > 0)
        {
            $this->needsCityAudit();
            $this->cityMessage .= "-- You should not use wind power in your cities. Remove them and replace them with something else \n";
        }
    }

    /**
     * Checks if the city has less than 175 points of pollution.
     *
     * @return void
     */
    protected function checkPollution()
    {
        if ($this->city->pollution > 175)
        {
            $this->needsCityAudit();
            $this->cityMessage .= "-- Your pollution is very high. You should buy some recycling centers to bring it down to under 175 \n";
        }
    }

    /**
     * Checks the city for a police station.
     *
     * If the city has less than 1,000 infra and has no police station
     *
     * @return void
     */
    protected function checkPoliceStation()
    {
        if ($this->city->infra >= 1000 && $this->city->policeStation == 0)
        {
            $this->needsCityAudit();
            $this->cityMessage .= "You should buy a police station to help reduce your crime rate \n";
        }
    }

    /**
     * Checks for supermarkets.
     *
     * If the city has more than 1 supermarket and if they don't have the international trade center
     *
     * @return void
     */
    protected function checkSupermarket()
    {
        if ($this->city->supermarket > 0 && $this->nation->intTradeCenter == 0)
        {
            $this->needsCityAudit();
            $this->cityMessage .= "-- You shouldn't have supermarkets in your city unless you have the International Trade Center. Please remove your supermarkets and replace them with something better \n";
        }
    }

    /**
     * Makes sure the city's population density is less than 200.
     *
     * @return void
     */
    protected function checkPopulationDensity()
    {
        if ($this->city->basePopDensity > 200)
        {
            $this->needsCityAudit();
            $this->cityMessage .= "-- Your population density is quite high. Buy some land to get it to at least under 200 \n";
        }
    }

    /**
     * Checks for disease.
     *
     * If the city's disease level is greater than 2 and its infra is less than or equal to 1,500
     *
     * @return void
     */
    protected function checkDisease()
    {
        if ($this->city->disease > 2 && $this->city->infra >= 1500)
        {
            $this->needsCityAudit();
            $this->cityMessage .= "-- Your disease is quite high, you should buy a hospital \n";
        }
    }

    /**
     * If the city needs to be audited, set the needed booleans to the proper value for future checking.
     *
     * @return void
     */
    protected function needsCityAudit()
    {
        $this->cityAudit = true;
        $this->needsAudit = true;
    }

    /**
     * Gets the sign in info for the nation.
     *
     * Queries the nations table in the defense database and stores it so we can use it later
     *
     * @see \App\Classes\Warchest::class
     * @throws ModelNotFoundException if the nation cannot be found in the database
     * @return void
     */
    protected function getMMRInfo()
    {
        $this->message .= "\n [b]Warchest Audit[/b]: \n";
        $this->mmr = new Warchest($this->nation);
        $this->mmr->getCurrentResources();
        $this->mmr->calculateReqs();
    }

    /**
     * Determines which message we should give them.
     *
     * Evaluates their MMR score and decides what bitching message should be included
     *
     * @return void
     */
    protected function determineMMRMessage()
    {
        if (! $this->mmr->checkIfSignedIn())
        {
            $this->message .= "-- You haven't signed in yet. Please go [link=https://banque-lumiere.pro/signin]here[/link] to sign in \n";
        }
        else
        {
            // If they're garbage
            if ($this->mmr->mmrScore < 50 && $this->nation->cities > 7)
            {
                $this->needsMMRAudit();
                $this->message .= "-- You [b]REALLY[/b] do not meet your warchest requirements. This needs to be your #1 priority. Go [link=https://banque-lumiere.pro/mmr]here[/link] to check your requirements. Your MMR Score is {$this->mmr->mmrScore} \n";
            }
            elseif ($this->mmr->mmrScore < 80 && $this->nation->cities > 7) // If they're not so bad
            {
                $this->needsMMRAudit();
                $this->message .= "-- You are on your way to meeting your warchest requirements, however you aren't close enough. Please work on meeting your warchest requirements ASAP. Go [link=https://banque-lumiere.pro/mmr]here[/link] to check your requirements. Your MMR Score is {$this->mmr->mmrScore} \n";
            }
            elseif ($this->mmr->mmrScore < 100 && $this->nation->cities > 7) // If they're pretty good
            {
                $this->needsMMRAudit();
                $this->message .= "-- You are almost meeting your warchest requirements, but you aren't quite there. Please work on meeting your warchest requirements ASAP. Go [link=https://banque-lumiere.pro/mmr]here[/link] to check your requirements. Your MMR Score is {$this->mmr->mmrScore} \n";
            }
            elseif ($this->mmr->mmrScore >= 100 && $this->nation->cities > 7) // If they're Curu
            {
                $this->needsMMRAudit();
                $this->message .= "-- Congratulations! You meet your warchest requirements. Have a cookie because there's not many of you \n";
            }
            elseif ($this->nation->cities < 8) // If they're under 8 cities idgaf about MMR
            {
                $this->mmrAudit = true;
                $this->message .= "-- We don't heavily enforce warchest requirements for nations under 8 cities, however, make sure you have an adequate stockpile to build up your military in a moments notice \n";
            }
        }
    }

    /**
     * Sets the needed values for future checking if they need an MMR audit.
     *
     * I don't think this is actually needed, but whatever
     *
     * @return void
     */
    protected function needsMMRAudit()
    {
        $this->needsAudit = true;
        $this->mmrAudit = true;
    }
}
