<?php
/**
 * Created by PhpStorm.
 * User: shane
 * Date: 2/25/17
 * Time: 12:47 PM
 */

namespace App\Classes;

use App\Models\Attacker;
use App\Models\Defender;
use App\Models\Belligerent;
use App\Models\Assignment;
use App\Classes\Output;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\Assign;


class Targets
{
    public static function populateDefenders()
    {
        $belligerents = Belligerent::where('is_attacking', 0)->get();

        foreach ($belligerents as $belligerent)
        {

            $nIDs = PWFunctions::getAllianceNationIDs($belligerent->aID);

            foreach ($nIDs as $nID)
            {
                $nation = new Nation($nID);

                if (!($nation->alliancePosition > 1)) continue;
                if ($nation->minsInactive > 10080) continue;

                $defender = new Defender;

                $defender->nID = $nID;
                $defender->nName = $nation->nationName;
                $defender->nRuler = $nation->leader;
                $defender->soldiers = $nation->soldiers;
                $defender->tanks = $nation->tanks;
                $defender->aircraft = $nation->aircraft;
                $defender->ships = $nation->ships;
                $defender->alliance = $nation->alliance;
                $defender->cities = $nation->cities;
                $defender->score = $nation->score;
                $defender->slots = 3;

                $defender->save();
            }
        }
    }

    public static function populateAttackers()
    {
        $belligerents = Belligerent::where('is_attacking', 1)->get();

        foreach ($belligerents as $belligerent)
        {

            $nIDs = PWFunctions::getAllianceNationIDs($belligerent->aID);

            foreach ($nIDs as $nID)
            {
                $nation = new Nation($nID);

                if (!($nation->alliancePosition > 1)) continue;
                //if ($nation->minsInactive > 10080) continue;

                $attacker = new Attacker;

                $attacker->nID = $nID;
                $attacker->nName = $nation->nationName;
                $attacker->nRuler = $nation->leader;
                $attacker->soldiers = $nation->soldiers;
                $attacker->tanks = $nation->tanks;
                $attacker->aircraft = $nation->aircraft;
                $attacker->ships = $nation->ships;
                $attacker->alliance = $nation->alliance;
                $attacker->cities = $nation->cities;
                $attacker->score = $nation->score;
                $attacker->nrf = $nation->nuclearResFacility;
                $attacker->slots = 5;

                $attacker->save();
            }
        }
    }

    public static function addTargets(Request $request)
    {
        if ($request->submit == "delete")
        {
            $belligerent = Belligerent::where('id', $request->status)->first();
            $belligerent->delete();
            return;
        }

        $belligerent = new Belligerent;
        $belligerent->aID = $request->alliance_id;
        $belligerent->is_attacking = $request->status;

        $alliance = new PWClient();
        $alliance = $alliance->getPage('https://politicsandwar.com/api/alliance/id=' . $belligerent->aID . '&key=' . env("PW_API_KEY"));
        $alliance = json_decode($alliance);
        $belligerent->aName = $alliance->name;

        $belligerent->save();
    }

    public static function getDefendingSlots()
    {
        $belligerents = Belligerent::where('is_attacking', 0)->get();

        foreach ($belligerents as $belligerent)
        {
            $wars = new Wars(3000);
            $wars = $wars->getWarsByAllianceName($belligerent->aName);

            foreach ($wars as $war)
            {
                $defender = Defender::where('nID', $war['defenderID'])->first();

                if ($war['status'] != 'Active') continue;
                if (count($defender) == 0) continue;

                $defender->slots = $defender->slots - 1;
                $defender->save();
            }
        }
    }

    public static function getAttackingSlots()
    {
        $belligerents = Belligerent::where('is_attacking', 1)->get();

        foreach ($belligerents as $belligerent)
        {
            $wars = new Wars(3000);
            $wars = $wars->getWarsByAllianceName($belligerent->aName);

            foreach ($wars as $war)
            {

                $attacker = Attacker::where('nID', $war['attackerID'])->first();

                if ($war['status'] != 'Active') continue;
                if (count($attacker) == 0) continue;

                $attacker->slots = $attacker->slots - 1;
                $attacker->save();

            }
        }
    }

    public static function assign(Request $request, $nation1, $nation2)
    {
        $assignment = new Assignment;
        $assignment->attacker_id = $request->attacker_id;
        $assignment->defender_id = $request->defender_id;
        $assignment->save();

        $nation1->slots = ($nation1->slots - 1);
        $nation1->save();

        $nation2->slots = ($nation2->slots - 1);
        $nation2->save();
    }

    public static function unassign(Request $request, $nation1, $nation2)
    {
        $assignment = Assignment::where('attacker_id', $request->attacker_id)->get();
        $assignment = $assignment->where('defender_id', $request->defender_id)->first();
        $assignment->delete();

        $nation1->slots = $nation1->slots + 1;
        $nation1->save();

        $nation2->slots = $nation2->slots + 1;
        $nation2->save();
    }

    public static function messageAttackers()
    {
        if (\App\Models\Settings::where("sKey", "targetTestMode")->firstOrFail()->value == 1) return;

        $attackers = Attacker::all();

        $client = new PWClient();
        $client->login();

        foreach ($attackers as $attacker)
        {
            $assignments = Assignment::where('attacker_id', $attacker->id)->get();

            if (count($assignments) == 0) continue;

            $message = "$attacker->nRuler,
            
            Below are your targets.
            
            ";

            foreach ($assignments as $assignment)
            {
                $mates = Assignment::where('defender_id', $assignment->defender_id)->get();
                $mates = $mates->where('attacker_id', '!=', $assignment->attacker_id);

                $message = $message . $assignment->defender->nName . ": https://politicsandwar.com/nation/id=" . $assignment->defender->nID;

                $total = count($mates);
                $count = 0;

                if ($total != 0)
                {
                    $message = $message . ", attacking with: ";
                }

                foreach ($mates as $mate)
                {
                    $count++;

                    $message = $message . $mate->attacker->nRuler;

                    if ($count != $total) $message = $message . ", ";
                    else $message = $message . ".\n";
                }
            }

            $client->sendMessage($attacker->nRuler, "TARGETS", $message);
        }
    }

    public static function spreadsheetExport() : string
    {
        $defenders = Defender::all();

        $string = "Target,Attacker 1,Attacker 2,Attacker 3\n";

        foreach ($defenders as $defender)
        {
            $assignments = $defender->assignments;

            $string .= "\"=HYPERLINK(\"\"https://politicsandwar.com/nation/id=$defender->nID\"\",\"\"$defender->nName\"\")\"";

            foreach ($assignments as $assignment)
            {
                $string .= "," . $assignment->attacker->nRuler;
            }

            $string .= "\n";
        }

        return $string;
    }

}