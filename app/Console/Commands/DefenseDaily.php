<?php

namespace App\Console\Commands;

use App\Classes\Nation;
use App\Classes\PWFunctions;
use App\Defense\DefenseNationHistory;
use App\Defense\DefenseNations;
use Illuminate\Console\Command;

class DefenseDaily extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'defense:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Daily things that need to run for the defense system';

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
        $this->updateNations();
    }

    protected function updateNations()
    {
        // Get all nation IDs in BK
        $nIDs = PWFunctions::getAllianceNationIDs(877);

        // Set all inBK's in the nations table to false
        DefenseNations::where("inBK", 1)->update(["inBK" => 0]);

        foreach ($nIDs as $nID)
        {
            $nation = new Nation($nID);

            if ($nation->alliancePosition == 1)
                continue;

            $history = new DefenseNationHistory();
            $history->nID = $nation->nID;
            $history->leader = $nation->leader;
            $history->score = $nation->score;
            $history->cities = $nation->cities;
            $history->infra = $nation->infra;
            $history->soldiers = $nation->soldiers;
            $history->tanks = $nation->tanks;
            $history->planes = $nation->aircraft;
            $history->ships = $nation->ships;
            $history->save();

            $n = DefenseNations::updateOrCreate([
                "nID" => $nID,
            ], [
                "leader" => $nation->leader,
                "nation" => $nation->nationName,
                "score" => $nation->score,
                "cities" => $nation->cities,
                "soldiers" => $nation->soldiers,
                "tanks" => $nation->tanks,
                "planes" => $nation->aircraft,
                "ships" => $nation->ships,
                "missiles" => $nation->missiles,
                "nukes" => $nation->nukes,
                "inBK" => true,
            ]);
        }
    }
}
