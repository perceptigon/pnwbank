<?php

namespace App\Jobs;

use App\Classes\PWClient;
use App\Defense\DefenseNations;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SignInReminderJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $badBoys = DefenseNations::getNationsHaventSignedIn();
        $client = new PWClient();
        $client->login();

        foreach ($badBoys as $nation)
        {
            $message = "Hi $nation->leader, \n \nThis message is being sent to you because you have not yet signed in for this period. Please go [link=https://www.rnco.uk/signin]here[/link] to sign in. We need this information in order to plan properly and you must sign in to be eligible for all economic programs.";

            $client->sendMessage($nation->leader, "Sign In Reminder", $message);
        }
    }
}
