<?php

namespace App\Jobs;

use App\Classes\Nation;
use App\Classes\Verify;
use App\Classes\PWClient;
use App\Classes\PWFunctions;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CityGrantReminder extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * Stores the message that should be sent to the member.
     *
     * @var string
     */
    protected $message;

    /**
     * Stores the city numbers that should get messages.
     *
     * @var array
     */
    protected $cityNums;

    /**
     * Create a new job instance.
     *
     * @param string $message The message that should be sent to the member
     * @param array $cityNums Which city number we should send the reminders to
     * @return void
     */
    public function __construct(string $message, array $cityNums)
    {
        $this->message = $message;
        $this->cityNums = $cityNums;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try
        {
            $nIDs = PWFunctions::getAllianceNationIDs(7399);

            $client = new PWClient();
            $client->login();

            foreach ($nIDs as $nID)
            {
                $nation = new Nation($nID);
                $verify = new Verify($nation);

                if (! in_array($nation->cities, $this->cityNums)) // If their city num is not in the cities that we're checking
                    continue;

                if ($verify->requestCityGrant())
                {
                    $message = "Hi $nation->leader,\n\n".$this->message;

                    $client->sendMessage($nation->leader, "You are eligible for a city grant", $message);
                }
            }
        }
        catch (\Exception $e)
        {
            $client->sendMessage("Blackbird", "City Grant Reminder Error", $e);
        }
    }
}
