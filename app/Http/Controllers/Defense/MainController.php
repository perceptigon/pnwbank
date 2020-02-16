<?php

namespace App\Http\Controllers\Defense;

use App\Defense\DefenseNations;
use App\Models\MMR;
use Auth;
use Gate;
use App\Classes\Output;
use App\Classes\Targets;
use App\Models\Assignment;
use App\Models\Defender;
use App\Models\Attacker;
use App\Models\Belligerent;
use App\Defense\DefenseProfiles;
use App\Defense\DefenseSignin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MainController extends Controller
{
    /**
     * @var Output
     */
    protected $output;

    /**
     * @var Request
     */
    protected $request;

    /**
     * MainController constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->output = new Output();
        $this->request = $request;
    }

    /**
     * Return the GET page for the sign in
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function signIn()
    {
        return view("defense.signin", [
            "output" => $this->output
        ]);
    }

    /**
     * Do the sign in
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function doSignIn()
    {
        $this->validate($this->request, [
            "nID" => "required|integer|min:0",
            "money" => "required|numeric|min:0",
            "food" => "required|integer|min:0",
            "uranium" => "required|integer|min:0",
            "steel" => "required|integer|min:0",
            "gas" => "required|integer|min:0",
            "munitions" => "required|integer|min:0",
            "aluminum" => "required|integer|min:0",
            "discord" => "required|in:yes,no",
            "update.*" => "required|in:never,monday,tuesday,wednesday,thursday,friday,saturday,sunday"
        ]);

        try
        {
            DefenseSignin::doSignIn($this->request);
            $this->output->addSuccess("Thanks! You've signed in successfully");
        }
        catch (\Exception $e)
        {
            $this->output->addError($e->getMessage());
        }

        return $this->signIn();
    }

    public function targets()
    {
        if (Gate::denies("targets"))
            return view("admin.unauthorized", ["page" => "targets"]);

        return view("defense.targets.targets", [
            "page" => 'targets'
        ]);
    }

    public function addTargets(Request $request)
    {
        if (Gate::denies("targets"))
            return view("admin.unauthorized", ["page" => "targets"]);

        Targets::addTargets($request);

        return view("defense.targets.targets", [
            "page" => 'targets'
        ]);
    }

    public function attackers()
    {
        if (Gate::denies("targets"))
            return view("admin.unauthorized", ["page" => "targets"]);

        $attackers = Attacker::all();
        $defenders = Defender::all();

        if (count($attackers) == 0 && count($defenders) == 0)
        {
            Targets::populateAttackers();
            Targets::populateDefenders();
            Targets::getDefendingSlots();
            Targets::getAttackingSlots();
        }

        return view("defense.targets.attackers", [
            "page" => 'targets'
        ]);
    }

    public function attacker($id)
    {
        if (Gate::denies("targets"))
            return view("admin.unauthorized", ["page" => "targets"]);

        return view("defense.targets.attacker", [
            "page" => 'targets'
        ])->with('id', $id);
    }

    public function postAttacker(Request $request)
    {
        if (Gate::denies("targets"))
            return view("admin.unauthorized", ["page" => "targets"]);

        $attacker = Attacker::where('id', $request->attacker_id)->first();
        $defender = Defender::where('id', $request->defender_id)->first();

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
                Targets::assign($request, $attacker, $defender);
            }
        }

        else if($request->assign == 'unassign')
        {
            Targets::unassign($request, $attacker, $defender);
        }

        return view("defense.targets.attacker", [
            "page" => 'targets',
            "output" => $this->output
        ])->with('id', $request->attacker_id);
    }

    //shows all defenders
    public function defenders()
    {
        if (Gate::denies("targets"))
            return view("admin.unauthorized", ["page" => "targets"]);

        $attackers = Attacker::all();
        $defenders = Defender::all();

        if (count($attackers) == 0 && count($defenders) == 0)
        {
            Targets::populateAttackers();
            Targets::populateDefenders();
            Targets::getDefendingSlots();
            Targets::getAttackingSlots();
        }

        return view("defense.targets.defenders", [
            "page" => 'targets'
        ]);
    }

    //shows specific defender
    public function defender($id)
    {
        if (Gate::denies("targets"))
            return view("admin.unauthorized", ["page" => "targets"]);

        return view("defense.targets.defender", [
            "page" => 'targets'
        ])->with('id', $id);
    }

    public function postDefender(Request $request)
    {
        if (Gate::denies("targets"))
            return view("admin.unauthorized", ["page" => "targets"]);

        $defender = Defender::where('id', $request->defender_id)->first();
        $attacker = Attacker::where('id', $request->attacker_id)->first();

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
                Targets::assign($request, $defender, $attacker);
            }
        }

        else if($request->assign == 'unassign')
        {
            Targets::unassign($request, $defender, $attacker);
        }

        return view("defense.targets.defender", [
            "page" => 'targets',
            "output" => $this->output
        ])->with('id', $request->defender_id);
    }

    public function populate()
    {
        if (Gate::denies("targets"))
            return view("admin.unauthorized", ["page" => "targets"]);

        Targets::populateAttackers();
        Targets::populateDefenders();
        Targets::getDefendingSlots();
        Targets::getAttackingSlots();
    }

    public function reset()
    {
        if (Gate::denies("targets"))
            return view("admin.unauthorized", ["page" => "targets"]);

        Assignment::truncate();
        Defender::truncate();
        Attacker::truncate();
        Belligerent::truncate();

        return redirect('defense/targets');

    }

    public function spreadsheet()
    {
        if (Gate::denies("targets"))
            return view("admin.unauthorized", ["page" => "targets"]);

        return view("defense.targets.spreadsheet", [
            "page" => 'targets'
        ]);
    }

    public function message()
    {
        if (Gate::denies("targets"))
            return view("admin.unauthorized", ["page" => "targets"]);

        Targets::messageAttackers();

        $this->output->addSuccess("Target messages have been successfully sent.");

        return redirect('defense/targets');

    }

    public function dashboard()
    {
        if (!Auth::check())
        {
            $this->output->addError('You must be logged into your BKN account to use this page. If you do not have a BKN account, you will need to register.');
            $nation = '';
            $requirements = [];
        }
        else
        {
            $nation = DefenseNations::getNation(Auth::user()->nID);
            $requirements = MMR::orderBy("cityNum", "asc")->get();
            $nationReq = MMR::getCityMMR($nation->cities);
        }

        return view("defense.dashboard", [
            "output" => $this->output,
            "nation" => $nation,
            "requirements" => $requirements,
            "nationReq" => $nationReq
        ]);
    }
}
