<?php

namespace App\Classes;

use App\Exceptions\UserErrorException;
use App\Models\Defense\spyBelligerent;
use App\Models\Defense\spyAttacker;
use App\Models\Defense\spyDefender;
use App\Models\Defense\spyAssignment;
use App\Models\Defense\spyParameter;
use App\Models\BKNation;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class Spies
{
    public static function populateDefenders()
    {
        $belligerents = spyBelligerent::where('is_attacking', 0)->get();

        foreach ($belligerents as $belligerent)
        {
            $nIDs = PWFunctions::getAllianceNationIDs($belligerent->aID);

            foreach ($nIDs as $nID)
            {
                $nation = new Nation($nID);

                if (!($nation->alliancePosition > 1)) continue;
                if ($nation->minsInactive > 10080) continue;

                $defender = new spyDefender;

                $defender->nID = $nID;
                $defender->nName = $nation->nationName;
                $defender->nRuler = $nation->leader;
                $defender->soldiers = $nation->soldiers;
                $defender->tanks = $nation->tanks;
                $defender->aircraft = $nation->aircraft;
                $defender->ships = $nation->ships;
                $defender->nukes = $nation->nukes;
                $defender->missiles = $nation->missiles;
                $defender->alliance = $nation->alliance;
                $defender->lastActive = $nation->minsInactive;
                $defender->warPolicy = $nation->warPolicy;
                $defender->score = $nation->score;
                $defender->slots = 3;

                //check for CIA to determine max number of spies
                if ($nation->intelAgency)
                {
                    $defender->maxSpies = 60;
                    $defender->cia = true;
                }
                else
                {
                    $defender->maxSpies = 50;
                    $defender->cia = false;
                }

                $defender->save();
            }
        }
    }

    public static function populateAttackers()
    {
        $belligerents = spyBelligerent::where('is_attacking', 1)->get();

        foreach ($belligerents as $belligerent)
        {
            $nIDs = PWFunctions::getAllianceNationIDs($belligerent->aID);

            foreach ($nIDs as $nID)
            {
                $nation = new Nation($nID);

                if (!($nation->alliancePosition > 1)) continue;
                if ($nation->minsInactive > 10080) continue;

                $attacker = new spyAttacker;

                $attacker->nID = $nID;
                $attacker->nName = $nation->nationName;
                $attacker->nRuler = $nation->leader;
                $attacker->soldiers = $nation->soldiers;
                $attacker->tanks = $nation->tanks;
                $attacker->aircraft = $nation->aircraft;
                $attacker->ships = $nation->ships;
                $attacker->alliance = $nation->alliance;
                $attacker->score = $nation->score;
                $attacker->lastActive = $nation->minsInactive;
                $attacker->warPolicy = $nation->warPolicy;
                $attacker->slots = 3;

                if ($nation->intelAgency)
                {
                    $attacker->slots = 2;
                    $attacker->cia = true;
                }
                else
                {
                    $attacker->slots = 1;
                    $attacker->cia = false;
                }

                //get spies from MMR sign-ins
                try
                {
                    $member = BKNation::where('nID', $attacker->nID)->firstOrFail();
                    if (is_null($member->spies)) $attacker->spies = 0;
                    else $attacker->spies = $member->spies;
                    $attacker->save();
                }
                catch (ModelNotFoundException $e)
                {
                    continue;
                }
            }
        }
    }

    public static function refreshDefenders()
    {
        $defenders = spyDefender::all();

        foreach ($defenders as $defender)
        {
            try
            {
                $nation = new Nation($defender->nID);

                $defender->soldiers = $nation->soldiers;
                $defender->tanks = $nation->tanks;
                $defender->aircraft = $nation->aircraft;
                $defender->ships = $nation->ships;
                $defender->nukes = $nation->nukes;
                $defender->score = $nation->score;

                $defender->save();
            }

            catch (UserErrorException $e)
            {
                $defender->delete();
            }
        }
    }

    public static function refreshAttackers()
    {
        $attackers = spyAttacker::all();

        foreach ($attackers as $attacker)
        {
            try
            {
                $nation = new Nation($attacker->nID);

                $attacker->soldiers = $nation->soldiers;
                $attacker->tanks = $nation->tanks;
                $attacker->aircraft = $nation->aircraft;
                $attacker->ships = $nation->ships;
                $attacker->score = $nation->score;

                $attacker->save();
            }

            catch (UserErrorException $e)
            {
                $attacker->delete();
            }
        }
    }

    public static function outOfRange()
    {
        $assignments = spyAssignment::all();
        $round = spyParameter::where('name', 'round')->first();

        $assignments = $assignments->where('round', $round->value);

        foreach ($assignments as $assignment)
        {
            $min = $assignment->attacker->score * 0.40;
            $max = $assignment->attacker->score * 2.50;
            if ($min > $assignment->defender->score || $max < $assignment->defender->score)
            {
                $assignments->forget($assignment->id);
            }
        }

        return $assignments;
    }

    public static function addSpies(Request $request)
    {
        if ($request->submit == "delete")
        {
            $belligerent = spyBelligerent::where('id', $request->status)->first();
            $belligerent->delete();
            return;
        }

        $belligerent = new spyBelligerent;
        $belligerent->aID = $request->alliance_id;
        $belligerent->is_attacking = $request->status;

        $alliance = new PWClient();
        $alliance = $alliance->getPage('https://politicsandwar.com/api/alliance/id=' . $belligerent->aID . '&key=' . env("PW_API_KEY"));
        $alliance = json_decode($alliance);
        $belligerent->aName = $alliance->name;

        $belligerent->save();

    }

    public static function assign(Request $request, $nation1, $nation2)
    {
        $assignment = new spyAssignment;
        $assignment->attacker_id = $request->attacker_id;
        $assignment->defender_id = $request->defender_id;
        $assignment->type = $request->type;

        //set round
        $round = spyParameter::where('name', 'round')->first();
        $assignment->round = $round->value;

        $assignment->sent = false;
        $assignment->save();

        $nation1->slots = ($nation1->slots - 1);
        $nation1->save();

        $nation2->slots = ($nation2->slots - 1);
        $nation2->save();
    }

    public static function unassign(Request $request, $nation1, $nation2)
    {
        $assignment = spyAssignment::where('attacker_id', $request->attacker_id)->get();
        $assignment = $assignment->where('defender_id', $request->defender_id)->first();
        $assignment->delete();

        $nation1->slots = $nation1->slots + 1;
        $nation1->save();

        $nation2->slots = $nation2->slots + 1;
        $nation2->save();
    }

    public static function messageAttackers()
    {
        if (\App\Models\Settings::where("sKey", "spyTestMode")->firstOrFail()->value == 1) return;

        $attackers = spyAttacker::all();

        $client = new PWClient();
        $client->login();

        foreach ($attackers as $attacker)
        {
            $assignments = spyAssignment::where('attacker_id', $attacker->id)->get();
            $assignments = $assignments->where('sent', false);

            if (count($assignments) == 0) continue;

            $message = "$attacker->nRuler,
            
            Please complete the following operations:
            
            ";

            foreach ($assignments as $assignment)
            {
                switch ($assignment->type)
                {
                    case 1:
                        $message .= "[b]Gather Intelligence[/b] on [link=https://politicsandwar.com/nation/id=" . $assignment->defender->nID . "]" . $assignment->defender->nName . "[/link]\n";
                        break;
                    case 2:
                        $message .= "[b]Assassinate Spies[/b] on [link=https://politicsandwar.com/nation/id=" . $assignment->defender->nID . "]" . $assignment->defender->nName . "[/link]\n";
                        break;
                    case 3:
                        $message .= "[b]Terrorize Civilians[/b] on [link=https://politicsandwar.com/nation/id=" . $assignment->defender->nID . "]" . $assignment->defender->nName . "[/link]\n";
                        break;
                    case 4:
                        $message .= "[b]Sabotage Soldiers[/b] on [link=https://politicsandwar.com/nation/id=" . $assignment->defender->nID . "]" . $assignment->defender->nName . "[/link]\n";
                        break;
                    case 5:
                        $message .= "[b]Sabotage Tanks[/b] on [link=https://politicsandwar.com/nation/id=" . $assignment->defender->nID . "]" . $assignment->defender->nName . "[/link]\n";
                        break;
                    case 6:
                        $message .= "[b]Sabotage Aircraft[/b] on [link=https://politicsandwar.com/nation/id=" . $assignment->defender->nID . "]" . $assignment->defender->nName . "[/link]\n";
                        break;
                    case 7:
                        $message .= "[b]Sabotage Ships[/b] on [link=https://politicsandwar.com/nation/id=" . $assignment->defender->nID . "]" . $assignment->defender->nName . "[/link]\n";
                        break;
                    case 8:
                        $message .= "[b]Sabotage Missiles[/b] on [link=https://politicsandwar.com/nation/id=" . $assignment->defender->nID . "]" . $assignment->defender->nName . "[/link]\n";
                        break;
                    case 9:
                        $message .= "[b]Sabotage Nukes[/b] on [link=https://politicsandwar.com/nation/id=" . $assignment->defender->nID . "]" . $assignment->defender->nName . "[/link]\n";
                        break;
                }
                $message .= "[link=http://bank.blackbird.im/defense/spies/results/submit?nID=$attacker->nID&aID=$assignment->id]Submit Results[/link], Assignment ID: $assignment->id\n\n";
                $assignment->sent = true;
                $assignment->save();
            }

            $message .= "After completing the assignments, please submit your results by clicking the \"Submit Results\" link. Everything should be prefilled for you except results, but in case it isn't, the assignment ID is listed.\n";
            $message .= "If you are confused about the system, or spying in general, please take a look at the [link=https://dev.bkpw.net/topic/7855-guide-war-time-spying/]guide on the forums[/link]. If you have an problems, contact Darth_Freer or Tiber on Discord.";

            $client->sendMessage($attacker->nRuler, "Spy Operation Order", $message);
        }
    }

    public static function spreadsheetExport() : string
    {
        $assignments = spyAssignment::all();

        $string = "Target,Alliance,Round,Attacker,Operation,Results\n";

        foreach ($assignments as $assignment)
        {
            $string .= $assignment->defender->nRuler;
            $string .= "," . $assignment->defender->alliance;
            $string .= "," . "$assignment->round";
            $string .= "," . $assignment->attacker->nRuler;

            switch ($assignment->type)
            {
                case 1:
                    $string .= "," . "Gather Intelligence";
                    break;
                case 2:
                    $string .= "," . "Assassinate Spies";
                    break;
                case 3:
                    $string .= "," . "Terrorize Civilians";
                    break;
                case 4:
                    $string .= "," . "Sabotage Soldiers";
                    break;
                case 5:
                    $string .= "," . "Sabotage Tanks";
                    break;
                case 6:
                    $string .= "," . "Sabotage Aircraft";
                    break;
                case 7:
                    $string .= "," . "Sabotage Ships";
                    break;
                case 8:
                    $string .= "," . "Sabotage Missiles";
                    break;
                case 9:
                    $string .= "," . "Sabotage Nukes";
                    break;
            }
            $string .= ",\"" . $assignment->results . "\"";
            $string .= "\n";
        }

        return $string;
    }

    public static function parseResults(Request $request)
    {
        $assignment = spyAssignment::where('id', $request->aID)->first();

        if (strpos($request->results, 'successfully gathered intelligence'))
        {
            $assignment->success = true;
            $assignment->results = $request->results;

            if (strpos($request->results, 'captured and executed'))
            {
                $assignment->attacker->spies = $assignment->attacker->spies - self::getCapturedSpies($request->results);
                $assignment->attacker->save();
                $assignment->success = 2;
            }

            $assignment->save();
        }

        elseif (strpos($request->results, 'successfully assassinated'))
        {
            $assignment->success = true;
            $assignment->results = $request->results;

            $assignment->defender->maxSpies = $assignment->defender->maxSpies - self::getAssassinatedSpies($request->results);
            $assignment->defender->save();

            if (strpos($request->results, 'captured and executed'))
            {
                $assignment->attacker->spies = $assignment->attacker->spies - self::getCapturedSpies($request->results);
                $assignment->attacker->save();
            }

            $assignment->save();

        }

        elseif (strpos($request->results, 'You successfully detonated an explosive'))
        {
            $assignment->success = true;
            $assignment->results = $request->results;

            if (strpos($request->results, 'captured and executed'))
            {
                $assignment->attacker->spies = $assignment->attacker->spies - self::getCapturedSpies($request->results);
                $assignment->attacker->save();
            }

            $assignment->save();

        }

        elseif (strpos($request->results, 'unable to sabotage') || (strpos($request->results, 'unable to assassinate')) || strpos($request->results, 'unsuccessful in gathering intelligence'))
        {
            $assignment->success = false;
            $assignment->results = $request->results;

            if (strpos($request->results, 'captured and executed'))
            {
                $assignment->attacker->spies = $assignment->attacker->spies - self::getCapturedSpies($request->results);
                $assignment->attacker->save();
            }

            $assignment->save();

        }

        return true;
    }

    public static function getCapturedSpies(string $results)
    {
        $pos = strpos($results, 'The operation cost');
        $killed = substr($results, $pos);

        $pos = strpos($killed, 'and');
        $killed = substr($killed, $pos);

        $pos = strpos($killed, ' ');
        $killed = substr($killed, $pos + 1);

        $pos = strpos($killed, ' ');
        $killed = substr($killed, 0, $pos);

        return $killed;
    }

    public static function getAssassinatedSpies(string $results)
    {
        $pos = strpos($results, 'Your spies killed');
        $killed = substr($results, $pos);

        $pos = strpos($killed, 'killed');
        $killed = substr($killed, $pos);

        $pos = strpos($killed, ' ');
        $killed = substr($killed, $pos + 1);

        $pos = strpos($killed, ' ');
        $killed = substr($killed, 0, $pos);

        return $killed;
    }

}
