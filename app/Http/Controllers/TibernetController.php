<?php

namespace App\Http\Controllers;

use App\Classes\PWClient;
use App\Models\Noob;
use App\Classes\Forums;
use App\Classes\Nation;
use App\Classes\Output;
use App\Classes\Tibernet;
use Illuminate\Http\Request;
use App;

class TibernetController extends Controller
{
    protected $output;

    public function __construct()
    {
        $this->output = new Output();
    }

    /**
     * Return Tibernet's homepage.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function home()
    {
        return view('ia/home');
    }

    /**
     * Return the application page.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function apply()
    {
        return view('ia/apply')->with('posted', 'default');
    }

    /**
     * Send the application.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function postApply(Request $request)
    {
        $this->validate($request, [
           "nation_ID" => "required|integer",
            "prev_alliances" => "required",
            "pissed_off" => "required|in:yes,no",
            "skills" => "required",
            "MMR" => "required|in:yes,no",
        ]);

        try
        {
            $nation = new Nation($request->nation_ID);

            $forum_id = Tibernet::getForumID($request->nation_ID);

            //check for no account
            if ($forum_id == -1)
            {
                $this->output->addError("There is no forum account associated with that nation id. Please make sure that the nation id you entered is correct, and make sure you have both created an account on the forums, and that you entered the proper nation id into your forum account's profile.");
                return view('ia/apply')->with(['output' => $this->output]);
            }

            $title = $nation->leader.'\'s Application';

            if (App::environment('local')) // If local env, then append this to the title
                $title .= " (TIBERNET TEST)";

            $post = "<p>
	             <strong>Nation Name:</strong> $nation->nationName<br>
	            <strong>Nation Link:</strong> <a href=\"https://politicsandwar.com/nation/id=$request->nation_ID\" rel=\"external nofollow\">https://politicsandwar.com/nation/id=$request->nation_ID</a><br>
	            <strong>Previous alliances and Previous Positions:</strong> $request->prev_alliances<br>
	            <strong>Have you done something to piss someone off in P&amp;W?:</strong> $request->pissed_off<br>
                <strong>What skills can you offer Cam?:</strong> $request->skills<br>
	            <strong>We are a military alliance, which means you will be required to stockpile a warchest (stockpile of resources and money) which will cut into your growth, are you okay with this?:</strong> $request->MMR
                </p>";

            $forums = new Forums;

            $forum_member = $forums->getMember($forum_id);
            $forum_member = json_decode($forum_member);
            $forum_name = $forum_member->name;

            $status = Tibernet::status($forum_name, $forum_id, $nation);

            if ($status == 'posted')
            {
                if (App::environment('local'))
                    $forum = 356; // If local env, post to Yoso's Playground
                else
                    $forum = 4; // If not, post to the application center

                $forums->createTopic($forum, $forum_id, $title, $post);
                Tibernet::addNoob($request->nation_ID, $nation->nationName, $nation->leader, $forum_id, $forum_name);
            }

            $this->determineApplyMessage($status);
        }
        catch (\Exception $e)
        {
            $this->output->addError($e->getMessage()); // Not the best thing to do but idc
        }

        return view('ia/apply')->with(['output' => $this->output]);
    }

    /**
     * Determines what message we should show after the application applied.
     *
     * @param string $status
     */
    protected function determineApplyMessage(string $status)
    {
        switch ($status)
        {
            case "not_bk":
                $this->output->addError("The nation you have entered is not a BK applicant. Please join BK in-game by going <a href=\"https://politicsandwar.com/alliance/id=4937\">here</a>, and try again.");
                break;
            case "no_account":
                $this->output->addError("There is no forum account associated with that nation id. Please make sure that the nation id you entered is correct, and make sure you have both created an account on the forums, and that you entered the proper nation id into your forum account's profile.");
                break;
            case "too_old":
                $this->output->addError("For secruity reasons, that nation is not eligible to be submitted through Tibernet. If you have entered an incorrect nation id, please correct it. If you have entered the correct nation id, then please apply via the alternate application process.");
                break;
            case "posted":
                $this->output->addSuccess("Thank you. Your application has been submitted and posted on the forums. You can see it <a href=\"https://bkpw.net/forum/4-application-center\">here</a>. Make sure to stay active and keep checking the post, because you will be asked questions in there that you need to answer before being accepted.</p>"); // TODO you could probably link directly to their new application. The topic ID is returned in the API call iirc
                break;
            default:
                $this->output->addError("Couldn't determine message");
        }
    }

    public function applicants()
    {
        return view('ia/applicants', [
            "page" => "applicants",
        ])->with('status', 'default');
    }

    public function postApplicants(Request $request)
    {
        $noob = Noob::where('nation_id', $request->nation_id)->first();

        $this->output->addSuccess("{$noob->nation_ruler} has been deleted from the database and removed from the alliance. Don't forget to unmask them.");

        $pw = new PWClient();
        $pw->login();
        $pw->removeMember($noob->nation_ruler);
        $noob->delete();

        return view('ia/applicants', [
            "page" => "applicants",
            "output" => $this->output,
        ]);
    }

    public function academy()
    {
        return view('ia/academy', [
            "page" => "academy",
            "output" => $this->output,
            "status" => 'default',
        ]);
    }

    public function postAcademy(Request $request)
    {
        $noob = Noob::where('nation_id', $request->nation_id)->first();
        $pw = new PWClient();
        $pw->login();
        $pw->removeMember($noob->nation_ruler);
        $noob->delete();

        return view('ia/academy', [
            "page" => "academy",
        ])->with('status', $noob->forum_name);
    }

    public function track()
    {
        return view('ia/track', [
            "page" => "track",
        ])->with('status', 'default');
    }

    public function postTrack(Request $request)
    {
        $noob = Noob::where('nation_id', $request->nation_id)->first();
        $pw = new PWClient();
        $pw->login();
        $pw->removeMember($noob->nation_ruler);
        $noob->delete();

        return view('ia/track', [
            "page" => "track",
        ])->with('status', $noob->forum_name);
    }

    public function notes($noob)
    {
        return view('ia/notes', [
            "page" => "notes",
        ])->with('noob_id', $noob);
    }

    public function postNotes(Request $request)
    {
        $noob = Noob::where('id', $request->noob_id)->first();

        if ($request->submit == 'Update')
        {
            $noob->notes = $request->note;
            $noob->save();
            $this->output->addSuccess("That note has been successfully updated.");
        }
        elseif ($request->submit == 'Delete')
        {
            $noob->notes = ' ';
            $noob->save();
            $this->output->addSuccess("That note has been successfully deleted.");
        }

        //return to correct page using noobs rank
        if ($noob->forum_mask == 3)
        {
            return view('ia/applicants', [
                "page" => "applicants",
                "output" => $this->output,
            ]);
        }
        elseif (in_array($noob->forum_mask, [77, 133], true))
        {
            return view('ia/academy', [
                "page" => "academy",
                "output" => $this->output,
            ]);
        }
        elseif (! in_array($noob->forum_mask, [3, 77, 133], true))
        {
            return view('ia/track', [
                "page" => "track",
                "output" => $this->output,
            ]);
        }
    }

    public function unmask()
    {
        return view('ia/unmask', [
            "page" => "unmask",
        ])->with('status', 'default');
    }
}
