<?php

namespace App\Models;

use App\Classes\PWBank;
use App\Jobs\SendMoney;
use App\Classes\PWClient;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\DispatchesJobs;

class Loans extends Model
{
    use DispatchesJobs;

    public $timestamps = false;

    /**
     * Get all pending loans.
     *
     * @return Collection
     */
    public static function getPendingLoans() : Collection
    {
        return self::where("isPending", true)->orderBy("timestamp")->get();
    }

    /**
     * Get all active loans.
     *
     * @return Collection
     */
    public static function getActiveLoans() : Collection
    {
        return self::where("isActive", true)->orderBy("due")->get();
}

    /**
     * Get loan info from it's code.
     *
     * @param int $code
     * @return Loans
     */
    public static function getLoanInfo(int $code) : Loans
    {
        return self::where("code", $code)->firstOrFail();
    }

    /**
     * Get loan info by it's ID.
     *
     * @param int $id
     * @return Loans
     */
    public static function getLoanInfoFromID(int $id) : Loans
    {
        return self::where("id", $id)->firstOrFail();
    }

    /**
     * Accept a loan and send it out.
     *
     * @param Request $request
     * @return bool
     * @throws \Exception
     */
    public static function acceptLoan(Request $request) : bool
    {
        $loan = self::getLoanInfoFromID($request->loanID);
        if ($loan->isApproved === true)
            throw new \Exception("That loan has already been approved");
        $due = new \DateTime(); // This shit will determine the due date
        $due->format("Y-m-d");
        $due->add(new \DateInterval("P".$loan->duration."D"));
        $due2 = date_format($due, "Y-m-d");

        $message = "Hi ".$loan->leader.", \n \n Your loan of $".number_format($loan->amount)." has been approved and sent to you. It is due by midnight on ".date_format($due, "l, F j, Y").". Failure to do so will result in you being penalized. \n \n Your loan code is: ".$loan->code." \n To pay back the loan, deposit $".number_format($loan->amount)." into the Rothschilds & Co. with the code as the transaction note. The transaction note should ONLY include the code. The system checks for loans at :05 every hour. Please try not to deposit money at that time otherwise your deposit might not be counted. Please contact us if this happens to you.\n \n You can view info about your loan [link=http://bank.blackbird.im/lookup/".$loan->code."]here[/link].";

        $bank = new PWBank();
        $bank->recipient = $loan->nationName;
        $bank->money = $loan->amount;
        $bank->note = "Loan";

        if (! $bank->checkIfFundsAvailable())
            return false;

        dispatch(new SendMoney($bank, $loan->leader, "Your Loan has been Approved!", $message));

        // Update loan in the database to reflect that it's send
        $loan->isPending = 0;
        $loan->isActive = 1;
        $loan->isApproved = 1;
        $loan->due = $due2;
        $loan->save();

        \App\Models\Log::createLog("loan", "Approved loan - ".$loan->code);

        return true;
    }

    /**
     * Deny a loan.
     *
     * @param Request $request
     * @return bool
     * @throws \Exception
     */
    public static function denyLoan(Request $request) : bool
    {
        // Get loan info and update it to represent that it was denied
        $loan = self::getLoanInfoFromID($request->loanID);
        $loan->isPending = 0;
        $loan->isDenied = 1;
        $loan->save();

        // Get and update profile
        $profile = \App\Models\Profile::getProfile($loan->nationID);
        $profile->loanActive = 0;
        $profile->save();

        // Send message telling them their loan was denied
        $client = new PWClient();
        $client->login();
        $message = "Hi ".$loan->leader.", \n \n Your loan of $".number_format($loan->amount)." has been denied. If you would like to know why, please [link=http://bank.blackbird.im/contact]contact us[/link].";
        $client->login($loan->leader, "Your loan has been denied", $message);

        \App\Models\Log::createLog("loan", "Denied Loan - ".$loan->code);

        return true;
    }

    /**
     * Send a manual loan.
     *
     * @param Request $request
     * @return bool
     * @throws \Exception
     */
    public static function manualLoan(Request $request) : bool
    {
        $nation = new \App\Classes\Nation($request->nID);

        // Create new entry in loans DB
        $loan = new self;
        $code = \App\Classes\Codes::generateCode();
        $loan->code = $code;
        $loan->nationID = $nation->nID;
        $loan->nationName = $nation->nationName;
        $loan->amount = $request->amount;
        $loan->originalAmount = $request->amount;
        $loan->due = $request->due;
        $loan->isApproved = true;
        $loan->isActive = true;
        $loan->reason = "Manual Loan";
        $loan->leader = $nation->leader;
        $loan->score = $nation->score;
        $loan->isPending = false;
        $loan->save();

        // Get profile and update it with an active loan
        $profile = \App\Models\Profile::getProfile($nation->nID);
        $profile->loanActive = true;
        $profile->save();

        $client = new PWClient();
        $client->login();

        // Because this is a MANUAL loan, I'm not going to use a job for this one

        // Now send that shit but check if it was meant to send the money or not
        if ($request->sendMoney == "yes")
            self::sendLoan($loan, $client);

        // Setup and send message
        $date = new \DateTime($request->due);
        $message = "Hello ".$nation->leader." \n \n A manual loan of $".number_format($loan->amount)." with the loan code of: ".$code." has been created for you. Make sure to pay back the full amount by ".date_format($date, "F j, Y")." or you may face penalties. To pay it back, deposit the amount into the Rothschilds & Co. bank with the code as the transaction note.";
        $client->sendMessage($nation->leader, "Manual Loan Information", $message);

        Log::createLog("loan", "Created manual loan - ".$code);

        return true;
    }

    /**
     * Send the loans money to the nation.
     *
     * This method is only used with the manual loan
     *
     * @param Loans $loan
     * @throws \Exception
     * @deprecated 3.2.5 This should only be used if you HAVE to
     */
    private static function sendLoan(Loans $loan, PWClient $client)
    {
        $bank = new \App\Classes\PWBank();
        $bank->recipient = $loan->nationName;
        $bank->money = $loan->amount;
        $bank->note = "Loan";
        $bank->send($client);
    }

    /**
     * Delete a loan.
     *
     * @param Request $request
     * @return bool
     */
    public static function deleteLoan(Request $request) : bool
    {
        self::where("id", $request->loanID)->delete();
        Log::createLog("loan", "Deleted loan - ".$request->loanID);

        return true;
    }

    /**
     * Count the pending loan requests.
     *
     * @return null|int
     */
    public static function countPendReqs()
    {
        $num = self::where("isPending", true)->count();

        return $num > 0 ? $num : null;
    }

    /**
     * Set a loan to complete when the loan is paid off.
     *
     * @param PWClient $client
     * @throws \Exception
     */
    public function loanComplete(PWClient $client)
    {
        // Update loan record
        $this->isPaid = true;
        $this->isActive = false;
        $this->save();

        // Get profile and update it
        $profile = \App\Models\Profile::getProfile($this->nationID);
        $profile->loanActive = false;
        $profile->lastLoan = date("Y-m-d");
        $profile->save();

        // Send message confirming loan paid
        $message = "Hi {$this->leader}, \n \n This message is being sent to you to confirm that the loan with the code {$this->code} has been successfully paid off.";
        $client->sendMessage($this->leader, "Loan Paid Off", $message);
    }

    /**
     * Make a payment towards the loan.
     *
     * @param int $value
     * @param PWClient $client
     * @throws \Exception
     */
    public function makePayment(int $value, PWClient $client)
    {
        // Calculate new amount due
        $this->amount -= $value;
        $this->save();
        $due = new \DateTime($this->due);
        $message = "Hi {$this->leader}, \n \n This message is being sent to you to confirm a payment of $".number_format($value)." with the loan code of {$this->code}. The amount left on your loan is now $".number_format($this->amount).". Make sure to pay the loan back before midnight on ".date_format($due, "l, F j, Y")." or else you will face penalties.";
        $client->sendMessage($this->leader, "Payment Confirmed", $message);
        Log::createLog("loan", "Paid back $value with code $this->code");
    }

    /**
     * If there was a payment error, we'll want to send them a message.
     *
     * @param PWClient $client
     * @throws \Exception
     */
    public function paymentError(PWClient $client)
    {
        // This runs when they pay back more or we can't process the request for some reason
        $message = "Hi $this->leader, \n \n This message is being sent to you to inform you that there was an error when processing your loan payment. Either you paid too much or there was an error. Please contact us (link below) and tell us if you paid too much or if there was simply an error. Include your loan code ($this->code) in the message.";
        $client->sendMessage($this->leader, "Payment Error", $message);
        Log::createLog("loan", "Loan error. Paid back too much - $loan->code");
    }

    /**
     * Get today's date.
     *
     * @return string yyyy-mm-dd
     */
    public static function getToday() : string
    {
        $today = new \DateTime();
        $today->format("Y-m-d");

        return date_format($today, "Y-m-d");
    }

    /**
     * Get all the loans that are late.
     *
     * @return Collection
     */
    public static function getLateLoans() : Collection
    {
        $expire = self::getToday();

        return self::where("due", "<", $expire)->where("isPaid", "false")->get();
    }

    /**
     * Get all the loans that are due today.
     *
     * @return Collection
     */
    public static function getLoansDueToday() : Collection
    {
        $today = self::getToday();

        return self::where("due", $today)->where("isPaid", "false")->get();
    }

    /**
     * Counts how much a nation has loaned out.
     *
     * @param int $nID
     * @return int
     */
    public static function getTotalMemberLoaned(int $nID) : int
    {
        return self::where("isApproved", true)->where("nationID", $nID)->sum("originalAmount");
    }

    /**
     * Returns a member's last 5 loans.
     *
     * @param int $nID
     * @return Collection
     */
    public static function getLastFiveLoans(int $nID) : Collection
    {
        return self::where("nationID", $nID)
            ->orderBy("timestamp", "desc")
            ->take(5)
            ->get();
    }
}
