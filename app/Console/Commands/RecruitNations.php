<?php

namespace App\Console\Commands;

use App\Classes\Nation;
use App\Classes\PWClient;
use App\Models\Recruiting\Nations;
use App\Models\Recruiting\Status;
use Illuminate\Console\Command;
use Wa72\HtmlPageDom\HtmlPage;

class RecruitNations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recruitNations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recruits Nations';

    /**
     * Client to login and send messages
     *
     * @var PWClient
     */
    protected $client;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->client = new PWClient();
        $this->client->login();

        $nIDs = $this->getNewlyCreatedNations();

        foreach ($nIDs as $nID)
        {
            if ($this->checkIfRecruited($nID))
                continue; // If the nation has already been recruited, go to the next iteration

            // Now let's get the Nation's info and see if they're in an alliance
            $nation = new Nation($nID);
            if ($nation->alliance != "None")
                continue; // Nation is in an alliance

            // YAY we can recruit this nation
            $this->recruit($nation);
        }
    }

    /**
     * We're gonna scrape the nation's page to get newly created nations to achieve a couple of things:
     * 1) It's A LOT faster and less resource intensive than using the nations API
     * 2) There's actually less confusing code lol
     *
     * @return array
     */
    protected function getNewlyCreatedNations() : array
    {
        $html = new HtmlPage(file_get_contents("https://politicsandwar.com/nations/"));
        $html = $html->getBody();
        $nIDs = [];
        for ($row = 2; $row < 12; $row++)
        {
            $nationLink = $html->filter(".nationtable tr:nth-child($row) td:nth-child(2) a")->attr('href');
            array_push($nIDs, preg_replace('/[^0-9]/', '', $nationLink));
        }

        return $nIDs;
    }

    /**
     * Calls the Nation's table. If there is a result in there, then they've already been recruited
     *
     * @param int $nID
     * @return bool
     */
    protected function checkIfRecruited(int $nID) : bool
    {
        $nation = Nations::where("nationID", $nID)->get();
        if ($nation->count() > 0)
            return true; // Nation already recruited

        return false; // Nation has never been recruited
    }

    /**
     * Does all the stuff we do when we recruit a nation
     *
     * @param Nation $nation
     */
    protected function recruit(Nation $nation)
    {
        $this->client->sendMessage($nation->leader, "Hi {$nation->leader}, check out Camelot!", Status::buildRecruitmentMessage($nation));

        $this->updateNationsTable($nation);
    }

    /**
     * Updates our Nations table so they don't another message
     *
     * @param Nation $nation
     */
    protected function updateNationsTable(Nation $nation)
    {
        Nations::insert([
            "inputDate" => time(),
            "nationID" => $nation->nID
        ]);
    }
}
