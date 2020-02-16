<?php

namespace App\Console\Commands;

use App\Classes\PWClient;
use App\Defense\DefenseNations;
use App\Defense\DefenseProfiles;
use App\Defense\DefenseSignin;
use App\Models\Accounts;
use App\Models\Inactivity;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SignInReset extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'defense:signinreset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset the sign ins';

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
        $this->resetProfiles();
        $this->resetNations();
        $this->autoSignIn();
    }

    /**
     * Perform the auto sign in. Super messy but I really don't give a fuck.
     */
    protected function autoSignIn()
    {
        $nations = $this->callAPI();

        foreach ($nations as $nation)
        {
            // Check for applicant. The API doesn't include applicants, but in case it does in the future we'll verify here
            if ($nation->allianceposition == 1)
                continue;

            // Check for vacation mode
            if ($nation->vacmode > 0)
                continue;

            // Check if they allow us to see their shit
            if ($nation->money == -1)
            {
                $this->fuckThisGuy($nation->leader, $nation->nationid);
                continue;
            }

            $profile = DefenseProfiles::getProfile($nation->nationid);

            // We need to get the nation's stuff in their accounts
            $accounts = Accounts::where("nID", $nation->nationid);

            $signin = new DefenseSignin();
            $signin->leader = $nation->leader;
            $signin->nation = $nation->nation;
            $signin->score = $nation->score;
            $signin->money = $nation->money + $accounts->sum("money");
            $signin->steel = $nation->steel + $accounts->sum("steel");
            $signin->munitions = $nation->munitions + $accounts->sum("munitions");
            $signin->gas = $nation->gasoline + $accounts->sum("gas");
            $signin->aluminum = $nation->aluminum + $accounts->sum("aluminum");
            $signin->nationID = $nation->nationid;
            $signin->irc = 0;
            $signin->updateDays = \serialize(["never"]);
            $signin->food = $nation->food + $accounts->sum("food");
            $signin->uranium = $nation->uranium + $accounts->sum("uranium");
            $signin->spies = $nation->spies;
            $signin->save();

            // Update profile
            $profile->lastSignIn = Carbon::now();
            $profile->hasSignedIn = 1;
            $profile->inBK = 1;
            $profile->save();

            // Update nations
            $defNation = DefenseNations::getNation($nation->nationid);
            $defNation->nID = $nation->nationid;
            $defNation->leader = $nation->leader;
            $defNation->nation = $nation->nation;
            $defNation->score = $nation->score;
            $defNation->cities = $nation->cities;
            $defNation->soldiers = $nation->soldiers;
            $defNation->tanks = $nation->tanks;
            $defNation->planes = $nation->aircraft;
            $defNation->ships = $nation->ships;
            $defNation->missiles = $nation->missiles;
            $defNation->nukes = $nation->nukes;
            $defNation->steel = $nation->steel + $accounts->sum("steel");
            $defNation->gas = $nation->gasoline + $accounts->sum("gas");
            $defNation->aluminum = $nation->aluminum + $accounts->sum("aluminum");
            $defNation->munitions = $nation->munitions + $accounts->sum("munitions");
            $defNation->money = $nation->money + $accounts->sum("money");
            $defNation->food = $nation->food + $accounts->sum("food");
            $defNation->uranium = $nation->uranium + $accounts->sum("uranium");
            $defNation->spies = $nation->spies;
            $defNation->inBK = true;
            $defNation->hasSignedIn = 1;
            $defNation->save();

        }
    }

    /**
     * Calls the alliance member's API and returns an array of all nations in the alliance. If it fails, it'll throw an exception
     *
     * @return array
     * @throws \Exception
     */
    protected function callAPI() : array
    {
        $json = \json_decode(file_get_contents("http://politicsandwar.com/api/alliance-members/?allianceid=4937&key=".env("PW_API_KEY")));

        if ($json->success == false)
            throw new \Exception($json->general_message);

        return $json->nations;
    }

    /**
     * For the fuckers who don't share their resources with us
     *
     * @param string $leader
     */
    protected function fuckThisGuy(string $leader, int $nID)
    {
        // Send the ass hole a message
        $message = "Hi {$leader},\n\nWe attempted to perform your sign-in automatically, however, you've opted to not share your resource values with us. If you would like to help us out and never have to sign-in again, please go [link=https://politicsandwar.com/account/]here[/link] and tick the \"Enable Alliance Information Access\". It is alliance policy to share your information.\n\nIn the mean time, please go [link=https://bkpw.net/defense/signin]here[/link] to perform your sign-in manually. If you do not sign in, you will not be eligible for any economic programs. Also remember that lying on your sign-in will only hurt you, so make sure you're honest.\n\nIn addition, due to you not sharing your resources with the Government, we have moved you to a higher tax bracket. If you change your settings to allow us to see your resources, contact a Government member to bring you back in the correct tax bracket.";

        // Check if the member is already a badboi (is inactive). If they are, let's not move them to a lower tax bracket lol
        if (! Inactivity::isMemberInactive($nID))
            $this->client->modifyMemberTaxBracket($nID,985);

        $this->client->sendMessage($leader, "Sign-ins Have Reset", $message);
    }

    /**
     * Reset profiles to indicate no one has signed in
     */
    protected function resetProfiles()
    {
        DefenseProfiles::where("hasSignedIn", true)->update(["hasSignedIn" => false]);
    }

    /**
     * Update the nations table to show that no one has signed in
     */
    protected function resetNations()
    {
        DefenseNations::where("hasSignedIn", true)->update(["hasSignedIn" => false]);
    }
}
