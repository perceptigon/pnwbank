<?php

namespace App\Console\Commands;

use App\Models\Employment;
use App\Models\ForumUser;
use App\Classes\Forums;
use App\Classes\PWClient;
use Illuminate\Console\Command;

class CheckEmployment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:employment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks to see if people are employed, and moves them to the correct tax bracket.';

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
        $members = ForumUser::whereIn('member_group_id', [116, 61, 43, 96, 171])->get();
        $forums = new Forums;

        $this->removeUnemployed();

        foreach ($members as $member)
        {
            $employee = Employment::where('forum_id', $member->member_id)->get();

            // skip people if they are already in the table
            if ($employee->isNotEmpty()) continue;

            // add new people
            $employee = new Employment;
            $employee->forum_id = $member->member_id;
            $employee->forum_name = $member->name;

            // get nation id
            $nid = $forums->getMember($member->member_id);
            $nid = (array)json_decode($nid, true);
            $nid = $nid['customFields'][3]["fields"][11]["value"];

            $employee->nation_id = $nid;
            $employee->save();

            // change tax bracket
            $pw = new PWClient();
            $pw->login();
            $pw->modifyMemberTaxBracket($employee->nation_id, 1310);
        }

        return true;
    }

    // remove all the plebs that aren't employed anymore
    public function removeUnemployed()
    {
        $employees = Employment::all();

        foreach ($employees as $employee)
        {
            $member = ForumUser::where('member_id', $employee->forum_id)->first();
            if(!in_array($member->member_group_id, [116, 61, 43, 96, 171]))
            {
                $employee->delete();

                // move back to normal tax bracket
                $pw = new PWClient();
                $pw->login();
                $pw->modifyMemberTaxBracket($employee->nation_id, 203);
            }
        }
    }
}
