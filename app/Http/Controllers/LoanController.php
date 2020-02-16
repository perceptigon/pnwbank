<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use Auth;
use App\Classes\Output;
use App\Classes\PWClient;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    /**
     * @var Output
     */
    private $output;

    /**
     * Store the PWClient if needed.
     *
     * @var PWClient
     */
    private $client;

    /**
     * LoanController constructor.
     */
    public function __construct()
    {
        $this->output = new Output();
    }

    /**
     * GET: /loans.
     *
     * View loan page
     *
     * @return mixed
     */
    public function loans()
    {
        $settings = Settings::getSettings();

        return view("loan", [
            "settings" => $settings,
            ])
            ->with('output', $this->output);
    }

    /**
     * POST: /loans.
     *
     * Request a loan
     *
     * @param Request $request
     * @return bool|mixed
     */
    public function reqLoan(Request $request)
    {
        try
        {
            $settings = \App\Models\Settings::where("sKey", "loanSystem")->firstOrFail();
            $due = \App\Models\Settings::where("sKey", "loanDuration")->firstOrFail();
            if ($settings->value === 0)
            {
                echo "The loan system is turned off";

                return false;
            }

            // Do some verification so it doesn't throw an uncaught exception if something is left empty
            if (empty($request->nID))
                throw new \Exception("Your nation ID is empty");
            if (empty($request->amount))
                throw new \Exception("You can't leave the amount empty");
            if (empty($request->reason))
                throw new \Exception("You must provide a reason");
            $nation = new \App\Classes\Nation($request->nID);
            $verify = new \App\Classes\Verify($nation);

            if ($verify->requestLoan($request->amount, $request->reason))
            {
                $profile = \App\Models\Profile::where("nationID", $nation->nID)->firstOrFail();
                $profile->loanActive = 1;
                $profile->save();

                $code = \App\Classes\Codes::generateCode();

                $loan = new \App\Models\Loans();
                $loan->code = $code;
                $loan->nationID = $nation->nID;
                $loan->nationName = $nation->nationName;
                $loan->amount = $request->amount;
                $loan->originalAmount = $request->amount;
                $loan->duration = $due->value;
                $loan->reason = $request->reason;
                $loan->leader = $nation->leader;
                $loan->score = $nation->score;
                $loan->isApproved = 1;
                $loan->save();

                \App\Models\Log::createLog("loan", "Requested loan $code ($nation->nID)");
                $this->output->addSuccess("Thanks, {$nation->leader}! Your loan request has been submitted. Please allow up to 24 hours for approval.");
            }
            else
            {
                foreach ($verify->errors as $error)
                    $this->output->addError($error);

                \App\Models\Log::createLog("loan", "Not eligible for loan ($nation->nID)", $this->output->errors);
            }

        }
        catch (\Exception $ex)
        {
            $this->output->addError($ex->getMessage());
        }

        return self::loans($this->output);
    }

    /**
     * GET: /lookup/{$code}.
     *
     * Lookup a loan by it's code
     *
     * @param $code
     * @return mixed
     */
    public function lookup($code)
    {
        try
        {
            $loan = \App\Models\Loans::getLoanInfo($code);
        }
        catch (\Exception $ex)
        {
            //abort(404);
        }

        $logs = \App\Models\Log::getLoanLogs($code);

        return view("loanlookup", [
                "loan" => $loan,
                "logs" => $logs,
        ])
            ->with('output', $this->output);
    }

    /**
     * POST: /lookup/{$code}.
     *
     * Edit a loan
     *
     * @param $code
     * @param Request $request
     * @return mixed
     */
    public function editLoan($code, Request $request)
    {
        if (Auth::guest() && ! Auth::user()->isAdmin)
            abort(403);

        try
        {
            if (isset($request->editLoan))
            {
                $loan = \App\Models\Loans::getLoanInfo($code);
                $loan->amount = $request->amount;
                $loan->due = $request->due;
                $loan->save();
                \App\Models\Log::createLog("loan", "Edited Loan ($code)");
                $this->output->addSuccess("Loan edited");
            }
            elseif (isset($request->markLoanComplete)) // Manually mark loan complete
            {
                $loan = \App\Models\Loans::getLoanInfo($code);
                $this->client = new PWClient();
                $this->client->login();
                $loan->loanComplete($this->client);
                $this->output->addSuccess("Loan Paid Off");
            }
            else
                throw new \Exception("Couldn't determine function");
        }
        catch (\Exception $e)
        {
            $this->output->addError($e->getMessage());
        }

        return $this->lookup($code);
    }
}
