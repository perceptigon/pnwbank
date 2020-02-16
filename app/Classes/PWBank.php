<?php

namespace App\Classes;

use GuzzleHttp\Client;
use Wa72\HtmlPageDom\HtmlPage;

class PWBank
{
    /**
     * @var int
     */
    public $money = 0;

    /**
     * @var int
     */
    public $food = 0;

    /**
     * @var int
     */
    public $coal = 0;

    /**
     * @var int
     */
    public $oil = 0;

    /**
     * @var int
     */
    public $uranium = 0;

    /**
     * @var int
     */
    public $lead = 0;

    /**
     * @var int
     */
    public $iron = 0;

    /**
     * @var int
     */
    public $bauxite = 0;

    /**
     * @var int
     */
    public $gasoline = 0;

    /**
     * @var int
     */
    public $munitions = 0;

    /**
     * @var int
     */
    public $steel = 0;

    /**
     * @var int
     */
    public $aluminum = 0;

    /**
     * The person/alliance the bank request should be sent to. Has to be exact.
     *
     * @var string
     */
    public $recipient;

    /**
     * If the request is to be sent to a nation or alliance.
     *
     * Defaults to Nation. Set to Alliance if it's being sent to an alliance
     *
     * @var string
     */
    public $type = "Nation";

    /**
     * The note added alongside the bank request.
     *
     * @var string
     */
    public $note = "";

    private $token;

    /**
     * An array that contains the data that will be sent in the post request.
     *
     * @var array
     */
    private $postData = [];

    /**
     * Holds the PWClient.
     *
     * @var PWClient
     */
    private $client;

    /**
     * Boolean that shows if the transaction was successful
     *
     * @var bool
     */
    public $verified = false;

    /**
     * The function that gathers everything and sends out the money.
     *
     * @return bool
     * @throws \Exception
     */
    public function send(PWClient $client) : bool
    {
        // Get settings
        $this->client = $client;
        $settings = \App\Models\Settings::getSettings();
        if ($settings["devMode"] === 0) // Check to see if dev mode is off
        {
            // Check to see if the recipient is filled out
            if (empty($this->recipient))
                throw new \Exception("Couldn't send -> Recipient empty");
            $this->getToken();
            $this->setupPost();

            $this->sendPOST();
        }
        else // If dev mode is on do what we need to do
        {
            $this->verified = true; // Set to true because if it's dev mode we assume it always sent
        }

        return true; // We can't yet check if the money was actually sent. We just assume it is. It's possible, but I'm lazy
    }

    /**
     * Gets the CSRF token from PW to send.
     *
     * Gets the token from the city page because it loads slightly faster
     *
     * @throws \Exception
     */
    private function getToken()
    {
        $url = "https://politicsandwar.com/city/id=100614";
        $content = new \simple_html_dom($this->client->getPage($url));
        $token = "";

        // Find the token
        foreach ($content->find("//*[@id=\"cityimg\"]/center/form[1]/input[2]") as $x)
            $token = $x->value;

        if (empty($token))
            throw new \Exception("Couldn't get token. This could be due to Politics and Snore or a Captcha");
        $this->token = $token;
    }

    /**
     * Hacky way to get token statically
     *
     * @param PWClient $client
     * @return mixed
     */
    public static function getTokenStatic(PWClient $client) : string
    {
        $self = new self;

        $self->client = $client;
        $self->getToken();

        return $self->token;
    }

    /**
     * Takes all the data needed and puts it into the post array so we can send it along.
     *
     * @throws \Exception
     */
    private function setupPost()
    {
        // Check if the token is setup
        if (empty($this->token))
            throw new \Exception("Token not set. Run getToken() first");
        $this->postData = [
            "withmoney" => $this->money,
            "withfood" => $this->food,
            "withcoal" => $this->coal,
            "withoil" => $this->oil,
            "withuranium" => $this->uranium,
            "withlead" => $this->lead,
            "withiron" => $this->iron,
            "withbauxite" => $this->bauxite,
            "withgasoline" => $this->gasoline,
            "withmunitions" => $this->munitions,
            "withsteel" => $this->steel,
            "withaluminum" => $this->aluminum,
            "withtype" => $this->type,
            "withrecipient" => $this->recipient,
            "withnote" => $this->note,
            "withsubmit" => "Withdraw",
            "token" => $this->token,
        ];

    }

    /**
     * Sends the post data completing the request.
     *
     * @throws \Exception
     */
    private function sendPOST()
    {
        // Check if the postData is empty
        if (empty($this->postData))
            throw new \Exception("Post data empty. Run setupPOST() first");
        $x = $this->client->postData($this->postData, "https://politicsandwar.com/alliance/id=4937&display=bank", true);

        $this->verifySent($x);
    }

    /**
     * Method to check if the transaction was successful
     *
     * Scrapes the response after the POST is sent. It looks for an .alert-danger
     * dialog box. If it sees one, it'll assume that the transaction was not successful.
     * This isn't the best or most secure way to do things but other methods
     * require quite a large architectural design change.
     *
     * In the future, setup a system that stores every transaction in the database with a
     * serialized PWBank object and a verification code that is included in the transaction note
     * and instead of looking for .alert-danger, look for the verification code.
     *
     * Currently,  nothing really uses this verification until I implement it in other places
     * but it's here, so that's cool.
     *
     * @param string $rawHTML
     * @return bool
     */
    protected function verifySent(string $rawHTML) : bool
    {
        // Create a HTMLPage so we can look for the alert box
        $html = new HtmlPage($rawHTML);

        // Look for the alert dialog on the page
        $alert = $html->filter(".alert-danger");

        // If no text is returned, then we can assume that the transaction was successful
        // You can look for .alert-success to confirm it, but fuck that
        if (empty($alert->text()))
        {
            $this->verified = true;
            return true;
        }
        else
        {
            $this->verified = false; // Set to false by default, but just to make sure set it here too
            return false;
        }
    }

    /**
     * Verify that we have the stuff in the bank to send this request
     *
     * @return bool
     * @throws \Exception
     */
    public function checkIfFundsAvailable() : bool
    {
        $client = new Client();
        $get = $client->get("http://politicsandwar.com/api/alliance-bank/?allianceid=4937&key=".env("PW_API_KEY"))->getBody();
        $json = \json_decode($get);

        if (! $json->success)
            throw new \Exception($json->general_message);

        $bank = $json->alliance_bank_contents;

        // So we can easily loop over each resource
        $resources = ["money", "food", "coal", "oil", "uranium", "iron", "bauxite", "lead", "gasoline", "munitions", "steel", "aluminum"];

        foreach ($resources as $resource)
        {
            // If the requested resource is greater than the amount we actually have, return a false.
            if ($this->$resource > $bank[0]->$resource)
                return false;
        }

        return true;
    }
}
