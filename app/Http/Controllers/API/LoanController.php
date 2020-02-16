<?php

namespace App\Http\Controllers\API;

use App\Models\Loans;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class LoanController extends Controller
{
    /**
     * Stores the request.
     *
     * @var Request
     */
    protected $request;

    /**
     * Will hold our errors.
     *
     * @var array
     */
    protected $errors = [];

    /**
     * LoanController constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Requests a loan.
     *
     * @return $this|string
     */
    public function reqLoan()
    {
        try
        {
            $settings = \App\Models\Settings::where("sKey", "loanSystem")->firstOrFail();
            $due = \App\Models\Settings::where("sKey", "loanDuration")->firstOrFail();
            if ($settings->value === 0)
                throw new \Exception("The loan system is turned off");
            // Do some verification so it doesn't throw an uncaught exception if something is left empty
            if (empty($this->request->nID))
                throw new \Exception("Your nation ID is empty");
            if (empty($this->request->amount))
                throw new \Exception("You can't leave the amount empty");
            if (empty($this->request->reason))
                throw new \Exception("You must provide a reason");
            $nation = new \App\Classes\Nation($this->request->nID);
            $verify = new \App\Classes\Verify($nation);

            if ($verify->requestLoan($this->request->amount, $this->request->reason))
            {
                $profile = \App\Models\Profile::getProfile($this->request->nID);
                $profile->loanActive = 1;
                $profile->save();

                $code = \App\Classes\Codes::generateCode();

                $loan = new \App\Models\Loans();
                $loan->code = $code;
                $loan->nationID = $nation->nID;
                $loan->nationName = $nation->nationName;
                $loan->amount = intval($this->request->amount);
                $loan->originalAmount = intval($this->request->amount);
                $loan->duration = intval($due->value);
                $loan->reason = $this->request->reason;
                $loan->leader = $nation->leader;
                $loan->score = $nation->score;
                $loan->isApproved = 1;
                $loan->save();

                \App\Models\Log::createLog("loan", "Requested loan $code ($nation->nID)");

                return $loan->toJson(149);
            }
            else
            {
                foreach ($verify->errors as $error)
                    array_push($this->errors, $error);

                \App\Models\Log::createLog("loan", "Not eligible for loan ($nation->nID)", $this->errors);

                $response = [
                    "errors" => $this->errors,
                ];

                return response(\json_encode($response, 149), 400)
                    ->header("Content-Type", "application/json");
            }
        }
        catch (\Exception $e)
        {
            $response = [
                "errors" => [$e->getMessage()],
            ];

            return response(\json_encode($response, 149), 400)
                ->header("Content-Type", "application/json");
        }
    }

    /**
     * Returns an json response with the loan info.
     *
     * @param int $code
     * @return $this|string
     */
    public function getLoan(int $code)
    {
        try
        {
            $loan = Loans::getLoanInfo($code);
        }
        catch (ModelNotFoundException $e)
        {
            $response = [
                "errors" => ["No loan with that code"],
            ];

            return response(\json_encode($response, 149), 404)
                ->header("Content-Type", "application/json");
        }

        return $loan->toJson(149);
    }
}
