<?php

namespace App\Console\Commands;

use App\Models\Loans;
use App\Classes\PWClient;
use App\Classes\PWFunctions;
use Illuminate\Console\Command;

class CheckLateLoans extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'loan:CheckLateLoans';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for late loans and notify people';

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
        // Get settings to check for war mode
        $settings = \App\Models\Settings::getSettings();
        if ($settings["warMode"] === 1)
        {
            echo "War Mode";

            return "War mode enabled";
        }
        else
        {
            $loans = Loans::getLateLoans();
            foreach ($loans as $loan)
            {
                $message = "Hi $loan->leader, \n \n Your loan of $".number_format($loan->amount)." with the code $loan->code has not been paid. \n \n Please deposit $".number_format($loan->amount)." to the Banque Lumiére portal with the code in the transaction note otherwise you may face penalties.".PWFunctions::endMessage();
                $this->client->sendMessage($loan->leader, "Late Loan Notice", $message);
            }
        }
    }
}
