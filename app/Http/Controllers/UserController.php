<?php

namespace App\Http\Controllers;

use App\Classes\Output;
use App\Defense\DefenseNationHistory;
use App\Defense\DefenseNations;
use App\Defense\DefenseSignin;
use App\Defense\Warchest;
use App\Exceptions\UserErrorException;
use App\Models\AccountLogs;
use App\Models\Accounts;
use App\Models\Grants\CityGrantRequests;
use App\Models\Loans;
use App\Models\Taxes;
use App\Models\Transactions;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Auth;
use Gate;

class UserController extends Controller
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
     * UserController constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->middleware("auth");
        $this->middleware("verified");
        $this->output = new Output();
        $this->request = $request;
    }
    /**
     * Display the user's account page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function bankAccountsOtheraa()
    {
        // Get the user's accounts
        $accounts = Auth::user()->accounts;

        return view("bankAccounts.templates.outsidetransferaa", [
            "output" => $this->output,
            "accounts" => $accounts
        ]);
    }
    /**
     * Display the user's account page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function bankAccountsOther()
    {
        // Get the user's accounts
        $accounts = Auth::user()->accounts;

        return view("bankAccounts.templates.outsidetransfer", [
            "output" => $this->output,
            "accounts" => $accounts
        ]);
    }
    /**
     * Display the user's account page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function bankAccounts()
    {
        // Get the user's accounts
        $accounts = Auth::user()->accounts;

        return view("bankAccounts.main", [
            "output" => $this->output,
            "accounts" => $accounts
        ]);
    }

    /**
     * POST method for any POST requests coming from the user's account page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function bankAccountsPost()
    {
        try
        {
            // Route the post request
            if (isset($this->request->createAccount))
                $this->createBankAccount();
            elseif (isset($this->request->deleteAccount))
                $this->deleteBankAccount();
            elseif (isset($this->request->transfer))
                $this->transfer();
            else
                throw new UserErrorException("Couldn't determine route");
        }
        catch (UserErrorException $e)
        {
            $this->output->addError($e->getMessage());
        }

        return $this->bankAccounts();
    }
    /**
     * POST method for any POST requests coming from the user's account page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function bankAccountsPostother()
    {
        try
        {
            // Route the post request
            if (isset($this->request->createAccount))
                $this->createBankAccount();
            elseif (isset($this->request->deleteAccount))
                $this->deleteBankAccount();
            elseif (isset($this->request->transfer))
                $this->transferAlliance();
            else
                throw new UserErrorException("Couldn't determine route");
        }
        catch (UserErrorException $e)
        {
            $this->output->addError($e->getMessage());
        }

        return $this->bankAccounts();
    }
    /**
     * POST method for any POST requests coming from the user's account page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function bankAccountsPostnation()
    {
        try
        {
            // Route the post request
            if (isset($this->request->createAccount))
                $this->createBankAccount();
            elseif (isset($this->request->deleteAccount))
                $this->deleteBankAccount();
            elseif (isset($this->request->transfer))
                $this->transferaa();
            else
                throw new UserErrorException("Couldn't determine route");
        }
        catch (UserErrorException $e)
        {
            $this->output->addError($e->getMessage());
        }

        return $this->bankAccounts();
    }
    /**
     * Create a bank account
     */
    public function createBankAccount()
    {
        $this->validate($this->request, [
           "accountName" => "required|max:40"
        ]);

        $account = Accounts::createAccount($this->request->accountName, Auth::user()->nID);

        $this->output->addSuccess("You've created an account!");
    }

    /**
     * I wonder what this does..... Delete a bank account
     *
     * @throws UserErrorException
     */
    public function deleteBankAccount()
    {
        // Verify that the current user owns the account
        try
        {
            $delAccount = Accounts::findOrFail($this->request->accountID);
        }
        catch (ModelNotFoundException $e)
        {
            throw new UserErrorException("That account doesn't exist"); // lol throw exception in a catch
        }

        $delAccount->deleteAccount();

        $this->output->addSuccess("You've deleted that account");
    }

    /**
     * View an account
     *
     * @param int $accountID
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function viewAccount(int $accountID)
    {
        // Get the account info
        $account = Accounts::find($accountID);

        $account->load("logs");

        if ($account == null || $account->count() == 0)
            abort(404);

        if (Auth::user()->nID != $account->nID)
        {
            if (Gate::denies("accounts") || !Auth::user()->isAdmin)
                return view("bankAccounts.notOwnAccount");
        }

        $transactions = Transactions::getLastTransactions($account->id);

        $transactions->load("fromAccountRel", "toAccountRel");

        return view("bankAccounts.viewAccount", [
            "output" => $this->output,
            "account" => $account,
            "transactions" => $transactions,
        ]);
    }

    /**
     * Post route for viewing account
     *
     * @param int $accountID
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function viewAccountPost(int $accountID)
    {
        try
        {
            if (isset($this->request->createDeposit))
                $this->deposit($accountID);
            elseif (isset($this->request->editAccount))
                $this->editAccount($accountID);
            else
                throw new UserErrorException("Couldn't determine route");
        }
        catch (UserErrorException $e)
        {
            $this->output->addError($e->getMessage());
        }

        return $this->viewAccount($accountID);
    }

    /**
     * Creates a deposit request for an account
     *
     * @param int $accountID
     * @throws UserErrorException
     */
    protected function deposit(int $accountID)
    {
        // Get the account
        $account = Accounts::find($accountID);

        if ($account === null || $account->count() === 0)
            throw new UserErrorException("That account doesn't exist");

        // Verify that the submitting user owns this account
        if (Auth::user()->nID != $account->nID)
            throw new UserErrorException("You don't own that account, silly billy");

        // Now call to create the deposit request
        $account->createDepositRequest();

        $this->output->addSuccess("Deposit request added successfully. Please check the top left corner of this page");
    }

    /**
     * Edits the account's values
     *
     * @param int $accountID
     * @throws UserErrorException
     */
    protected function editAccount(int $accountID)
    {
        $account = Accounts::find($accountID);

        $account->editAccount($this->request);

        $this->output->addSuccess("Account Edited");
    }

    /**
     * Start a transfer
     */
    public function transfer()
    {
        // Get the account that is being transferred from
        $account = Accounts::find($this->request->from);

        if ($account == null || $account->count() == 0)
        {
            $this->output->addError("That account doesn't exist");

            return $this->bankAccounts();
        }

        if (Auth::user()->nID != $account->nID)
        {
            $this->output->addError("You don't own that account");

            return $this->bankAccounts();
        }

        try
        {
            if ($this->request->to === "nation")
            {
                if ($account->transferNation($this->request))
                {
                    $this->output->addSuccess("Withdraw has been requested. The withdraw could take up to 5 minutes.");
                }
                else
                {
                    // If this is false, then we didn't have enough resources to complete the transaction
                    $this->output->addError("The Bank didn't have enough funds to complete your transaction. Please contact the Archduke of Economics to solve this issue.");
                }
            }
            else
            {
                $account->transferAccount($this->request->to, $this->request);
                $this->output->addSuccess("Transfer successful.");
            }

        }
        catch (UserErrorException $e)
        {
            $this->output->addError($e->getMessage());
        }

        return $this->bankAccounts();
    }
    /**
     * Start a transfer
     */
    public function transferAlliance()
    {
        // Get the account that is being transferred from
        $account = Accounts::find($this->request->from);

        if ($account == null || $account->count() == 0)
        {
            $this->output->addError("That account doesn't exist");

            return $this->bankAccounts();
        }

        if (Auth::user()->nID != $account->nID)
        {
            $this->output->addError("You don't own that account");

            return $this->bankAccounts();
        }

        try
        {
            if ($this->request->to === "allianceOther")
            {
                if ($account->transferAllianceOther($this->request))
                {
                    $this->output->addSuccess("Withdraw has been requested. The withdraw could take up to 5 minutes.");
                }
                else
                {
                    // If this is false, then we didn't have enough resources to complete the transaction
                    $this->output->addError("The Bank didn't have enough funds to complete your transaction. Please contact the Archduke of Economics to solve this issue.");
                }
            }
            else
            {
                $account->transferAccount($this->request->to, $this->request);
                $this->output->addSuccess("Transfer successful.");
            }

        }
        catch (UserErrorException $e)
        {
            $this->output->addError($e->getMessage());
        }

        return $this->bankAccounts();
    }
    /**
     * Start a transfer
     */
    public function bankAccountsPostaa()
    {
        // Get the account that is being transferred from
        $account = Accounts::find($this->request->from);

        if ($account == null || $account->count() == 0)
        {
            $this->output->addError("That account doesn't exist");

            return $this->bankAccounts();
        }

        if (Auth::user()->nID != $account->nID)
        {
            $this->output->addError("You don't own that account");

            return $this->bankAccounts();
        }

        try
        {
            if ($this->request->to === "nationOther")
            {
                if ($account->transferNationOther($this->request))
                {
                    $this->output->addSuccess("Withdraw has been requested. The withdraw could take up to 5 minutes.");
                }
                else
                {
                    // If this is false, then we didn't have enough resources to complete the transaction
                    $this->output->addError("The Bank didn't have enough funds to complete your transaction. Please contact the Archduke of Economics to solve this issue.");
                }
            }
            else
            {
                $account->transferAccount($this->request->to, $this->request);
                $this->output->addSuccess("Transfer successful.");
            }

        }
        catch (UserErrorException $e)
        {
            $this->output->addError($e->getMessage());
        }

        return $this->bankAccounts();
    }
    /**
     * GET: /user/dashboard
     *
     * Dashboard for the user which displays a lot of useful information
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function dashboard()
    {
        $defNation = DefenseNations::getNation(Auth::user()->nID);
        if (! $defNation->inBK) // If they're not in Camelot, redirect to home because fuck them
            return redirect("/");

        $mmrScore = Warchest::mmrScoreFromDefNations($defNation);
        $totalTaxed = Taxes::totalMemberTaxed(Auth::user()->nID);
        $taxHistory = Taxes::getAllMemberTaxHistory(Auth::user()->nID);
        $nationHistory = DefenseNationHistory::getAllMemberHistory(Auth::user()->nID);
        $totalCityGrants = CityGrantRequests::getTotalMemberSent(Auth::user()->nID);
        $signInHistory = DefenseSignin::getLastYearSignins(Auth::user()->nID);
        $totalLoaned = Loans::getTotalMemberLoaned(Auth::user()->nID);

        $signInResources = [
            "money" => [
                "name" => "Money",
                "variable" => "money",
                "color" => "rgba(0, 150, 136, 0.5)"
            ],
            "steel" => [
                "name" => "Steel",
                "variable" => "steel",
                "color" => "rgba(66, 66, 66, .5)"
            ],
            "gas" => [
                "name" => "Gasoline",
                "variable" => "gas",
                "color" => "rgba(244, 67, 54, 0.5)"
            ],
            "aluminum" => [
                "name" => "Aluminum",
                "variable" => "aluminum",
                "color" => "rgba(156, 39, 176, 0.5)"
            ],
            "munitions" => [
                "name" => "Munitions",
                "variable" => "munitions",
                "color" => "rgba(255, 152, 0, 0.5)"
            ],
            "uranium" => [
                "name" => "Uranium",
                "variable" => "uranium",
                "color" => "rgba(76, 175, 80, 0.5)"
            ],
            "food" => [
                "name" => "Food",
                "variable" => "food",
                "color" => "rgba(3, 169, 244, 0.5)"
            ],
        ];

        return view("dashboard", [
            "output" => $this->output,
            "nation" => $defNation,
            "mmrScore" => $mmrScore,
            "totalTaxed" => $totalTaxed,
            "taxHistory" => $taxHistory,
            "nationHistory" => $nationHistory,
            "totalCityGrants" => $totalCityGrants,
            "signInHistory" => $signInHistory,
            "signInResources" => $signInResources,
            "totalLoaned" => $totalLoaned
        ]);
    }

    /**
     * GET: /user/export
     *
     * Gets shit about the nation and exports it as JSON
     *
     * @return mixed
     */
    public function userExport()
    {
        $nID = Auth::user()->nID;

        $memberHistory = DefenseNationHistory::getAllMemberHistory($nID);
        $taxHistory = Taxes::getLiterallyEverythingHolyFuckForNation($nID);
        $signIns = DefenseSignin::getAllSignInsForNation($nID);

        // Build return array
        $return = [
            "history" => $memberHistory->toArray(),
            "taxes" => $taxHistory->toArray(),
            "signIns" => $signIns->toArray(),
        ];

        return response(\json_encode($return, 129), 200)
            ->header("Content-Type", "application/json");
    }
}
