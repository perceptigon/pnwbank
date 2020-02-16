<?php

namespace App\Http\Controllers\Defense;

use Auth;
use Gate;
use App\Classes\Nation;
use App\Models\Defense\spyParameter;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Classes\Spies;
use App\Classes\Output;
use App\Models\Defense\spyAttacker;
use App\Models\Defense\spyDefender;
use App\Models\Defense\spyBelligerent;
use App\Models\Defense\spyAssignment;

class SpyController extends Controller
{
    protected $output;

    public function __construct(Request $request)
    {
        $this->output = new Output();
        $this->request = $request;
    }

    public function spies()
    {
        if (Gate::denies("targets"))
            return view("admin.unauthorized", ["page" => "targets"]);

        return view("defense.spies.spies", [
            "page" => 'spies'
        ]);
    }

    public function addSpies(Request $request)
    {
        Spies::addSpies($request);

        return view("defense.spies.spies", [
            "page" => 'spies'
        ]);
    }

    public function attackers()
    {
        $attackers = spyAttacker::all();
        $defenders = spyDefender::all();

        if (count($attackers) == 0 && count($defenders) == 0)
        {
            Spies::populateAttackers();
            Spies::populateDefenders();
        }

        return view("defense.spies.spy_attackers", [
            "page" => 'spies'
        ]);
    }

    public function attacker($id)
    {
        return view("defense.spies.spy_attacker", [
            "page" => 'spies'
        ])->with('id', $id);
    }

    public function postAttacker(Request $request)
    {
        $attacker = spyAttacker::where('id', $request->attacker_id)->first();
        $defender = spyDefender::where('id', $request->defender_id)->first();

        if ($request->assign == 'assign')
        {

            if ($attacker->slots == 0)
            {
                $this->output->addError('This defender has no more slots available');
            }
            elseif ($defender->slots == 0)
            {
                $this->output->addError('That defender has no more slots available and cannot be assigned.');
            }
            else
            {
                Spies::assign($request, $attacker, $defender);
            }
        }

        else if($request->assign == 'unassign')
        {
            Spies::unassign($request, $attacker, $defender);
        }

        return view("defense.spies.spy_attacker", [
            "page" => 'spies',
            "output" => $this->output
        ])->with('id', $request->attacker_id);
    }

    public function defenders()
    {
        $attackers = spyAttacker::all();
        $defenders = spyDefender::all();

        if (count($attackers) == 0 && count($defenders) == 0)
        {
            Spies::populateAttackers();
            Spies::populateDefenders();
        }

        return view("defense.spies.spy_defenders", [
            "page" => 'spies'
        ]);
    }

    public function defender($id)
    {
        return view("defense.spies.spy_defender", [
            "page" => 'spies'
        ])->with('id', $id);
    }

    public function postDefender(Request $request)
    {
        $defender = spyDefender::where('id', $request->defender_id)->first();
        $attacker = spyAttacker::where('id', $request->attacker_id)->first();

        if ($request->assign == 'assign')
        {
            if ($defender->slots == 0)
            {
                $this->output->addError('This defender has no more slots available.');
            }
            elseif ($attacker->slots == 0)
            {
                $this->output->addError('That attacker has no slots available and cannot be assigned.');
            }
            else
            {
                Spies::assign($request, $defender, $attacker);
            }
        }

        else if($request->assign == 'unassign')
        {
            Spies::unassign($request, $defender, $attacker);
        }

        return view("defense.spies.spy_defender", [
            "page" => 'spies',
            "output" => $this->output
        ])->with('id', $request->defender_id);
    }

    public function reset()
    {
        spyAssignment::truncate();
        spyDefender::truncate();
        spyAttacker::truncate();

        $round = spyParameter::where('name', 'round')->first();
        $round->value = 1;
        $round->save();

        $defenders = spyBelligerent::where('is_attacking', 0)->get();
        foreach ($defenders as $defender) $defender->delete();

        return redirect('defense/spies');
    }

    public function refresh()
    {
        Spies::refreshAttackers();
        Spies::refreshDefenders();

        $outOfRanges = Spies::outOfRange();

        if ($outOfRanges->count() > 0)
        {
            return view("defense.spies.out_of_range", [
                "page" => 'spies'
            ])->with('outOfRanges', $outOfRanges);
        }

        return redirect('defense/spies');
    }

    public function nextRound()
    {
        $round = spyParameter::where('name', 'round')->first();
        $round->value += 1;
        $round->save();

        $attackers = spyAttacker::all();
        foreach ($attackers as $attacker)
        {
            if ($attacker->cia) $attacker->slots = 2;
            else $attacker->slots = 1;
            $attacker->save();
        }

        $defenders = spyDefender::all();
        foreach ($defenders as $defender)
        {
            $defender->slots = 3;
            $defender->save();
        }

        return redirect('defense/spies');
    }

    public function attackResults($id)
    {
        return view("defense.spies.spy_attacker_results", [
            "page" => 'spies'
        ])->with('id', $id);
    }

    public function defendResults($id)
    {
        return view("defense.spies.spy_defender_results", [
            "page" => 'spies'
        ])->with('id', $id);
    }

    public function resultsSubmit()
    {
        return view("defense.spies.spy_results_submit", [
            "page" => 'spies'
        ]);
    }

    public function results()
    {
        return view("defense.spies.results", [
            "page" => 'spies'
        ]);
    }
    
    public function spreadsheet()
    {
        return view("defense.spies.spy_spreadsheet", [
            "page" => 'spies'
        ]);
    }

    public function postResultsSubmit(Request $request)
    {
        try
        {
            $assignment = spyAssignment::where('id', $request->aID)->firstOrFail();

            if ($assignment->attacker->nID != $request->nID)
            {
                $this->output->addError('Assignment ID and Nation ID do not match.');
            }
            else if (!is_null($assignment->results))
            {
                $this->output->addError('Results have already been submitted for that assignment');
            }
            else
            {
                $this->output->addSuccess('Your result submission has been accepted.');
                Spies::parseResults($request);
            }
        }
        catch (ModelNotFoundException $e)
        {
            $this->output->addError('There is no assignment with that assignment ID');
        }

        return view("defense.spies.spy_results_submit", [
            "page" => 'spies',
            "output" => $this->output
        ]);
    }

    public function message()
    {
        Spies::messageAttackers();

        $this->output->addSuccess("Target messages have been successfully sent.");

        return redirect('defense/spies');
    }
}
