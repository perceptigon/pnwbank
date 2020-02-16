<?php

namespace App\Jobs;

use App\Classes\Nation;
use App\Classes\PWClient;
use App\Classes\PWFunctions;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class MassMessageJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * The subject of the message.
     *
     * @var string
     */
    protected $subject;

    /**
     * The text of the message.
     *
     * @var string
     */
    protected $message;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $subject, string $message)
    {
        $this->subject = $subject;
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $nIDs = PWFunctions::getAllianceNationIDs(4937);
        $client = new PWClient();
        $client->login();

        foreach ($nIDs as $nID)
        {
            $nation = new Nation($nID);

            if ($nation->alliancePosition == 1)
                continue; // If they're an applicant, skip over them

            $client->sendMessage($nation->leader, $this->subject, $this->message);
        }
    }
}
