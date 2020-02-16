<?php

namespace App\Http\Controllers;

use App\Exceptions\UserErrorException;
use App\Models\Accounts;
use App\Models\MMR;
use App\Models\Recruiting\Nations;
use App\Models\Recruiting\Status;
use App\Models\Profile;
use App\Models\User;
use Auth;
use Gate;
use Carbon\Carbon;
use App\Models\Log;
use App\Models\Loans;
use App\Models\Taxes;
use App\Http\Requests;
use App\Classes\Output;
use App\Classes\Verify;
use App\Jobs\SendMoney;
use App\Classes as Classes;
use Illuminate\Http\Request;
use App\Defense\DefenseSignin;
use App\Defense\DefenseNations;
use App\Models\Grants\CityGrant;
use App\Models\Grants\ActivityGrant;
use App\Defense\DefenseNationHistory;
use Mockery\CountValidator\Exception;
use App\Models\Grants\CityGrantRequests;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AdminController extends Controller
{
    /**
     * @var Output
     */
    private $output;

    /**
     * Store a PWClient if needed.
     *
     * @var Classes\PWClient
     */
    private $client;

    /**
     * AdminController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->output = new Output();
    }

    /**
     * Admin Dashboard.
     *
     * @return mixed
     */
    public function index()
    {
        $stats = new Classes\Stats();
        $stats->dashboard();

        return view("admin/index", [
            "page" => "dashboard",
            "stats" => $stats,
        ]);
    }

    /**
     * GET: admin/users.
     *
     * @return mixed
     */
    public function users(Request $request)
    {
        if (Gate::denies("users"))
           return view("admin.unauthorized", ["page" => "users"]);

        if ($request->get("username") !== null) // Search for a username
            $users = User::where("username", "LIKE", "%{$request->get('username')}%")->paginate(1000); // Paginate but not really lol
        else
            $users = \App\Models\User::paginate(25);

        return view("admin/users", [
            "page" => "users",
            "users"=> $users,
        ]);
    }

    /**
     * GET: admin/users/edit.
     *
     * @param $uID
     * @return mixed
     */
    public function editUser($uID)
    {
        if (Gate::denies("users"))
            return view("admin.unauthorized", ["page" => "users"]);

        // Get user
        $user = \App\Models\User::where("id", $uID)->first();
        $perms = $user->getPermissions();
        if (! isset($user->username))
        {
            $this->output->addError("No member with that ID");
            abort("404"); // If the user doesn't exist who gives a fuck
        }

        // Load user's accounts
        $user->load("accounts");

        return view("admin/editUser", [
            "user" => $user,
            "page" => "users",
            "perms" => $perms,
            ])
            ->with('output', $this->output);

    }

    /**
     * POST: admin/users.
     *
     * Edits a user
     *
     * @param Request $request
     * @return mixed
     */
    public function postEditUser(Request $request)
    {
        if (Gate::denies("admin") && Gate::denies("user")) // Prevent unauthorized POSTs
            return view("admin.unauthorized", ["page" => "users"]);

        $user = \App\Models\User::where("id", $request->id)->first();
        if (! isset($user->username))
        {
            $this->output->addError("No member with that ID");
            abort("404"); // If the user doesn't exist who gives a fuck
        }

        // If isAdmin isn't set, it'll be null
        if ($request->isAdmin != 1)
            $request->isAdmin = 0; // Just set it to 0 here. Fuck it

        // Update permissions and verify that the string passed is "yes", if not, set it to "no"
        $perms = json_encode([
           "loans" => ($request->loans ?? "no") === "yes" ? "yes" : "no",
            "grants" => ($request->grants ?? "no") === "yes" ? "yes" : "no",
            "market" => ($request->market ?? "no") === "yes" ? "yes" : "no",
            "settings" => ($request->settings ?? "no") === "yes" ? "yes" : "no",
            "so" => ($request->so ?? "no") === "yes" ? "yes" : "no",
            "users" => ($request->users ?? "no") === "yes" ? "yes" : "no",
            "taxes" => ($request->taxes ?? "no") === "yes" ? "yes" : "no",
            "members" => ($request->members ?? "no") === "yes" ? "yes" : "no",
            "accounts" => ($request->accounts ?? "no") === "yes" ? "yes" : "no",
            "targets" => ($request->targets ?? "no") === "yes" ? "yes" : "no",
        ]);

        $user->username = $request->username;
        $user->title = $request->title;
        $user->isAdmin = $request->isAdmin;
        $user->permissions = $perms;
        $user->nID = $request->nID;
        $user->save();

        $this->output->addSuccess("You've edited that user, gg");

        return self::editUser($user->id);
    }

    /**
     * GET: admin/loans.
     *
     * Views active/pending loans and loan actions
     *
     * @return mixed
     */
    public function loans()
    {
        if (Gate::denies("loans"))
            return view("admin.unauthorized", ["page" => "loans"]);

        $stats = new Classes\Stats();
        $stats->loanPage();

        $pendLoans = \App\Models\Loans::getPendingLoans();
        $activeLoans = \App\Models\Loans::getActiveLoans();
        $logs = \App\Models\Log::getSlimLogs("loan");

        /*$today = new \DateTime;
        $today->format("Y-m-d");*/

        $today = new Carbon();

        return view("admin.loans", [
            "page" => "loans",
            "stats" => $stats,
            "pendLoans" => $pendLoans,
            "activeLoans" => $activeLoans,
            "today" => $today,
            "logs" => $logs,
        ])
            ->with('output', $this->output);
    }

    /**
     * POST: admin/loans.
     *
     * Deals with the post requests for loans. Approving/Denying/Actions
     *
     * @param Request $request
     * @return mixed
     */
    public function loanPost(Request $request)
    {
        if (Gate::denies("admin") && Gate::denies("loans")) // Prevent unauthorized POSTs
            return view("admin.unauthorized", ["page" => "loans"]);

        // Determine what POST is coming and and do what it needs to do
        try
        {
            if (isset($request->approveLoan))
            {
                if (\App\Models\Loans::acceptLoan($request))
                    $this->output->addSuccess("Loan sent successfully!");
                else
                    $this->output->addError("Couldn't send loan. Verify that there's enough money");
            }
            elseif (isset($request->denyLoan))
            {
                if (\App\Models\Loans::denyLoan($request))
                    $this->output->addSuccess("Loan denied successfully");
            }
            elseif (isset($request->manualLoan))
            {
                if (\App\Models\Loans::manualLoan($request))
                    $this->output->addSuccess("Manual loan sent successfully");
            }
            elseif (isset($request->deleteLoan))
            {
                if (\App\Models\Loans::deleteLoan($request))
                    $this->output->addSuccess("Loan deleted successfully");
            }
            else
                throw new \Exception("Couldn't determine function");
        }
        catch (\Exception $ex)
        {
            $this->output->addError($ex);
        }

        return $this->loans();

    }

    /**
     * GET: admin/market.
     *
     * @return mixed
     */
    public function market()
    {
        if (Gate::denies("market"))
            return view("admin.unauthorized", ["page" => "market"]);

        $stats = new Classes\Stats();
        $stats->marketPage();
        $last15 = \App\Models\MarketDeals::getLast15Deals();
        $offers = \App\Models\Market::getOffers();

        return view("admin.market", [
            "page" => "market",
            "stats" => $stats,
            "last15" => $last15,
            "offers" => $offers,
        ])
            ->with('output', $this->output);
    }

    /**
     * POST admin/market.
     *
     * Deleting a deal and editing offers
     *
     * @param Request $request
     * @return mixed
     */
    public function marketPost(Request $request)
    {
        if (Gate::denies("market"))
            return view("admin.unauthorized", ["page" => "market"]);

        try
        {
            if (isset($request->deleteDeal)) // Delete deal
            {
                //$deal = \App\MarketDeals::deleteDeal($request->dID);
                $deal = \App\Models\MarketDeals::getDealInfo($request->dID);
                // Add deleted amount to pool of resources
                $resource = \App\Models\Market::getResourceInfo($deal->resource);
                $resource->amount += $deal->amount;
                $resource->save();
                $deal->delete();
                $this->output->addSuccess("Deal deleted");
            }
            elseif (isset($request->editOffers))
            {
                $resources = \App\Models\Market::getOffers();

                foreach ($resources as $res) // Loop through all the resources and update them depending on the POST
                {
                    if ($request->{$res->resource.'Amount'} < 0 || $request->{$res->resource."PPU"} < 0)
                    {
                        $this->output->addError("You inserted a number less than 0 for {$res->resource}. Values for this resource was not updated");
                        continue;
                    }
                    $res->amount = $request->{$res->resource.'Amount'};
                    $res->ppu = $request->{$res->resource."PPU"};
                    $res->save();
                }

                $this->output->addSuccess("Resources edited");
            }
        }
        catch (\Exception $e)
        {
            $this->output->addError($e);
        }

        return self::market();

    }

    /**
     * GET: admin/so.
     *
     * Stratton Oakmont related things
     *
     * @return mixed
     */
    public function so()
    {
        if (Gate::denies("so"))
            return view("admin.unauthorized", ["page" => "so"]);

        return view("admin.so", [
            "page" => "so",
        ])
            ->with('output', $this->output);
    }

    /**
     * POST: admin/so.
     *
     * Stratton Oakmont POST actions
     *
     * @param Request $request
     * @return mixed
     */
    public function soPost(Request $request)
    {
        if (Gate::denies("so"))
            return view("admin.unauthorized", ["page" => "so"]);

        // Quickly check to make sure all the values are filled out
        try
        {
            if (empty($request->money) &&
                empty($request->aluminum) &&
                empty($request->bauxite) &&
                empty($request->coal) &&
                empty($request->food) &&
                empty($request->gasoline) &&
                empty($request->iron) &&
                empty($request->lead) &&
                empty($request->munitions) &&
                empty($request->oil) &&
                empty($request->steel) &&
                empty($request->uranium))
                throw new \Exception("You just tried to send nothing, dumbass.");
            if (empty($request->recipient))
                throw new \Exception("You didn't fill out the recipient");
            if (empty($request->type))
                throw new \Exception("Recipient type is incorrect or empty");
            if (empty($request->note))
                throw new \Exception("The transaction note is required");

            $bank = new \App\Classes\PWBank();

            if ($request->type === "Alliance")
                $bank->type = "Alliance";

            $bank->recipient = $request->recipient;

            if(!empty($request->money)) $bank->money = $request->money;
            if(!empty($request->aluminum)) $bank->aluminum = $request->aluminum;
            if(!empty($request->bauxite)) $bank->bauxite = $request->bauxite;
            if(!empty($request->coal)) $bank->coal = $request->coal;
            if(!empty($request->food)) $bank->food = $request->food;
            if(!empty($request->gasoline)) $bank->gasoline = $request->gasoline;
            if(!empty($request->iron)) $bank->iron = $request->iron;
            if(!empty($request->lead)) $bank->lead = $request->lead;
            if(!empty($request->munitions)) $bank->munitions = $request->munitions;
            if(!empty($request->oil)) $bank->oil = $request->oil;
            if(!empty($request->steel)) $bank->steel = $request->steel;
            if(!empty($request->uranium)) $bank->uranium = $request->uranium;

            $bank->note = $request->note;

            dispatch(new SendMoney($bank, $bank->recipient, "You done got sent money.", "test"));

            $log = new \App\Models\SO();
            $log->recipient = $request->recipient;
            $log->note = $request->note;
            $log->money = $request->money;
            $log->save();

            $this->output->addSuccess("Money sent");
        }
        catch (\Exception $e)
        {
            $this->output->addError($e->getMessage());
        }

        return self::so();
    }

    /**
     * GET: /admin/taxes.
     *
     * Displays tax data and history
     *
     * @return mixed
     */
    public function taxes()
    {
        if (Gate::denies("taxes"))
            return view("admin.unauthorized", ["page" => "taxes"]);

        $taxes = new \App\Models\Taxes();
        $taxes->getTaxHistory();

        return view("admin.taxes", [
            "page" => "taxes",
            "taxes" => $taxes,
        ]);
    }

    /**
     * GET: admin/city.
     *
     * City Grants
     *
     * @param CityGrantRequests|null $cityReq
     * @return mixed
     */
    public function city(CityGrantRequests $cityReq = null)
    {
        if (Gate::denies("grants"))
            return view("admin.unauthorized", ["page" => "city"]);

        $stats = new Classes\Stats();
        $stats->cityGrantsPage();

        $pendingGrants = \App\Models\Grants\CityGrantRequests::getPendingGrants();
        $grants = \App\Models\Grants\CityGrant::getAllCityGrants();

        $logs = \App\Models\Log::getSlimLogs("cityGrant");

        return view("admin.city", [
            "page" => "city",
            "stats" => $stats,
            "pendingGrants" => $pendingGrants,
            "grants" => $grants,
            "logs" => $logs,
            "cityReq" => $cityReq,
        ])
            ->with('output', $this->output);
    }

    /**
     * POST /admin/city.
     *
     * City grant actions. Sending/Denying/Any other actions on the page
     *
     * @param Request $request
     * @return mixed
     */
    public function cityPost(Request $request)
    {
        if (Gate::denies("grants"))
            return view("admin.unauthorized", ["page" => "city"]);

        try
        {
            // Determine which POST request is being sent
            if (isset($request->approveGrant))
            {
                if (CityGrantRequests::approveGrant($request->grantID))
                    $this->output->addSuccess("Grant sent");
                else
                    $this->output->addError("Couldn't send grant. Verify that there's enough money for the grant.");
            }
            elseif (isset($request->denyGrant))
            {
                CityGrantRequests::denyGrant($request->grantID);
                $this->output->addSuccess("Denied grant");
            }
            elseif (isset($request->resetGrantTimer))
            {
                \App\Models\Profile::resetCityGrantTimer($request->nID);
                \App\Models\Log::createLog("cityGrant", "Reset grant timer - $request->nID");
                $this->output->addSuccess("Grant timer reset");
            }
            elseif (isset($request->resetGrantNum))
            {
                \App\Models\Profile::resetCityGrantNumber($request->nID);
                \App\Models\Log::createLog("cityGrant", "Reset grant number - $request->nID");
                $this->output->addSuccess("Grant number reset");
            }
            elseif (isset($request->manualGrant))
            {
                // Get nation info
                $nation = new \App\Classes\Nation($request->nID);
                $grantInfo = CityGrant::getGrantInfo($request->gNum);
                $grant = CityGrantRequests::addPendGrant($nation, $grantInfo->grantNum, $grantInfo->amount);

                CityGrantRequests::approveGrant($grant->id);
                $this->output->addSuccess("Manual grant sent");
            }
            elseif (isset($request->getGrant))
            {
                // Get grant info
                $grantInfo = CityGrantRequests::getReqInfo($request->gID);
                // Return the view here so we include the grant info
                return self::city($grantInfo); // TODO when it can't find one it thows an exception that isn't a friendly error. Fix that
            }
            elseif (isset($request->editGrant))
            {
                // Edit city grant
                CityGrant::editGrant($request);
                $this->output->addSuccess("City Grant edited");
            }
            elseif (isset($request->deleteGrant))
            {
                // Delete city grant
                CityGrant::deleteGrant($request->gID);
                $this->output->addSuccess("Grant Deleted");
            }
            elseif (isset($request->createGrant))
            {
                CityGrant::createGrant($request);
                $this->output->addSuccess("Grant added");
                \App\Models\Log::createLog("cityGrant", "Added grant");
            }
            else
                throw new \Exception("Couldn't determine function");
        }
        catch (\Exception $e)
        {
            $this->output->addError($e->getMessage());
        }

        return self::city();
    }

    /**
     * GET: admin/entrance.
     *
     * Entrance Aid
     *
     * @return mixed
     */
    public function entrance()
    {
        if (Gate::denies("grants"))
            return view("admin.unauthorized", ["page" => "entrance"]);

        $pendEnt = \App\Models\Grants\EntranceAid::getPendReqs();

        return view("admin.entrance", [
            "page" => "entrance",
            "pendEnt" => $pendEnt,
        ])
            ->with('output', $this->output);
    }

    /**
     * POST: admin/entrance.
     *
     * Approving/Denying entrance aid
     *
     * @param Request $request
     * @return mixed
     */
    public function entrancePost(Request $request)
    {
        if (Gate::denies("grants"))
            return view("admin.unauthorized", ["page" => "entrance"]);

        try
        {
            // Determine function
            if (isset($request->approveGrant))
            {
                \App\Models\Grants\EntranceAid::approveGrant($request->gID);
                $this->output->addSuccess("Entrance Aid Approved");
            }
            elseif ($request->denyGrant)
            {
                \App\Models\Grants\EntranceAid::denyGrant($request->gID);
                $this->output->addSuccess("Grant Denied");
            }
            else
                throw new \Exception("Couldn't determine function");
        }
        catch (\Exception $e)
        {
            $this->output->addError($e->getMessage());
        }

        return self::entrance();
    }

    public function oil()
    {
        if (Gate::denies("grants"))
            return view("admin.unauthorized", ["page" => "oil"]);

        $pendGrants = \App\Models\Grants\OilGrant::getPendReqs();

        return view("admin.oil", [
            "page" => "oil",
            "pendGrants" => $pendGrants,
        ])
            ->with('output', $this->output);
    }

    public function oilPost(Request $request)
    {
        if (Gate::denies("grants"))
            return view("admin.unauthorized", ["page" => "oil"]);

        try
        {
            if (isset($request->approveGrant))
            {
                \App\Models\Grants\OilGrant::acceptGrant($request->gID);
                $this->output->addSuccess("Grant Approved");
            }
            elseif (isset($request->denyGrant))
            {
                \App\Models\Grants\OilGrant::denyGrant($request->gID);
            }
            else
                throw new \Exception("Couldn't determine function");
        }
        catch (\Exception $e)
        {
            $this->output->addError($e->getMessage());
        }

        return self::oil();
    }

    public function nukes()
    {
        if (Gate::denies("grants"))
            return view("admin.unauthorized", ["page" => "nukes"]);

        $pendGrants = \App\Models\Grants\NukeGrants::getPendReqs();

        return view("admin.nuke", [
            "page" => "nukes",
            "pendGrants" => $pendGrants,
        ])
            ->with('output', $this->output);
    }

    public function nukesPost(Request $request)
    {
        if (Gate::denies("grants"))
            return view("admin.unauthorized", ["page" => "nukes"]);

        try
        {
            if (isset($request->approveGrant))
            {
                \App\Models\Grants\NukeGrants::acceptGrant($request->gID);
                $this->output->addSuccess("Grant Approved");
            }
            elseif (isset($request->denyGrant))
            {
                \App\Models\Grants\NukeGrants::denyGrant($request->gID);
            }
            else
                throw new \Exception("Couldn't determine function");
        }
        catch (\Exception $e)
        {
            $this->output->addError($e->getMessage());
        }

        return self::nukes();
    }

    /**
     * GET: admin/activity.
     *
     * Activity Grants
     *
     * @return mixed
     */
    public function activity()
    {
        if (Gate::denies("grants"))
            return view("admin.unauthorized", ["page" => "activity"]);

        $pendGrants = \App\Models\Grants\ActivityGrant::getPendReqs();

        return view("admin.activity", [
            "page" => "activity",
            "pendGrants" => $pendGrants,
        ])
            ->with('output', $this->output);
    }

    /**
     * POST: admin/activity.
     *
     * Activity grant actions
     *
     * @param Request $request
     * @return mixed
     */
    public function activityPost(Request $request)
    {
        if (Gate::denies("grants"))
            return view("admin.unauthorized", ["page" => "activity"]);

        try
        {
            if (isset($request->approveGrant))
            {
                \App\Models\Grants\ActivityGrant::approveGrant($request->gID);
                $this->output->addSuccess("Grant Approved");
            }
            elseif (isset($request->denyGrant))
            {
                \App\Models\Grants\ActivityGrant::denyGrant($request->gID);
            }
            else
                throw new \Exception("Couldn't determine function");
        }
        catch (\Exception $e)
        {
            $this->output->addError($e->getMessage());
        }

        return self::activity();
    }

    public function egr()
    {
        if (Gate::denies("grants"))
            return view("admin.unauthorized", ["page" => "egr"]);

        $pendGrants = \App\Models\Grants\EGRGrant::getPendReqs();

        return view("admin.egr", [
            "page" => "egr",
            "pendGrants" => $pendGrants,
        ])
            ->with('output', $this->output);
    }

    public function egrPost(Request $request)
    {
        if (Gate::denies("grants"))
            return view("admin.unauthorized", ["page" => "egr"]);

        try
        {
            if (isset($request->approveGrant))
            {
                \App\Models\Grants\EGRGrant::acceptGrant($request->gID);
                $this->output->addSuccess("Grant Approved");
            }
            elseif (isset($request->denyGrant))
            {
                \App\Models\Grants\EGRGrant::denyGrant($request->gID);
            }
            else
                throw new \Exception("Couldn't determine function");
        }
        catch (\Exception $e)
        {
            $this->output->addError($e->getMessage());
        }

        return self::egr();
    }
    public function pb()
    {
        if (Gate::denies("grants"))
            return view("admin.unauthorized", ["page" => "pb"]);

        $pendGrants = \App\Models\Grants\pbGrants::getPendReqs();

        return view("admin.pb", [
            "page" => "pb",
            "pendGrants" => $pendGrants,
        ])
            ->with('output', $this->output);
    }

    public function pbPost(Request $request)
    {
        if (Gate::denies("grants"))
            return view("admin.unauthorized", ["page" => "pb"]);

        try
        {
            if (isset($request->approveGrant))
            {
                \App\Models\Grants\pbGrants::acceptGrant($request->gID);
                $this->output->addSuccess("Grant Approved");
            }
            elseif (isset($request->denyGrant))
            {
                \App\Models\Grants\pbGrants::denyGrant($request->gID);
            }
            else
                throw new \Exception("Couldn't determine function");
        }
        catch (\Exception $e)
        {
            $this->output->addError($e->getMessage());
        }

        return self::pb();
    }
    public function irondome()
    {
        if (Gate::denies("grants"))
            return view("admin.unauthorized", ["page" => "irondome"]);

        $pendGrants = \App\Models\Grants\irondomeGrants::getPendReqs();

        return view("admin.irondome", [
            "page" => "irondome",
            "pendGrants" => $pendGrants,
        ])
            ->with('output', $this->output);
    }

    public function irondomePost(Request $request)
    {
        if (Gate::denies("grants"))
            return view("admin.unauthorized", ["page" => "irondome"]);

        try
        {
            if (isset($request->approveGrant))
            {
                \App\Models\Grants\irondomeGrants::acceptGrant($request->gID);
                $this->output->addSuccess("Grant Approved");
            }
            elseif (isset($request->denyGrant))
            {
                \App\Models\Grants\irondomeGrants::denyGrant($request->gID);
            }
            else
                throw new \Exception("Couldn't determine function");
        }
        catch (\Exception $e)
        {
            $this->output->addError($e->getMessage());
        }

        return self::irondome();
    }
    public function mlp()
    {
        if (Gate::denies("grants"))
            return view("admin.unauthorized", ["page" => "mlp"]);

        $pendGrants = \App\Models\Grants\mlpGrants::getPendReqs();

        return view("admin.mlp", [
            "page" => "mlp",
            "pendGrants" => $pendGrants,
        ])
            ->with('output', $this->output);
    }

    public function mlpPost(Request $request)
    {
        if (Gate::denies("grants"))
            return view("admin.unauthorized", ["page" => "mlp"]);

        try
        {
            if (isset($request->approveGrant))
            {
                \App\Models\Grants\mlpGrants::acceptGrant($request->gID);
                $this->output->addSuccess("Grant Approved");
            }
            elseif (isset($request->denyGrant))
            {
                \App\Models\Grants\mlpGrants::denyGrant($request->gID);
            }
            else
                throw new \Exception("Couldn't determine function");
        }
        catch (\Exception $e)
        {
            $this->output->addError($e->getMessage());
        }

        return self::mlp();
    }
    public function cce()
    {
        if (Gate::denies("grants"))
            return view("admin.unauthorized", ["page" => "cce"]);

        $pendGrants = \App\Models\Grants\cceGrants::getPendReqs();

        return view("admin.cce", [
            "page" => "cce",
            "pendGrants" => $pendGrants,
        ])
            ->with('output', $this->output);
    }

    public function ccePost(Request $request)
    {
        if (Gate::denies("grants"))
            return view("admin.unauthorized", ["page" => "cce"]);

        try
        {
            if (isset($request->approveGrant))
            {
                \App\Models\Grants\cceGrants::acceptGrant($request->gID);
                $this->output->addSuccess("Grant Approved");
            }
            elseif (isset($request->denyGrant))
            {
                \App\Models\Grants\cceGrants::denyGrant($request->gID);
            }
            else
                throw new \Exception("Couldn't determine function");
        }
        catch (\Exception $e)
        {
            $this->output->addError($e->getMessage());
        }

        return self::cce();
    }
    public function nrf()
    {
        if (Gate::denies("grants"))
            return view("admin.unauthorized", ["page" => "nrf"]);

        $pendGrants = \App\Models\Grants\nrfGrants::getPendReqs();

        return view("admin.nrf", [
            "page" => "nrf",
            "pendGrants" => $pendGrants,
        ])
            ->with('output', $this->output);
    }

    public function nrfPost(Request $request)
    {
        if (Gate::denies("grants"))
            return view("admin.unauthorized", ["page" => "nrf"]);

        try
        {
            if (isset($request->approveGrant))
            {
                \App\Models\Grants\nrfGrants::acceptGrant($request->gID);
                $this->output->addSuccess("Grant Approved");
            }
            elseif (isset($request->denyGrant))
            {
                \App\Models\Grants\nrfGrants::denyGrant($request->gID);
            }
            else
                throw new \Exception("Couldn't determine function");
        }
        catch (\Exception $e)
        {
            $this->output->addError($e->getMessage());
        }

        return self::nrf();
    }

    /**
     * GET: admin/id.
     *
     * Iron Dome Grants
     *
     * @return mixed
     */
    public function id()
    {
        if (Gate::denies("grants"))
            return view("admin.unauthorized", ["page" => "id"]);

        $pendGrants = \App\Models\Grants\IDGrants::getPendReqs();

        return view("admin.id", [
            "page" => "id",
            "pendGrants" => $pendGrants,
        ])
            ->with('output', $this->output);
    }

    /**
     * POST: admin/id.
     *
     * Iron Dome Actions. Sending/Denying
     *
     * @param Request $request
     * @return mixed
     */
    public function idPost(Request $request)
    {
        if (Gate::denies("grants"))
            return view("admin.unauthorized", ["page" => "id"]);

        try
        {
            if (isset($request->approveGrant))
            {
                \App\Models\Grants\IDGrants::acceptGrant($request->gID);
                $this->output->addSuccess("Grant Approved");
            }
            elseif (isset($request->denyGrant))
            {
                \App\Models\Grants\IDGrants::denyGrant($request->gID);
            }
            else
                throw new \Exception("Couldn't determine function");
        }
        catch (\Exception $e)
        {
            $this->output->addError($e->getMessage());
        }

        return self::id();
    }

    /**
     * GET: admin/settings.
     *
     * Settings page
     *
     * @return mixed
     */
    public function settings()
    {
        if (Gate::denies("settings"))
            return view("admin.unauthorized", ["page" => "settings"]);

        $settings = \App\Models\Settings::getSettings();

        return view("admin.settings", [
            "page" => "settings",
            "settings" => $settings,
        ])
            ->with('output', $this->output);
    }

    /**
     * POST: admin/settings.
     *
     * Editing settings
     *
     * @param Request $request
     * @return mixed
     */
    public function editSettings(Request $request)
    {
        if (Gate::denies("admin") && Gate::denies("grants")) // Prevent unauthorized POSTs
            return view("admin.unauthorized", ["page" => "settings"]);

        $this->output = new Output();
        // I can't think of another way to easily do this :/
        // We'll get an array of all the sKey's that I want to update, then loop over it
        $sKeys = ["warMode", "devMode", "loanSystem", "maxLoan", "loanDuration", "cityGrantSystem", "allianceMarketSystem", "entranceAidSystem", "entranceAidAmount", "activityGrantSystem", "idGrantSystem", "idGrantAmount", "targetTestMode", "spyTestMode", "oilSystem", "nukeprojectSystem", "nukesSystem"];

        try
        {
            foreach ($sKeys as $sKey)
            {
                $query = \App\Models\Settings::where("sKey", $sKey)->firstOrFail();
                $query->value = $request->$sKey;
                $query->save();
            }
            \App\Models\Log::createLog("system", "Edited settings");
            $this->output->addSuccess("Settings updated");
        }
        catch (\Exception $e)
        {
            $this->output->addError("Couldn't update settings - ".$e->getMessage());
        }

        return $this->settings();
    }

    /**
     * GET: admin/logs.
     *
     * View logs
     *
     * @param null $category
     * @return mixed
     */
    public function logs($category = null)
    {
        if (is_null($category) || $category == "all")
            $logs = \App\Models\Log::orderBy("timestamp", "desc")->paginate(50);
        else
            $logs = \App\Models\Log::where("category", $category)->orderBy("timestamp", "desc")->paginate(50);

        // Get page number
        if (isset($_GET["page"]))
            $page = $_GET["page"];
        else
            $page = 1;

        return view("admin.logs", [
            "page" => "logs",
            "logs" => $logs,
            "page" => $page,
        ]);
    }

    /**
     * GET: admin/members.
     *
     * View the members page
     *
     * @return mixed
     */
    public function members()
    {
        if (Gate::denies("members"))
            return view("admin.unauthorized", ["page" => "members"]);

        $cityBreakdown = DefenseNations::getCityBreakdown();
        $cityHistory = DefenseNationHistory::getCity30DayHistory();
        $nations = DefenseNations::getBKNations();

        // Get the code for the audit script
        try
        {
            $auditFile = fopen(__DIR__."/../../Jobs/AuditNations.php", "r");
            $audit = fread($auditFile, filesize(__DIR__."/../../Jobs/AuditNations.php"));
            fclose($auditFile);
        }
        catch (\Exception $e)
        {
            $this->output->addError("Couldn't open audit file");
            $audit = "Couldn't open file";
        }

        return view("admin.members", [
            "page" => "members",
            "cityBreakdown" => $cityBreakdown,
            "cityHistory" => $cityHistory,
            "nations" => $nations,
            "audit" => $audit,
        ])
            ->with('output', $this->output);
    }

    /**
     * View the members of BK and their resources and shit
     *
     * @param int $nID
     * @return $this|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function memberView(int $nID)
    {
        if (Gate::denies("members"))
            return view("admin.unauthorized", ["page" => "members"]);

        try
        {
            $nation = DefenseNations::getNation($nID);
            $siginins = DefenseSignin::getLast10Signins($nID);
            $history = DefenseNationHistory::getNation30DayHistory($nID);
            $taxHistory = Taxes::getMemberTaxHistory($nID);
            $stats = [
                "totalLoaned" => Loans::getTotalMemberLoaned($nID),
                "totalCityGrants" => CityGrantRequests::getTotalMemberSent($nID),
                "totalActivity" => ActivityGrant::getTotalMemberSent($nID),
                "totalTaxed" => Taxes::totalMemberTaxed($nID),
            ];

            $loans = Loans::getLastFiveLoans($nID);
            $cityGrants = CityGrantRequests::getLastFiveGrants($nID);
            $activityGrants = ActivityGrant::getLastFiveGrants($nID);
            $profile = Profile::getProfile($nID); // If no profile exists, It'll create it here!

            return view("admin.memberview", [
                "page" => "members",
                "nation" => $nation,
                "signins" => $siginins,
                "history" => $history,
                "stats" => $stats,
                "taxHistory" => $taxHistory,
                "loans" => $loans,
                "cityGrants" => $cityGrants,
                "activityGrants" => $activityGrants,
                "profile" => $profile,
            ])->with('output', $this->output);
        }
        catch (ModelNotFoundException $e)
        {
            abort(404);
        }
    }

    /**
     * Posting to the member view page. Only function is to update member profile
     *
     * @param Request $request
     */
    public function memberViewPOST(int $nID, Request $request)
    {
        if (Gate::denies("members"))
            return view("admin.unauthorized", ["page" => "members"]);

        // Get profile
        $profile = Profile::getProfile($nID);
        $profile->lastLoan = $request->lastLoan;
        $profile->loanActive = $request->loanActive ?? false;
        $profile->grantPending = $request->grantPending ?? false;
        $profile->lastGrant = $request->lastGrant;
        $profile->lastGrantDate = $request->lastGrantDate;
        $profile->entAid = $request->entAid;
        $profile->lastActivityGrant = $request->lastActivityGrant;
        $profile->pendingActivityGrant = $request->pendingActivityGrant ?? false;
        $profile->gottenIDGrant = $request->gottenIDGrant ?? false;
        $profile->gottencceGrant = $request->gottencceGrant ?? false;
        $profile->gottenmlpGrant = $request->gottenmlpGrant ?? false;
        $profile->gottenirondomeGrant = $request->gottenirondomeGrant ?? false;
        $profile->gottenpbGrant = $request->gottenpbGrant ?? false;
        $profile->gottennrfGrant = $request->gottennrfGrant ?? false;
        $profile->gottenEGRGrant = $request->gottenEGRGrant ?? false;

        $profile->save();

        $this->output->addSuccess("You've edited this profile");

        return $this->memberView($nID);
    }

    /**
     * POST method for the members admin page
     *
     * @param Request $request
     * @return mixed
     */
    public function membersPOST(Request $request)
    {
        // Determine the function
        if (isset($request->cityGrantReminder))
            $this->cityGrantReminder($request->message, $request->cities);
        elseif (isset($request->auditNations))
            $this->auditNations();
        elseif (isset($request->massMessage))
            $this->massMessage($request);
        elseif (isset($request->signInReminder))
            $this->signInReminder();
        else
            $this->output->addError("Couldn't determine function");

        return $this->members();
    }

    /**
     * Send a city grant reminder to some nations
     *
     * @param string $message
     * @param array $cityNums
     */
    protected function cityGrantReminder(string $message, array $cityNums)
    {
        dispatch(new \App\Jobs\CityGrantReminder($message, $cityNums));
        Log::createLog("system", "Ran the City Grant Reminder job");
        $this->output->addSuccess("The City Grant Reminder Job has been added to the queue. Messages should be sent out soon");
    }

    /**
     * Audit nations
     */
    protected function auditNations()
    {
        dispatch(new \App\Jobs\AuditNations());
        Log::createLog("system", "Ran the Audit Nations job");
        $this->output->addSuccess("The Audit Nations job has been added to the queue. Messages should be sent out soon");
    }

    /**
     * Send a custom mass message
     *
     * @param Request $request
     */
    protected function massMessage(Request $request)
    {
        dispatch(new \App\Jobs\MassMessageJob($request->subject, $request->message));
        Log::createLog("system", "Ran the Mass Message job");
        $this->output->addSuccess("The Mass Message job has been added to the queue. Messages should be sent out soon");
    }

    /**
     * Send a sign in reminder
     */
    protected function signInReminder()
    {
        dispatch(new \App\Jobs\SignInReminderJob());
        Log::createLog("system", "Ran the sign in reminder job");
        $this->output->addSuccess("The sign in job has been added to the queue. Messages should be sent out soon");
    }

    /**
     * GET: admin/accounts
     *
     * View and manage accounts
     */
    public function accounts()
    {
        if (Gate::denies("accounts"))
            return view("admin.unauthorized", ["page" => "members"]);

        $accounts = Accounts::all();

        return view("admin.accounts", [
            "page" => "accounts",
            "accounts" => $accounts
        ]);
    }

    public function income()
    {
        $stats = new Classes\Stats();
        $stats->dashboard();

        return view("admin.income", [
            "page" => "income",
            "stats" => $stats
        ]);
    }

    public function spreadsheet($days)
    {
        if (Gate::denies("grants"))
            return view("admin.unauthorized", ["page" => "budget"]);

        return view("admin.budget_spreadsheet", [
            "page" => "budget",
            "days" => $days,
        ]);
    }

    public function budget()
    {
        if (Gate::denies("grants"))
            return view("admin.unauthorized", ["page" => "budget"]);

        return view("admin.budget", [
            "page" => "budget",
        ]);
    }

    public function postBudget(Request $request)
    {
        if (Gate::denies("grants"))
            return view("admin.unauthorized", ["page" => "budget"]);

        return redirect("/budget/{$request->days}");
    }

    /**
     * GET: /ia/recruiting
     *
     * Manage the recruiting script
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function recruiting()
    {
        $status = Status::all();
        $onOff = $status->where("name", "on/off")->first();
        $recruitingTopic = $status->where("name", "recruitTopic")->first();
        $recruitingMessage = $status->where("name", "recruitMessage")->first();
        $nations = Nations::orderBy("nationID", "desc")->paginate(100);

        return view("ia.recruiting", [
            "page" => "recruiting",
            "onOff" => $onOff,
            "recruitTopic" => $recruitingTopic,
            "recruitMessage" => $recruitingMessage,
            "nations" => $nations
        ])->with('output', $this->output);
    }

    /**
     * POST: /ia/recruiting
     *
     * Do the post shit for the recruiting page
     *
     * @param Request $request
     * @throws \Exception
     */
    public function recruitingPOST(Request $request)
    {
        if (isset($request->editOnOff))
        {
            $status = Status::where("name", "on/off")->first();
            $status->status = $request->onOff;
            $status->save();

            $this->output->addSuccess("You've updated the recruiting script status");

            return $this->recruiting();
        }
        else if (isset($request->editMessage))
        {
            $status = Status::where("name", "recruitMessage")->first();
            $status->value = $request->recruitMessage;
            $status->save();

            $this->output->addSuccess("You've updated the recruiting message");

            return $this->recruiting();
        }
        else
            throw new \Exception("Couldn't Determine Route");
    }

    /**
     * GET: /defense/mmr
     *
     * Displays MMR Tiers and stuff
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function mmr()
    {
        if (Gate::denies("targets"))
            return view("admin.unauthorized", ["page" => "targets"]);

        $requirements = MMR::orderBy("cityNum", "asc")->get();

        return view("admin.mmr", [
            "requirements" => $requirements,
            "page" => "mmr",
        ])->with('output', $this->output);
    }

    /**
     * POST: /defense/mmr
     *
     * Handles the MMR POST stuff
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function mmrPOST(Request $request)
    {
        if (Gate::denies("targets"))
            return view("admin.unauthorized", ["page" => "targets"]);

        if (isset($request->editTiers))
            $this->editMMRTiers($request);
        elseif (isset($request->addTier))
            $this->createMMRTier($request);
        else
            throw new \Exception("Couldn't determine route");

        return $this->mmr();

    }

    /**
     * Update the MMR Tiers
     *
     * @param Request $request
     */
    protected function editMMRTiers(Request $request)
    {
        for ($row = 0; $row < count($request->id); $row++)
        {
            $tier = MMR::find($request->id[$row]);
            $tier->cityNum = $request->cityNum[$row];
            $tier->money = $request->money[$row];
            $tier->food = $request->food[$row];
            $tier->uranium = $request->uranium[$row];
            $tier->gas = $request->gas[$row];
            $tier->munitions = $request->munitions[$row];
            $tier->steel = $request->steel[$row];
            $tier->aluminum = $request->aluminum[$row];

            $tier->save();
        }

        Log::createLog("system", "Edited MMR Tiers");

        $this->output->addSuccess("MMR Tiers Updated");
    }

    /**
     * Create an MMR Tier
     *
     * @param Request $request
     */
    protected function createMMRTier(Request $request)
    {
        if (MMR::checkIfTierExists($request->cityNum))
        {
            $this->output->addError("A tier already exists for that city number");
            return;
        }

        $tier = new MMR();
        $tier->cityNum = $request->cityNum;
        $tier->money = $request->money;
        $tier->food = $request->food;
        $tier->uranium = $request->uranium;
        $tier->gas = $request->gas;
        $tier->munitions = $request->munitions;
        $tier->steel = $request->steel;
        $tier->aluminum = $request->aluminum;

        $tier->save();

        Log::createLog("system", "Created a new MMR Tier. ID - ".$tier->id);

        $this->output->addSuccess("MMR Tier Created Successfully");
    }
}
