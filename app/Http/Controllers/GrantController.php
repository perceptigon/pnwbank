<?php

namespace App\Http\Controllers;

use Auth;
use Gate;
use App\Classes\Output;
use App\Classes\Verify;
use Illuminate\Http\Request;

class GrantController extends Controller
{
    /**
     * @var Output
     */
    private $output;

    /**
     * GrantController constructor.
     */
    public function __construct()
    {
        $this->output = new Output();
    }

    /**
     * GET: /city.
     *
     * City Grants
     *
     * @return mixed
     */
    public function city()
    {
        $grants = \App\Models\Grants\CityGrant::orderBy("grantNum")->get();
        $settings = \App\Models\Settings::where("sKey", "cityGrantSystem")->firstOrFail();

        return view("grants/city", [
            'grants' => $grants,
            'settings' => $settings,
            ])
            ->with('output', $this->output);
    }

    /**
     * POST: /city.
     *
     * Requesting a city grant
     *
     * @param Request $request
     * @return bool|mixed
     */
    public function reqCity(Request $request)
    {
        try
        {
            $settings = \App\Models\Settings::where("sKey", "cityGrantSystem")->firstOrFail();

            if ($settings->value === 0)
            {
                echo "The grant system is turned off"; // This should never happen, however if someone sends a POST request we can kill it here
                return false;
            }
            $nation = new \App\Classes\Nation($request->nID);
            $verify = new Verify($nation);

            if ($verify->requestCityGrant()) // Checks passed, setup pending city grant
            {
                // Update profile
                $profile = \App\Models\Profile::where("nationID", $nation->nID)->firstOrFail();
                $profile->grantPending = 1;
                $profile->save();
                // Set city grant pending
                $cityNum = $nation->cities + 1;
                $gInfo = \App\Models\Grants\CityGrant::getGrantInfo($cityNum);
                \App\Models\Grants\CityGrantRequests::addPendGrant($nation, $cityNum, $gInfo->amount);
                \App\Models\Log::createLog("cityGrant", "Requested grant #".$cityNum." ($nation->nID)");
                $this->output->addSuccess("Thanks, {$nation->leader}. Your request has been submitted. Please allow up to 24 hours for approval.");
            }
            else
            {
                foreach ($verify->errors as $error)
                    $this->output->addError($error);
                \App\Models\Log::createLog("cityGrant", "Not eligible for grant ($nation->nID)", $this->output->errors);
            }
        }
        catch (\Exception $ex)
        {
            $this->output->addError($ex->getMessage());
        }

        // Return normal city grant view
        return self::city();

    }

    /**
     * GET: /entrance.
     *
     * Entrance Aid
     *
     * @return mixed
     */
    public function entrance()
    {
        $amount = \App\Models\Settings::where("sKey", "entranceAidAmount")->firstOrFail();
        $system = \App\Models\Settings::where("sKey", "entranceAidSystem")->firstOrFail();

        return view("grants/entrance", [
            'amount' => $amount->value,
            'system' => $system->value,
        ])
            ->with('output', $this->output);
    }

    /**
     * POST: /entrance.
     *
     * Request entrance aid
     *
     * @param Request $request
     * @return bool|mixed
     */
    public function reqEntrance(Request $request)
    {
        try
        {
            $amount = \App\Models\Settings::where("sKey", "entranceAidAmount")->firstOrFail();
            $settings = \App\Models\Settings::where("sKey", "entranceAidSystem")->firstOrFail();

            if ($settings === 0)
            {
                echo "The entrance aid system is turned off"; // This should never happen, however if someone sends a POST request we can kill it here
                return false;
            }

            $nation = new \App\Classes\Nation($request->nID);
            $verify = new Verify($nation);

            if ($verify->requestEntranceAid())
            {
                $ent = new \App\Models\Grants\EntranceAid();
                $ent->nID = $nation->nID;
                $ent->leader = $nation->leader;
                $ent->nationName = $nation->nationName;
                $ent->amount = $amount->value;
                $ent->save();

                \App\Models\Log::createLog("entrance", "Requested ($nation->nID)");
                $this->output->addSuccess("Thanks, {$nation->leader}. Your request has been submitted. Please allow up to 24 hours for approval.");
            }
            else
            {
                foreach ($verify->errors as $error)
                    $this->output->addError($error);

                \App\Models\Log::createLog("entrance", "Not eligible for grant ($nation->nID)", $this->output->errors);
            }
        }
        catch (\Exception $ex)
        {
            $this->output->addError($ex->getMessage());
        }

        return self::entrance();

    }

    public function egrGrant()
    {
        $system = \App\Models\Settings::where("sKey", "oilSystem")->firstOrFail();

        return view("grants/egr", [
            'system' => $system->value,
        ])
            ->with('output', $this->output);
    }

    public function reqEGRGrant(Request $request)
    {
        try
        {
            $settings = \App\Models\Settings::where("sKey", "oilSystem")->firstOrFail();

            if ($settings === 0)
            {
                echo "The EGR & Oil Grant system is turned off"; // This should never happen, however if someone sends a POST request we can kill it here
                return false;
            }

            $nation = new \App\Classes\Nation($request->nID);
            $verify = new Verify($nation);

            $forumProfile = new \App\Classes\ForumProfile($nation->nID);
            $forumProfile->getForumProfile();

            if ($verify->requestEGRGrant($forumProfile))
            {
                $egr = new \App\Models\Grants\EGRGrant();
                $egr->nID = $nation->nID;
                $egr->leader = $nation->leader;
                $egr->nationName = $nation->nationName;
                $egr->isPending = true;
                $egr->isSent = false;
                $egr->save();
                \App\Models\Log::createLog("egr", "Requested ($nation->nID)");
                $this->output->addSuccess("Thanks, {$nation->leader}. Your request has been submitted. Please allow up to 24 hours for approval.");
            }
            else
            {
                foreach ($verify->errors as $error)
                    $this->output->addError($error);
                \App\Models\Log::createLog("egr", "Not eligible for grant ($nation->nID)", $this->output->errors);
            }
        }
        catch (\Exception $ex)
        {
            $this->output->addError($ex->getMessage());
        }

        return self::egrGrant();
    }

    /**
     * GET: /ironddome.
     *
     * Iron Dome Grants
     *
     * @return mixed
     */
    public function irondomeGrant()
    {
        $system = \App\Models\Settings::where("sKey", "idGrantSystem")->firstOrFail();

        return view("grants/irondome", [
            'system' => $system->value,
        ])
            ->with('output', $this->output);
    }

    public function reqirondomeGrant(Request $request)
    {
        try
        {
            $settings = \App\Models\Settings::where("sKey", "idGrantSystem")->firstOrFail();

            if ($settings === 0)
            {
                echo "The Grant system is turned off"; // This should never happen, however if someone sends a POST request we can kill it here
                return false;
            }

            $nation = new \App\Classes\Nation($request->nID);
            $verify = new Verify($nation);

            $forumProfile = new \App\Classes\ForumProfile($nation->nID);
            $forumProfile->getForumProfile();

            if ($verify->requestirondomeGrant($forumProfile))
            {
                $irondomegrant = new \App\Models\Grants\irondomeGrants();
                $irondomegrant->nID = $nation->nID;
                $irondomegrant->leader = $nation->leader;
                $irondomegrant->nationName = $nation->nationName;
                $irondomegrant->isPending = true;
                $irondomegrant->isSent = false;
                $irondomegrant->save();
                \App\Models\Log::createLog("Iron Dome", "Requested ($nation->nID)");
                $this->output->addSuccess("Thanks, {$nation->leader}. Your request has been submitted. Please allow up to 24 hours for approval.");
            }
            else
            {
                foreach ($verify->errors as $error)
                    $this->output->addError($error);
                \App\Models\Log::createLog("egr", "Not eligible for grant ($nation->nID)", $this->output->errors);
            }
        }
        catch (\Exception $ex)
        {
            $this->output->addError($ex->getMessage());
        }

        return self::irondomeGrant();
    }
    /**
     * GET: /cce.
     *
     * cce Grants
     *
     * @return mixed
     */
    public function cceGrant()
    {
        $system = \App\Models\Settings::where("sKey", "idGrantSystem")->firstOrFail();

        return view("grants/cce", [
            'system' => $system->value,
        ])
            ->with('output', $this->output);
    }

    public function reqcceGrant(Request $request)
    {
        try
        {
            $settings = \App\Models\Settings::where("sKey", "idGrantSystem")->firstOrFail();

            if ($settings === 0)
            {
                echo "The Grant system is turned off"; // This should never happen, however if someone sends a POST request we can kill it here
                return false;
            }

            $nation = new \App\Classes\Nation($request->nID);
            $verify = new Verify($nation);

            $forumProfile = new \App\Classes\ForumProfile($nation->nID);
            $forumProfile->getForumProfile();

            if ($verify->requestcceGrant($forumProfile))
            {
                $ccegrant = new \App\Models\Grants\cceGrants();
                $ccegrant->nID = $nation->nID;
                $ccegrant->leader = $nation->leader;
                $ccegrant->nationName = $nation->nationName;
                $ccegrant->isPending = true;
                $ccegrant->isSent = false;
                $ccegrant->save();
                \App\Models\Log::createLog("CCE", "Requested ($nation->nID)");
                $this->output->addSuccess("Thanks, {$nation->leader}. Your request has been submitted. Please allow up to 24 hours for approval.");
            }
            else
            {
                foreach ($verify->errors as $error)
                    $this->output->addError($error);
                \App\Models\Log::createLog("cce", "Not eligible for grant ($nation->nID)", $this->output->errors);
            }
        }
        catch (\Exception $ex)
        {
            $this->output->addError($ex->getMessage());
        }

        return self::cceGrant();
    }
    /**
     * GET: /nrf.
     *
     * nrf Grants
     *
     * @return mixed
     */
    public function nrfGrant()
    {
        $system = \App\Models\Settings::where("sKey", "nukeprojectSystem")->firstOrFail();

        return view("grants/nrf", [
            'system' => $system->value,
        ])
            ->with('output', $this->output);
    }

    public function reqnrfGrant(Request $request)
    {
        try
        {
            $settings = \App\Models\Settings::where("sKey", "nukeprojectSystem")->firstOrFail();

            if ($settings === 0)
            {
                echo "The NRF Grant system is turned off"; // This should never happen, however if someone sends a POST request we can kill it here
                return false;
            }

            $nation = new \App\Classes\Nation($request->nID);
            $verify = new Verify($nation);

            $forumProfile = new \App\Classes\ForumProfile($nation->nID);
            $forumProfile->getForumProfile();

            if ($verify->requestnrfGrant($forumProfile))
            {
                $nrfgrant = new \App\Models\Grants\nrfGrants();
                $nrfgrant->nID = $nation->nID;
                $nrfgrant->leader = $nation->leader;
                $nrfgrant->nationName = $nation->nationName;
                $nrfgrant->isPending = true;
                $nrfgrant->isSent = false;
                $nrfgrant->save();
                \App\Models\Log::createLog("nrf", "Requested ($nation->nID)");
                $this->output->addSuccess("Thanks, {$nation->leader}. Your request has been submitted. Please allow up to 24 hours for approval.");
            }
            else
            {
                foreach ($verify->errors as $error)
                    $this->output->addError($error);
                \App\Models\Log::createLog("nrf", "Not eligible for grant ($nation->nID)", $this->output->errors);
            }
        }
        catch (\Exception $ex)
        {
            $this->output->addError($ex->getMessage());
        }

        return self::nrfGrant();
    }
    /**
     * GET: /mlp.
     *
     * mlp Grants
     *
     * @return mixed
     */
    public function mlpGrant()
    {
        $system = \App\Models\Settings::where("sKey", "idGrantSystem")->firstOrFail();

        return view("grants/mlp", [
            'system' => $system->value,
        ])
            ->with('output', $this->output);
    }

    public function reqmlpGrant(Request $request)
    {
        try
        {
            $settings = \App\Models\Settings::where("sKey", "idGrantSystem")->firstOrFail();

            if ($settings === 0)
            {
                echo "The Grant system is turned off"; // This should never happen, however if someone sends a POST request we can kill it here
                return false;
            }

            $nation = new \App\Classes\Nation($request->nID);
            $verify = new Verify($nation);

            $forumProfile = new \App\Classes\ForumProfile($nation->nID);
            $forumProfile->getForumProfile();

            if ($verify->requestmlpGrant($forumProfile))
            {
                $mlpgrant = new \App\Models\Grants\mlpGrants();
                $mlpgrant->nID = $nation->nID;
                $mlpgrant->leader = $nation->leader;
                $mlpgrant->nationName = $nation->nationName;
                $mlpgrant->isPending = true;
                $mlpgrant->isSent = false;
                $mlpgrant->save();
                \App\Models\Log::createLog("MLP", "Requested ($nation->nID)");
                $this->output->addSuccess("Thanks, {$nation->leader}. Your request has been submitted. Please allow up to 24 hours for approval.");
            }
            else
            {
                foreach ($verify->errors as $error)
                    $this->output->addError($error);
                \App\Models\Log::createLog("mlp", "Not eligible for grant ($nation->nID)", $this->output->errors);
            }
        }
        catch (\Exception $ex)
        {
            $this->output->addError($ex->getMessage());
        }

        return self::mlpGrant();
    }
    /**
     * GET: /pb.
     *
     * pb Grants
     *
     * @return mixed
     */
    public function pbGrant()
    {
        $system = \App\Models\Settings::where("sKey", "idGrantSystem")->firstOrFail();

        return view("grants/pb", [
            'system' => $system->value,
        ])
            ->with('output', $this->output);
    }

    public function reqpbGrant(Request $request)
    {
        try
        {
            $settings = \App\Models\Settings::where("sKey", "idGrantSystem")->firstOrFail();

            if ($settings === 0)
            {
                echo "The Grant system is turned off"; // This should never happen, however if someone sends a POST request we can kill it here
                return false;
            }

            $nation = new \App\Classes\Nation($request->nID);
            $verify = new Verify($nation);

            $forumProfile = new \App\Classes\ForumProfile($nation->nID);
            $forumProfile->getForumProfile();

            if ($verify->requestpbGrant($forumProfile))
            {
                $pbgrant = new \App\Models\Grants\pbGrants();
                $pbgrant->nID = $nation->nID;
                $pbgrant->leader = $nation->leader;
                $pbgrant->nationName = $nation->nationName;
                $pbgrant->isPending = true;
                $pbgrant->isSent = false;
                $pbgrant->save();
                \App\Models\Log::createLog("PB", "Requested ($nation->nID)");
                $this->output->addSuccess("Thanks, {$nation->leader}. Your request has been submitted. Please allow up to 24 hours for approval.");
            }
            else
            {
                foreach ($verify->errors as $error)
                    $this->output->addError($error);
                \App\Models\Log::createLog("PB", "Not eligible for grant ($nation->nID)", $this->output->errors);
            }
        }
        catch (\Exception $ex)
        {
            $this->output->addError($ex->getMessage());
        }

        return self::pbGrant();
    }
    /**
     * GET: /id.
     *
     * CIA Grants
     *
     * @return mixed
     */
    public function idGrant()
    {
        $amount = \App\Models\Settings::where("sKey", "idGrantAmount")->firstOrFail();
        $system = \App\Models\Settings::where("sKey", "idGrantSystem")->firstOrFail();

        return view("grants/id", [
            'amount' => $amount->value,
            'system' => $system->value,
        ])
            ->with('output', $this->output);
    }

    /**
     * POST: /id.
     *
     * Request an ID Grant
     *
     * @param Request $request
     * @return bool|mixed
     */
    public function reqIDGrant(Request $request)
    {
        try
        {
            $amount = \App\Models\Settings::where("sKey", "idGrantAmount")->firstOrFail();
            $settings = \App\Models\Settings::where("sKey", "idGrantSystem")->firstOrFail();

            if ($settings === 0)
            {
                echo "The ID Grant system is turned off"; // This should never happen, however if someone sends a POST request we can kill it here
                return false;
            }

            $nation = new \App\Classes\Nation($request->nID);
            $verify = new Verify($nation);

            $forumProfile = new \App\Classes\ForumProfile($nation->nID);
            $forumProfile->getForumProfile();

            if ($verify->requestCIAGrant($forumProfile))
            {
                $id = new \App\Models\Grants\IDGrants();
                $id->nID = $nation->nID;
                $id->leader = $nation->leader;
                $id->nationName = $nation->nationName;
                $id->amount = $amount->value;
                $id->save();
                \App\Models\Log::createLog("id", "Requested ($nation->nID)");
                $this->output->addSuccess("Thanks, {$nation->leader}. Your request has been submitted. Please allow up to 24 hours for approval.");
            }
            else
            {
                foreach ($verify->errors as $error)
                    $this->output->addError($error);
                \App\Models\Log::createLog("id", "Not eligible for grant ($nation->nID)", $this->output->errors);
            }
        }
        catch (\Exception $ex)
        {
            $this->output->addError($ex->getMessage());
        }

        return self::idGrant();
    }

    /**
     * GET: /activity.
     *
     * View Activity Grants page
     *
     * @return mixed
     */
    public function activity()
    {
        $system = \App\Models\Settings::where("sKey", "activityGrantSystem")->firstOrFail();

        return view("grants/activity", [
            'system' => $system->value,
        ])
            ->with('output', $this->output);
    }

    /**
     * POST: /activity.
     *
     * Request an activity grant and validate
     *
     * @param Request $request
     * @return bool|mixed
     */
    public function reqActivity(Request $request)
    {
        $settings = \App\Models\Settings::where("sKey", "activityGrantSystem")->firstOrFail();

        try
        {
            if ($settings === 0)
            {
                echo "The Activity Grant system is turned off"; // This should never happen, however if someone sends a POST request we can kill it here
                return false;
            }

            $nation = new \App\Classes\Nation($request->nID);

            $forumProfile = new \App\Classes\ForumProfile($nation->nID);
            $forumProfile->getForumProfile();

            $verify = new Verify($nation);

            if ($verify->requestActivityGrant($forumProfile))
            {
                $verify->profile->pendingActivityGrant = 1;
                $verify->profile->save();

                $activity = new \App\Models\Grants\ActivityGrant();
                $activity->nID = $nation->nID;
                $activity->leader = $nation->leader;
                $activity->nationName = $nation->nationName;
                $activity->threshold = $verify->threshold;
                $activity->amount = $verify->activityAmount;
                $activity->save();

                \App\Models\Log::createLog("activity", "Requested $verify->threshold ($nation->nID)");
                $this->output->addSuccess("Thanks, {$nation->leader}. Your request has been submitted. Please allow up to 24 hours for approval.");
            }
            else
            {
                foreach ($verify->errors as $error)
                    $this->output->addError($error);

                \App\Models\Log::createLog("activity", "Not eligible for grant ($nation->nID)", $this->output->errors);
            }
        }
        catch (\Exception $ex)
        {
            $this->output->addError($ex->getMessage());
        }

        return self::activity();
    }

    public function oil()
    {
        $system = \App\Models\Settings::where("sKey", "oilSystem")->firstOrFail();

        return view("grants/oil", [
            'system' => $system->value,
        ])
            ->with('output', $this->output);
    }

    public function reqOil(Request $request)
    {
        $settings = \App\Models\Settings::where("sKey", "oilSystem")->firstOrFail();

        try
        {
            if ($settings === 0)
            {
                echo "The EGR & Oil Grant system is turned off"; // This should never happen, however if someone sends a POST request we can kill it here
                return false;
            }

            $nation = new \App\Classes\Nation($request->nID);

            $verify = new Verify($nation);

            if ($verify->reqOil())
            {
                $verify->profile->pendingOilGrant = 1;
                $verify->profile->save();

                $oil = new \App\Models\Grants\OilGrant();
                $oil->nID = $nation->nID;
                $oil->leader = $nation->leader;
                $oil->nationName = $nation->nationName;
                $oil->isPending = true;
                $oil->isSent = false;
                $oil->save();

                \App\Models\Log::createLog("oil", "Requested $verify->threshold ($nation->nID)");
                $this->output->addSuccess("Thanks, {$nation->leader}. Your request has been submitted. Please allow up to 24 hours for approval.");
            }
            else
            {
                foreach ($verify->errors as $error)
                    $this->output->addError($error);

                \App\Models\Log::createLog("oil", "Not eligible for grant ($nation->nID)", $this->output->errors);
            }
        }
        catch (\Exception $ex)
        {
            $this->output->addError($ex->getMessage());
        }

        return self::oil();
    }

    public function nukes()
    {
        $system = \App\Models\Settings::where("sKey", "nukesSystem")->firstOrFail();

        return view("grants/nukes", [
            'system' => $system->value,
        ])
            ->with('output', $this->output);
    }

    public function reqNukes(Request $request)
    {
        $settings = \App\Models\Settings::where("sKey", "nukesSystem")->firstOrFail();

        try
        {
            if ($settings === 0)
            {
                echo "The Nuke Grant system is turned off"; // This should never happen, however if someone sends a POST request we can kill it here
                return false;
            }

            $nation = new \App\Classes\Nation($request->nID);

            $forumProfile = new \App\Classes\ForumProfile($nation->nID);
            $forumProfile->getForumProfile();

            $verify = new Verify($nation);

            if ($verify->requestNukeGrants($forumProfile))
            {
                $verify->profile->pendingnukesGrant = 1;
                $verify->profile->save();

                $nuke = new \App\Models\Grants\NukeGrants();
                $nuke->nID = $nation->nID;
                $nuke->leader = $nation->leader;
                $nuke->nationName = $nation->nationName;
                $nuke->isPending = true;
                $nuke->isSent = false;
                $nuke->save();

                \App\Models\Log::createLog("nuke", "Requested $verify->threshold ($nation->nID)");
                $this->output->addSuccess("Thanks, {$nation->leader}. Your request has been submitted. Please allow up to 24 hours for approval.");
            }
            else
            {
                foreach ($verify->errors as $error)
                    $this->output->addError($error);

                \App\Models\Log::createLog("nuke", "Not eligible for grant ($nation->nID)", $this->output->errors);
            }
        }
        catch (\Exception $ex)
        {
            $this->output->addError($ex->getMessage());
        }

        return self::oil();
    }
}
