<?php

namespace App\Console\Commands;

use App\Classes\PWClient;
use Illuminate\Console\Command;
use App\Classes\PWFunctions;
use App\Models\Member;
use App\Models\Unmask;
use App\Classes\Tibernet;
use App\Classes\Forums;

class UpdateMembers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tibernet:UpdateMembers';

    protected $client;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for removed members that are still masked on the forums.';

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
        $functions = new PWFunctions;
        $ids = $functions->getAllianceNationIDs();

        $removed = Member::whereNotIn('nID', $ids)->get();

        //handle people who have been removed
        foreach ($removed as $remove)
        {
            $forum_id = Tibernet::getForumID($remove->nID);
            if ($forum_id != 0) $mask = Tibernet::getMask($forum_id);
            else $mask = 0;

            if ($mask != 3 && $forum_id != 0)
            {
                $forums = new Forums;

                $forum_member = $forums->getMember($forum_id);
                $forum_member = json_decode($forum_member);
                $forum_name = $forum_member->name;

                $message = "The following member needs to be unmasked:
                
                Nation: https://politicsandwar.com/nation/id=" . $remove->nID . "
                Forum: [link=https://bkpw.net/profile/" . $forum_id . "-" . $forum_name . "]" . $forum_name . "[/link]";

                $this->client->sendMessage('Who Me', "Pleb to Unmask", $message);
            }
        }

        //delete member database
        Member::getQuery()->delete();

        //add current members
        foreach ($ids as $id)
        {
            $member = new Member();
            $member->nID = $id;
            $member->save();
        }

    }
}
