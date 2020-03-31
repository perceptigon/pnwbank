<?php

namespace App\Console\Commands;

use App\Models\Deposits;
use App\Models\Loans;
use App\Classes\PWClient;
use Illuminate\Console\Command;

class CheckLoanPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'loan:checkLoanPayments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        echo "Running \n";
        $this->checkLoanPayments();
    }

    /**
     * Checks for loan payments. Messy code.
     *
     * @todo make this nicer
     */
    private function checkLoanPayments()
    {
        $loans = Loans::getActiveLoans();
        // We're gonna do deposit requests in here until I'm not lazy.... which will be a while lul
        $deposits = Deposits::getActiveDeposits();
        $content = new \simple_html_dom($this->client->getPage("https://politicsandwar.com/alliance/id=7399&display=bank"));
        for ($xpath = 2; $xpath < 52; $xpath++) {
            // Get Note
            foreach ($content->find('//*[@id="scrollme"]/table/tbody/tr['.$xpath.']/td[2]/img') as $x) {
                $x = $x->title;
                $note = preg_replace("/[^0-9]/", "", $x);
                // Get timestamp
                foreach ($content->find('//*[@id="scrollme"]/table/tbody/tr['.$xpath.']/td[2]/text()') as $y) {
                    $then = $y->plaintext;
                }
                $timestamp = strtotime($then);
                // Get three hours in the future. Four to convert to GMT (no daylight saving) and one hour before it. (PW doesn't observe DLS)
                // Change to +10,800 during daylight saving
                // Change to +14,400 during standard time
                // We only want to check the results that are from the last hour
                // This shit determines if it's daylight saving or not
                $dt = new \DateTime();
                if ($dt->format("I") == 1)
                    $add = 10800;
                else
                    $add = 14400;
                $now = time() + $add;
                echo "Now - $now \n";
                echo "Then - $timestamp \n";
                if ($now < $timestamp) {
                    echo "Passed timestamp \n";
                    foreach ($loans as $loan) {
                        echo "Code - $loan->code \n>";
                        echo "Note - $note \n";
                        if ($note == $loan->code) {
                            foreach ($content->find('//*[@id="scrollme"]/table/tbody/tr['.$xpath.']/td[6]') as $m) {
                                $noDot = substr($m, 0, strpos($m, "."));
                                $value = preg_replace("/[^0-9]/", "", $noDot);
                                if ($value == $loan->amount)
                                    $loan->loanComplete($this->client);
                                elseif ($value < $loan->amount)
                                    $loan->makePayment($value, $this->client);
                                else
                                    $loan->paymentError($this->client);
                            }
                        }
                    }
                    // Now process deposits
                    foreach ($deposits as $deposit) {
                        echo "Deposit Code - $deposit->code\n";
                        echo "Note - $note\n";
                        if ($note == $deposit->code) {
                            // Setup variables
                            $money = 0;
                            $food = 0;
                            $coal = 0;
                            $oil = 0;
                            $uranium = 0;
                            $lead = 0;
                            $iron = 0;
                            $bauxite = 0;
                            $gas = 0;
                            $munitions = 0;
                            $steel = 0;
                            $aluminum = 0;

                            // Now go through each column and get their values... this is gonna be so messy lmao
                            foreach ($content->find('//*[@id="scrollme"]/table/tbody/tr['.$xpath.']/td[6]') as $m)
                                $money = $this->getValue($m);
                            foreach ($content->find('//*[@id="scrollme"]/table/tbody/tr['.$xpath.']/td[7]') as $m)
                                $food = $this->getValue($m);
                            foreach ($content->find('//*[@id="scrollme"]/table/tbody/tr['.$xpath.']/td[8]') as $m)
                                $coal = $this->getValue($m);
                            foreach ($content->find('//*[@id="scrollme"]/table/tbody/tr['.$xpath.']/td[9]') as $m)
                                $oil = $this->getValue($m);
                            foreach ($content->find('//*[@id="scrollme"]/table/tbody/tr['.$xpath.']/td[10]') as $m)
                                $uranium = $this->getValue($m);
                            foreach ($content->find('//*[@id="scrollme"]/table/tbody/tr['.$xpath.']/td[11]') as $m)
                                $lead = $this->getValue($m);
                            foreach ($content->find('//*[@id="scrollme"]/table/tbody/tr['.$xpath.']/td[12]') as $m)
                                $iron = $this->getValue($m);
                            foreach ($content->find('//*[@id="scrollme"]/table/tbody/tr['.$xpath.']/td[13]') as $m)
                                $bauxite = $this->getValue($m);
                            foreach ($content->find('//*[@id="scrollme"]/table/tbody/tr['.$xpath.']/td[14]') as $m)
                                $gas = $this->getValue($m);
                            foreach ($content->find('//*[@id="scrollme"]/table/tbody/tr['.$xpath.']/td[15]') as $m)
                                $munitions = $this->getValue($m);
                            foreach ($content->find('//*[@id="scrollme"]/table/tbody/tr['.$xpath.']/td[16]') as $m)
                                $steel = $this->getValue($m);
                            foreach ($content->find('//*[@id="scrollme"]/table/tbody/tr['.$xpath.']/td[17]') as $m)
                                $aluminum = $this->getValue($m);

                            $deposit->account->deposit([
                                "money" => $money,
                                "food" => $food,
                                "coal" => $coal,
                                "oil" => $oil,
                                "uranium" => $uranium,
                                "lead" => $lead,
                                "iron" => $iron,
                                "bauxite" => $bauxite,
                                "gas" => $gas,
                                "munitions" => $munitions,
                                "steel" => $steel,
                                "aluminum" => $aluminum
                            ], $this->client);

                            // Now update the deposit
                            $deposit->pending = false;
                            $deposit->completed = true;
                            $deposit->save();
                        }
                    }
                }
            }
        }
        // Get bank page
    }

    protected function getValue($stuff)
    {
        /*$noDot = substr($stuff, 0, strpos($stuff, "."));
        $value = preg_replace("/[^0-9]/", "", $noDot);*/
        $remove = preg_replace('/[^0-9\.]/', "", $stuff->plaintext);

        return \floatval($remove);
    }
}
