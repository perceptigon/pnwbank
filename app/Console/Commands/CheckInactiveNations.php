<?php

namespace App\Console\Commands;

use App\Classes\Nation;
use App\Classes\PWClient;
use App\Classes\PWFunctions;
use App\Models\Inactivity;
use Illuminate\Console\Command;

class CheckInactiveNations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inactiveCheck';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks for inactive nations and moves them to a new tax bracket';

    /**
     * The number of MINUTES the nation needs to be inactive to be considered 'inactive status'
     *
     * @var int
     */
    protected $inactiveCutoff = 2880;

    /**
     * Array of BK Nations
     *
     * @var array
     */
    protected $nations;

    /**
     * @var PWClient
     */
    protected $client;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->client = new PWClient();
        $this->client->login();

        $this->getBKNations();
        $this->cycleNations();

        $inactivity = new Inactivity();
        $inactivity->cleanUp();
    }

    /**
     * Gather all BK nation's nIDs
     */
    protected function getBKNations()
    {
        $this->nations = PWFunctions::getAllianceNationIDs(7399);
    }

    /**
     * Run through each nation
     */
    protected function cycleNations()
    {
        foreach ($this->nations as $nID)
        {
            $nation = new Nation($nID);

            if ($nation->alliancePosition == 1) // If applicant, skip
                continue;

            if ($nation->minsInactive >= $this->inactiveCutoff)
            {
                if (! $this->checkIfAlreadyInactive($nID)) // We don't want to set them to inactive status if they're already in inactive status
                    $this->setNationInactive($nation);
            }
        }
    }

    /**
     * Checks if the nation is already set to inactive mode
     * @param int $nID
     * @return bool
     */
    protected function checkIfAlreadyInactive(int $nID) : bool
    {
        $check = Inactivity::where("nID", $nID)
            ->where("isInactive", true)
            ->get();

        if ($check->isEmpty()) // No results which means they're not already in inactive status
            return false;
        else
            return true; // We returned a result which means that they are already in inactive status
    }

    /**
     * Sets a nation as inactive
     *
     * @param Nation $nation
     */
    protected function setNationInactive(Nation $nation)
    {
        Inactivity::setMemberInactive($nation->nID, $nation->leader, $this->client);
    }
}
