<?php

namespace App\Jobs;

use App\Classes\Nation;
use App\Classes\PWClient;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendVerifyMessage implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $user;

    /**
     * Create a new job instance.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
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

        $nation = new Nation($this->user->nID);

        $message = "Hi $nation->leader, \n\n An account at the BK Bank has been created and this message is being sent to you to verify that it was actually you who created the account. In order to verify your account, click the link below. If you did not create this account, [b]DO NOT[/b] click the link and please contact a Government member right away.\n\n [link=".url("/verify/".$this->user->verifyToken)."]Click here to verify your account![/link]\nIf your token was not autofilled, your token is: ".$this->user->verifyToken;

        $client->sendMessage($nation->leader, "BK Bank Verification", $message);
    }
}
