<?php

namespace App\Console\Commands;

use App\Models\Loans;
use App\Classes\PWClient;
use App\Classes\PWFunctions;
use Illuminate\Console\Command;

class CheckLoansDueToday extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'loan:checkLoansDueToday';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a message to people who have loans due today';

    protected $client;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->client = new PWClient();
        $this->client->login();

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $settings = \App\Models\Settings::getSettings();
        if ($settings["warMode"] === 1)
        {
            echo "War Mode";

            return "War mode enabled";
        }
        else
        {
            $loans = Loans::getLoansDueToday();

            foreach ($loans as $loan)
            {
                $message = "Hi $loan->leader, \n \n This is just a reminder to let you know that your loan is due today. \n \n Amount Due - $".number_format($loan->amount)." \n Loan Code - $loan->code \n To pay back your loan, deposit the amount into the bank with the transaction note set as the code.".PWFunctions::endMessage();
                $this->client->sendMessage($loan->leader, "Loan Due Today", $message);
            }
        }

    }
}
