<?php

namespace App\Console\Commands;

use App\Classes\Wars;
use App\Classes\Forums;
use App\Classes\Nation;
use App\Models\Settings;
use App\Classes\ForumProfile;
use App\Defense\DefenseNations;
use Illuminate\Console\Command;

class CheckNewWars extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'checkWars';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks for new Rothschilds & Co. defensive wars';

    protected $war;

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
        $settings = Settings::getSettings();
        //if ($settings["warMode"] === 1)
        //    return;

        $wars = new Wars(50);

        $runWars = collect([]); // Setup collection

        // Array of the alliances that we monitor
        $alliances = ["Rothschild Family"];
        foreach ($alliances as $alliance)
            $runWars = $runWars->merge($wars->getDefendingWarsByAllianceName($alliance));

        // Now compare if the war ID has been saved
        foreach ($runWars as $war)
        {
            $warDB = \App\Models\Wars::where("warID", $war["warID"])->get();
            $this->war = $war;

            if ($warDB->count() === 0) // If the war isn't in the DB, we haven't reported it yet
            {
                // First, add the war to the DB
                \App\Models\Wars::create([
                    "warID" => $war["warID"],
                ]);

                $this->makePost();
            }
        }
    }

    public function makePost()
    {
        // Get the defender nation object
        $defender = new Nation($this->war["defenderID"]);
        $attacker = new Nation($this->war["attackerID"]);

        echo "Defender Name - $defender->leader \n";
        echo "Attacker Score - $attacker->score | nID $attacker->nID\n";

        $counters = DefenseNations::getNationsInRange($attacker->score);
        foreach ($counters as $counter)// Calculate military scores and get forum profiles for the nations
        {
            $counter->milScore = Nation::staticCalcMilScore($counter->soldiers, $counter->tanks, $counter->planes, 0, 0, 0); // Don't count ships
            try
            {
                $forumProfile = new ForumProfile($counter->nID);
                $forumProfile->getForumProfile();
                $counter->forumProfile = $forumProfile;
            }
            catch (\Exception $e)
            {
                $counter->forumProfile = false;
            }

        }

        // Sort counters
        $sortedCounters = $counters->sortByDesc("milScore");

        $forum = new Forums();

        $forum->createTopic(93, 1775, "Target - {$attacker->leader} ({$attacker->score})", view("balls", [
            "attacker" => $attacker,
            "defender" => $defender,
            "counters" => $sortedCounters,
        ]));
    }
}
