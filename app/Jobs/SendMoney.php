<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Classes\PWBank;
use App\Classes\PWClient;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMoney extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * Recipient of the message.
     *
     * @var string
     */
    protected $recipient;

    /**
     * Subject of the message.
     *
     * @var string
     */
    protected $subject;

    /**
     * The message to be sent to the nation.
     *
     * @var string
     */
    protected $message;

    /**
     * Store the PWBank object.
     *
     * @var PWBank
     */
    protected $bank;

    /**
     * Holds our PWClient.
     *
     * @var PWClient
     */
    protected $client;

    /**
     * Create a new job instance.
     *
     * @param PWBank $bank
     * @param string|null $recipient
     * @param string|null $subject
     * @param string|null $message
     */
    public function __construct(PWBank $bank, string $recipient = null, string $subject = null, string $message = null)
    {
        $this->bank = $bank;
        $this->recipient = $recipient;
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
        try
        {
            if ($this->checkForPolAndSnore()) // If Politics and Snore, sleep for 3 minutes
                sleep(600);

            // Setup a client for this job
            $this->client = new PWClient();
            $this->client->login();

            // Check if this has already been attempted. If so, just send me a message and gtfo
            if ($this->attempts() > 1)
            {
                $this->error("SendMoney job hit it's second attempt. Trying to send {$this->bank->money} to {$this->bank->recipient}");

                return;
            }

            // If everything is good, go ahead and send the money
            $this->send();
        }
        catch (\Exception $e)
        {
            $this->failed($e->getMessage());
        }

    }

    /**
     * This calls the proper method when we're ready to go though with sending the money.
     */
    protected function send()
    {
        $this->sendBank();
        $this->sendMessage();
    }

    /**
     * Sends the money and/or resources.
     */
    protected function sendBank()
    {
        $this->bank->send($this->client);
    }

    /**
     * Sends the message associated with the transfer.
     */
    protected function sendMessage()
    {
        // If the recipient is null, then no message needs to be sent
        if ($this->recipient == null)
            return;

        $this->client->sendMessage($this->recipient, $this->subject, $this->message);
    }

    /**
     * Checks for Politics and Snore. If the minute :00 return true.
     *
     * @return bool
     */
    protected function checkForPolAndSnore() : bool
    {
        $now = Carbon::now();

        if ($now->minute > 55 || $now->minute < 5)
            return true;

        return false;
    }

    /**
     * Call this method when there's an error.
     *
     * @param string $error
     */
    protected function error(string $error)
    {
        $message = "There was an error while processing on of the SendMoney jobs. Message: \n \n$error";

        $this->client->sendMessage("Azazel", "SendMoney Job Error", $message);
    }

    /**
     * If the job fails for whatever reason, this method is called.
     *
     * @param string $message
     */
    public function failed(string $message = "Failed method called.")
    {
        // Reset the PWClient
        $this->client = new PWClient();
        $this->client->login();

        $this->error($message);
    }
}
