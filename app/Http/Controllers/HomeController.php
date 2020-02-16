<?php

namespace App\Http\Controllers;

use App\Classes\PWClient;
use App\Models\Inactivity;
use Auth;
use App\Http\Requests;
use App\Classes\Output;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    // This controller will just be used for random stuff that has nothing to do with the other controllers

    /**
     * @var Output
     */
    private $output;

    /**
     * HomeController constructor.
     */
    public function __construct()
    {
        $this->output = new Output();
    }

    /**
     * Return homepage view.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function home()
    {
        return view('home');
    }

    /**
     * GET: /contact.
     *
     * View Contact page
     *
     * @return mixed
     */
    public function contact()
    {
        if (! Auth::guest() && Auth::user()->isAdmin) // Get pending contact requests if they're an admin
            $pendReqs = \App\Models\Contact::getPendReqs();
        else
            $pendReqs = null;

        return view("contact", [
            "pendReqs" => $pendReqs,
        ])
            ->with('output', $this->output);
    }

    /**
     * POST: /contact.
     *
     * Create a contact request or change it's status
     *
     * @param Request $request
     * @return mixed
     */
    public function contactPost(Request $request)
    {
        try
        {
            if (isset($request->createReq))
            {
                \App\Models\Contact::createReq($request);
                $this->output->addSuccess("Your contact request has been submitted. Please allow up to 24 hours for a response");
                \App\Models\Log::createLog("contact", "Created contact request");
            }
            elseif (isset($request->setComplete))
            {
                \App\Models\Contact::setComplete($request->cID);
                $this->output->addSuccess("Contact set to complete");
                \App\Models\Log::createLog("contact", "Completed contact - ".$request->cID);
            }
            else
            {
                throw new \Exception("Couldn't determine function");
            }
        }
        catch (\Exception $ex)
        {
            $this->output->addError($ex->getMessage());
        }

        return self::contact();

    }

    /**
     * Removes an inactive nation from inactive status
     *
     * @param int $id
     * @return $this
     */
    public function removeInactive(int $id)
    {
        $inactive = Inactivity::find($id);

        if (! $inactive->isInactive)
        {
            $this->output->addError("That nation is not in inactive status");
        }
        else
        {
            $client = new PWClient();
            $client->login();
            $inactive->setMemberActive($client);
            $this->output->addSuccess("Your nation has been moved to the correct tax bracket.");
        }

        return view("home")
            ->with([
                "output" => $this->output,
            ]);
    }
}
