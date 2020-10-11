<?php

namespace App\Models;

use App\Classes\Codes;
use App\Classes\Nation;
use App\Classes\PWBank;
use App\Classes\PWClient;
use App\Exceptions\UserErrorException;
use App\Jobs\CreateDepositRequest;
use App\Jobs\SendMoney;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;
use Gate;

class Accounts extends Model
{
    use SoftDeletes;

    protected $fillable = ["name", "nID"];

    /**
     * Relation with the transactions that are from this account
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fromTransactions()
    {
        return $this->hasMany('\App\Models\Transactions', "fromAccountID");
    }

    /**
     * Relationship with the transactions that are to this account
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function toTransactions()
    {
        return $this->hasMany('\App\Models\Transactions', "toAccountID");
    }

    /**
     * Relationship between the account and it's many deposits
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function deposits()
    {
        return $this->hasMany('\App\Models\Deposits', "accountID");
    }

    /**
     * Relationship between the account and it's account logs
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function logs()
    {
        return $this->hasMany('App\Models\AccountLogs', "accountID");
    }

    /**
     * Creates an account
     *
     * @param string $name
     * @param int $nID
     * @return static
     */
    public static function createAccount(string $name, int $nID)
    {
        return self::create([
            "name" => $name,
            "nID" => $nID,
        ]);
    }

    /**
     * Properly deletes an account
     *
     * @throws UserErrorException
     */
    public function deleteAccount()
    {
        if (Auth::user()->nID != $this->nID)
            throw new UserErrorException("You don't own that account");

        // Verify that there's nothing left in the account.
        // Loop over the array of checking values
        $check = ["money", "coal", "oil", "uranium", "iron", "bauxite", "gas", "munitions", "steel", "aluminum", "food", "lead"];
        foreach ($check as $c)
        {
            if ($this->{$c} > 0)
                throw new UserErrorException("You can't delete an account that still has money/resources in it. Transfer out your stuff and then retry.");
        }

        $this->delete();
    }

    /**
     * Transfer stuff to an account
     *
     * @param int $accountID
     * @param Request $request
     * @throws UserErrorException
     */
    public function transferAccount(int $accountID, Request $request)
    {
        $toAccount = Accounts::find($accountID);

        if ($toAccount == null || $toAccount->count() == 0)
            throw new UserErrorException("That account you are trying to send to does not exist");
            
        if ($toAccount->id == $this->id)
            throw new UserErrorException("You can't transfer to and from the same account, dumbass.");

        $resources = ["money", "coal", "oil", "uranium", "iron", "bauxite", "gas", "munitions", "steel", "aluminum", "food", "lead"]; // So we can loop easily

        $total = 0; // We'll use this to make sure they're not transferring nothing

        // Loop over the resources and subtract/add the resources
        foreach ($resources as $res)
        {
            // Verify that the account has the right amounts to transfer
            if ($this->$res < $request->$res)
                throw new UserErrorException("You don't have enough {$res} to complete that transfer");

            if ($request->$res < 0)
                throw new UserErrorException("You entered a negative value for {$res}. SWIPER NO SWIPING");

            $total += $request->$res;

            $this->$res -= $request->$res; // Subtract if from this account
            $toAccount->$res += $request->$res; // Add it to the transferring account
        }

        if ($total < 0.01)
            throw new UserErrorException("You can't transfer nothing dummy!");

        // Now save both accounts
        $this->save();
        $toAccount->save();

        // Create transaction
        $transaction = [
            "fromAccountID" => $this->id,
            "toAccountID" => $toAccount->id,
            "fromAccount" => true,
            "toAccount" => true,
        ];

        foreach ($resources as $res)
            $transaction[$res] = $request->$res; // Add whatever money/resources were moved

        Transactions::create($transaction);
    }

    /**
     * Transfer stuff to a nation
     *
     * @param Request $request
     * @return bool
     * @throws UserErrorException
     */
    public function transferNation(Request $request) : bool
    {
        // Get the nation we're sending shit to
        $nation = new Nation($this->nID);

        $bank = new PWBank();

        $resources = ["money", "coal", "oil", "uranium", "iron", "bauxite", "gas", "munitions", "steel", "aluminum", "food", "lead"]; // So we can loop easily

        $total = 0; // We'll use this to make sure they're not transferring nothing

        // Loop over the resources and verify shit
        foreach ($resources as $res)
        {
            // Verify that the account has the right amounts to transfer
            if ($this->$res < $request->$res)
                throw new UserErrorException("You don't have enough {$res} to complete that transfer");

            if ($request->$res < 0)
                throw new UserErrorException("You entered a negative value for {$res}. SWIPER NO SWIPING");

            $total += $request->$res;

            $this->$res -= $request->$res; // Subtract if from this account
        }

        if ($total < 0.01)
            throw new UserErrorException("You can't withdraw nothing dumbo!");

        // Add the stuff to the bank
        $bank->recipient = $nation->nationName;
        $bank->note = "Withdraw from {$this->name}";
        $bank->money = $request->money;
        $bank->coal = $request->coal;
        $bank->oil = $request->oil;
        $bank->uranium = $request->uranium;
        $bank->iron = $request->iron;
        $bank->bauxite = $request->bauxite;
        $bank->gasoline = $request->gas;
        $bank->munitions = $request->munitions;
        $bank->steel = $request->steel;
        $bank->aluminum  = $request->aluminum;
        $bank->food = $request->food;
        $bank->lead = $request->lead;

        // Verify we have enough stored to send this request
        if (! $bank->checkIfFundsAvailable())
            return false;

        // Setup message
        $message = "Hi $nation->leader,\n\n This message is being sent to you to confirm your successful withdraw from {$this->name}\n\n";

        foreach ($resources as $res)
        {
            // Here we'll build a 'receipt' for the transaction showing them what was sent. lol I know it looks like crap
            $message .= ucfirst($res) . " - " . number_format($request->$res, 2) . "\n";
        }

        // Create transaction
        $transaction = [
            "fromAccountID" => $this->id,
            "fromAccount" => true,
            "toAccount" => false,
            "toName" => $nation->nationName,
        ];

        foreach ($resources as $res)
            $transaction[$res] = $request->$res; // Add whatever money/resources were moved

        // Save this account
        $this->save();

        Transactions::create($transaction);

        dispatch(new SendMoney($bank, $nation->leader, "Withdraw Confirmation", $message));

        return true;
    }
    /**
     * Transfer stuff to a nation
     *
     * @param Request $request
     * @return bool
     * @throws UserErrorException
     */
    public function transferNationOther(Request $request) : bool
    {
        // Get the nation we're sending shit to
        $nation = new Nation($this->nID);

        $bank = new PWBank();

        $resources = ["money", "coal", "oil", "uranium", "iron", "bauxite", "gas", "munitions", "steel", "aluminum", "food", "lead"]; // So we can loop easily

        $total = 0; // We'll use this to make sure they're not transferring nothing

        // Loop over the resources and verify shit
        foreach ($resources as $res)
        {
            // Verify that the account has the right amounts to transfer
            if ($this->$res < $request->$res)
                throw new UserErrorException("You don't have enough {$res} to complete that transfer");

            if ($request->$res < 0)
                throw new UserErrorException("You entered a negative value for {$res}. SWIPER NO SWIPING");

            $total += $request->$res;

            $this->$res -= $request->$res; // Subtract if from this account
        }

        if ($total < 0.01)
            throw new UserErrorException("You can't withdraw nothing dumbo!");

        // Add the stuff to the bank
        $bank->recipient = $request->nationname;
        $bank->note = "Transfer from $nation->nationName";
        $bank->money = $request->money;
        $bank->coal = $request->coal;
        $bank->oil = $request->oil;
        $bank->uranium = $request->uranium;
        $bank->iron = $request->iron;
        $bank->bauxite = $request->bauxite;
        $bank->gasoline = $request->gas;
        $bank->munitions = $request->munitions;
        $bank->steel = $request->steel;
        $bank->aluminum  = $request->aluminum;
        $bank->food = $request->food;
        $bank->lead = $request->lead;

        // Verify we have enough stored to send this request
        if (! $bank->checkIfFundsAvailable())
            return false;

        // Setup message
        $message = "Hi $nation->leader,\n\n This message is being sent to you to confirm your successful withdraw from {$this->name}\n\n";

        foreach ($resources as $res)
        {
            // Here we'll build a 'receipt' for the transaction showing them what was sent. lol I know it looks like crap
            $message .= ucfirst($res) . " - " . number_format($request->$res, 2) . "\n";
        }

        // Create transaction
        $transaction = [
            "fromAccountID" => $this->id,
            "fromAccount" => true,
            "toAccount" => false,
            "toName" => $request->nationname,
        ];

        foreach ($resources as $res)
            $transaction[$res] = $request->$res; // Add whatever money/resources were moved

        // Save this account
        $this->save();

        Transactions::create($transaction);

        dispatch(new SendMoney($bank, $nation->leader, "Withdraw Confirmation", $message));

        return true;
    }
    /**
     * Transfer stuff to a alliance
     *
     * @param Request $request
     * @return bool
     * @throws UserErrorException
     */
    public function transferAllianceOther(Request $request) : bool
    {
        // Get the nation we're sending shit to
        $nation = new Nation($this->nID);

        $bank = new PWBank();

        $resources = ["money", "coal", "oil", "uranium", "iron", "bauxite", "gas", "munitions", "steel", "aluminum", "food", "lead"]; // So we can loop easily

        $total = 0; // We'll use this to make sure they're not transferring nothing

        // Loop over the resources and verify shit
        foreach ($resources as $res)
        {
            // Verify that the account has the right amounts to transfer
            if ($this->$res < $request->$res)
                throw new UserErrorException("You don't have enough {$res} to complete that transfer");

            if ($request->$res < 0)
                throw new UserErrorException("You entered a negative value for {$res}. SWIPER NO SWIPING");

            $total += $request->$res;

            $this->$res -= $request->$res; // Subtract if from this account
        }

        if ($total < 0.01)
            throw new UserErrorException("You can't withdraw nothing dumbo!");

        // Add the stuff to the bank
        $bank->recipient = $request->alliancename;
        $bank->type = "Alliance";
        $bank->note = "Transfer from $nation->nationName";
        $bank->money = $request->money;
        $bank->coal = $request->coal;
        $bank->oil = $request->oil;
        $bank->uranium = $request->uranium;
        $bank->iron = $request->iron;
        $bank->bauxite = $request->bauxite;
        $bank->gasoline = $request->gas;
        $bank->munitions = $request->munitions;
        $bank->steel = $request->steel;
        $bank->aluminum  = $request->aluminum;
        $bank->food = $request->food;
        $bank->lead = $request->lead;

        // Verify we have enough stored to send this request
        if (! $bank->checkIfFundsAvailable())
            return false;

        // Setup message
        $message = "Hi $nation->leader,\n\n This message is being sent to you to confirm your successful withdraw from {$this->name}\n\n";

        foreach ($resources as $res)
        {
            // Here we'll build a 'receipt' for the transaction showing them what was sent. lol I know it looks like crap
            $message .= ucfirst($res) . " - " . number_format($request->$res, 2) . "\n";
        }

        // Create transaction
        $transaction = [
            "fromAccountID" => $this->id,
            "fromAccount" => true,
            "toAccount" => false,
            "toName" => $request->alliancename,
        ];

        foreach ($resources as $res)
            $transaction[$res] = $request->$res; // Add whatever money/resources were moved

        // Save this account
        $this->save();

        Transactions::create($transaction);

        dispatch(new SendMoney($bank, $nation->leader, "Withdraw Confirmation", $message));

        return true;
    }

    /**
     * Creates a deposit request for the account
     */
    public function createDepositRequest()
    {
        // Generate unique code
        $code = Codes::generateCode();

        $deposit = Deposits::create([
            "accountID" => $this->id,
            "code" => $code,
        ]);
        echo"Please use the following code:$code. Link: https://politicsandwar.com/alliance/id=7399&display=bank#deposit";
        dispatch(new CreateDepositRequest($deposit));
    }

    /**
     * Deposit stuff into the account
     *
     * @param array $deposit
     * @param PWClient $client
     */
    public function deposit(array $deposit, PWClient $client)
    {
        foreach ($deposit as $key => $value)
        {
            $this->$key += $value;
        }

        // Now send them a confirmation message
        $nation = new Nation($this->nID);

        $message = "Hi {$nation->leader},\n\nThis message is being sent to you to confirm a deposit. Values are listed below:\n\n";

        // Add a transaction
        $tranSetup = [
            "fromAccountID" => null,
            "toAccountID" => $this->id,
            "fromAccount" => false,
            "toAccount" => true,
            "fromName" => $nation->nationName,
        ];

        foreach ($deposit as $key => $value)
        {
            $message .= ucfirst($key) . " - " . number_format($value, 2) . "\n";
            $tranSetup[$key] = $value;
        }

        // Send them a message
        $client->sendMessage($nation->leader, "Deposit Confirmation", $message);

        $this->save();

        // Create the transaction
        Transactions::create($tranSetup);
    }

    public function editAccount(Request $request)
    {
        // Do some verification
        if (!Auth::user()->isAdmin)
            throw new UserErrorException("You're not an admin");

        if (Gate::denies("accounts"))
            throw new UserErrorException("You don't have permission to edit accounts");

        if ($this->nID == Auth::user()->nID)
            throw new UserErrorException("You can't edit your own accounts");

        $total = 0; // Used later to determine if anything is actually changing

        // Now we can actually do shit
        $resources = ["money", "coal", "oil", "uranium", "iron", "bauxite", "gas", "munitions", "steel", "aluminum", "food", "lead"];

        $log = new AccountLogs();

        foreach ($resources as $res)
        {
            // Setup log
            $log->$res = $request->$res;
            // Add/subtract from account
            $this->$res += $request->$res;

            // Check if after adding/subtracting the resources, if it's less than 0
            if ($this->$res < 0)
                throw new UserErrorException("You can't set their {$res} to less than 0, dumbass");

            $total += $request->$res;
        }

        if ($total == 0)
            throw new UserErrorException("Why even try to edit their account? I fucking hate people");

        // Add further details to the log
        $log->editor = Auth::user()->username;
        $log->accountID = $this->id;

        // Save both models
        $this->save();
        $log->save();
    }
}
