<?php

namespace App\Jobs;

use App\Classes\Nation;
use App\Classes\PWClient;
use App\Models\Accounts;
use App\Models\Deposits;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateDepositRequest implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Holds the deposit request
     *
     * @var Deposits
     */
    protected $deposit;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Deposits $deposit)
    {
        $this->deposit = $deposit;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $client = new PWClient();
        $client->login();

        // Get nation info
        $nation = new Nation($this->deposit->account->nID);

        // Setup message
        $message = "Hi {$nation->leader},\n\n You've submitted a deposit request for the account named: {$this->deposit->account->name}.\n\nThe code for your deposit is: {$this->deposit->code}\n\nPlease send whatever money and resources you want to deposit into your account into the bank using the code above as the transaction, just like how you make a payment towards a loan.\n\nPlease note that the system checks for deposits at five past the hour, so if you make a deposit during that time, your deposit [b]will not count[/b]. So please try to avoid that. Additionally, this means that your deposit will not show up in your account until then. You will receive a confirmation email when your deposit is processed. If you not get a message within two hours, please contact us.";

        // Now send them a message
        $client->sendMessage($nation->leader, "Deposit Request", $message);
    }
}
