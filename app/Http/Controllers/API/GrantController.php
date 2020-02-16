<?php

namespace App\Http\Controllers\API;

use App\Classes\Verify;
use App\Models\Grants\CityGrant;
use App\Models\Profile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class GrantController extends Controller
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
     * GrantController constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function reqCity()
    {
        try
        {
            $settings = \App\Models\Settings::where("sKey", "cityGrantSystem")->firstOrFail();

            if ($settings->value === 0)
                throw new \Exception("The grant system is turned off");
            if (empty($this->request->nID))
                throw new \Exception("No Nation ID Provided");
            $nation = new \App\Classes\Nation($this->request->nID);
            $verify = new Verify($nation);

            if ($verify->requestCityGrant()) // Checks passed, setup pending city grant
            {
                // Update profile
                $profile = Profile::getProfile($nation->nID);
                $profile->grantPending = 1;
                $profile->save();
                // Set city grant pending
                $cityNum = $nation->cities + 1;
                $gInfo = \App\Models\Grants\CityGrant::getGrantInfo($cityNum);
                $grant = \App\Models\Grants\CityGrantRequests::addPendGrant($nation, $cityNum, $gInfo->amount);
                \App\Models\Log::createLog("cityGrant", "Requested grant #".$cityNum." ($nation->nID)");

                return $grant->toJson(149);
            }
            else
            {
                foreach ($verify->errors as $error)
                    array_push($this->errors, $error);
                \App\Models\Log::createLog("cityGrant", "Not eligible for grant ($nation->nID)", $this->errors);

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
     * Get the city grant info and display it as JSON.
     *
     * @param int $gID
     * @return $this
     */
    public function gInfo(int $gID)
    {
        $gInfo = CityGrant::getGrantInfoByID($gID);

        $gInfo->notes = str_replace("<", "&lt;", $gInfo->notes);

        return (new Response($gInfo->toJson(128), 200))
            ->header("content-type", "application/json");
    }
}
