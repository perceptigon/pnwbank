<?php

namespace App\Classes;

use GuzzleHttp\Client;
use Wa72\HtmlPageDom\HtmlPageCrawler;

class PWClient
{
    /**
     * The GuzzleHttp client used for everything.
     *
     * @var Client
     */
    protected $client;

    /**
     * True if they've logged in, false if not. Used to make sure they're logged in
     * before doing things that requires being logged in.
     *
     * @var bool
     */
    protected $loggedIn;

    /**
     * Store the settings here so that we don't have to query it multiple times.
     *
     * @var array
     */
    protected $settings;

    /**
     * PWClient constructor.
     *
     * Creates a new GuzzleHttp client and stores it
     */
    public function __construct()
    {
        $this->settings = \App\Models\Settings::getSettings();

        $this->client = new Client([
            "verify" => false,
            "cookies" => true,
        ]);
    }

    /**
     * Logs into PW.
     *
     * This is required to be ran before running functions needing to be logged in for
     */
    public function login()
    {
        $postData = [
            "form_params" => [
                "email" => env("PW_EMAIL"),
                "password" => env("PW_PASSWORD"),
                "loginform" => "Login",
            ],
        ];

        if ($this->settings["devMode"] != 1) // Check if dev mode is off
            $this->client->request("POST", "https://politicsandwar.com/login/", $postData);

        $this->loggedIn = true;
    }

    /**
     * Grabs a page and returns the HTML of that page.
     *
     * @param string $url
     * @return string
     */
    public function getPage(string $url) : string
    {
        $response = $this->client->get($url);

        return $response->getBody();
    }

    /**
     * Sends an in-game message to someone.
     *
     * @param string $recipient
     * @param string $subject
     * @param string $message
     * @return bool
     * @throws \Exception
     */
    public function sendMessage(string $recipient, string $subject, string $message) : bool
    {
        if (! $this->loggedIn)
            throw new \Exception("You must call the login() function before running this function");
        if ($this->settings["devMode"] === 1) // Check if dev mode is on
            return true;

        // Add message footer to the end of the message
        $message .= "\n\n".self::endMessage();

        $postData = [
            "form_params" => [
                "newconversation" => "true", // Has to be a string
                "receiver" => $recipient,
                "carboncopy" => "",
                "subject" => $subject,
                "body" => $message,
                "sndmsg" => "Send Message",
            ],
        ];

        $this->client->request("POST", "https://politicsandwar.com/inbox/message/", $postData);

        return true;
    }

    /**
     * Sends a POST request to a URL.
     *
     * @param array $postData
     * @param string $url
     * @param bool $needsToBeLoggedIn Optionally set if you want to make sure to be logged in before sending
     * @throws \Exception
     * @return string
     */
    public function postData(array $postData, string $url, bool $needsToBeLoggedIn = false) : string
    {
        if ($needsToBeLoggedIn)
        {
            if (! $this->loggedIn)
                throw new \Exception("You must call the login() function before running this function");
        }

        if ($this->settings["devMode"] === 1) // Check if dev mode is on
            return "";

        // Setup postData for the request
        $post = [
            "form_params" => $postData,
        ];

        $response = $this->client->request("POST", $url, $post);

        return $response->getBody();
    }

    /**
     * Returns the string that should be included at the end of every message.
     *
     * @return string
     */
    public static function endMessage() : string
    {
        return "This message was sent automatically. DO NOT respond to this message. If you need to contact us about something, please go [link=https://banque-lumiere.pro/contact]here[/link].";
    }

    /**
     * Change a member's level in-game.
     *
     * 0 = Remove
     * 1 = Applicant
     * 2 = Member
     * 3 = Officer
     * 4 = Heir
     * 5 = Leader
     *
     * @param string $leader
     * @param int $level
     */
    protected function changeMemberLevel(string $leader, int $level)
    {
        $this->postData([
            "nationperm" => $leader,
            "level" => $level,
            "permsubmit" => "Go",
        ], "https://politicsandwar.com/alliance/id=7399", true);
    }

    /**
     * Accept a member in-game. Sets their level to 2.
     *
     * @param string $leader
     */
    public function acceptMember(string $leader)
    {
        $this->changeMemberLevel($leader, 2);
    }

    /**
     * Removes a member from the alliance in-game.
     *
     * @param string $leader
     */
    public function removeMember(string $leader)
    {
        $this->changeMemberLevel($leader, 0);
    }

    /**
     * Changes a member's tax bracket
     *
     * @param int $nID
     * @param int $taxBracketID
     * @throws \Exception
     */
    public function modifyMemberTaxBracket(int $nID, int $taxBracketID)
    {
        if (!$this->loggedIn)
            throw new \Exception("You must be logged in to run this function");

        $token = $this->getTaxToken();

        $this->postData([
            "nation_id" => $nID,
            "bracket_id" => $taxBracketID,
            "change_member_bracket" => "Update Nation's Bracket",
            "token" => $token,
        ], "https://politicsandwar.com/alliance/id=7399&display=taxes", true);
    }

    /**
     * Get the SPECIAL FUCKING TOKEN ON THE FUCKING STUPID FUCKING TAX FUCKING PAGE FOR FUCKS SAKE SHEEPY WHY DOES IT FUCKING HAVE TO FUCKING BE FUCKING DIFFERENT
     *
     * @return HtmlPageCrawler|string
     * @internal param PWClient $client
     */
    public function getTaxToken() : string
    {
        $html = new HtmlPageCrawler($this->getPage("https://politicsandwar.com/alliance/id=7399&display=taxes"));

        $token = $html->filter("input[name=token]")->getAttribute("value");

        return $token;
    }
}
