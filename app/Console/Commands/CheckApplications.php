<?php

namespace App\Console\Commands;

use App\Models\Noob;
use App\Models\Carebear;
use App\Classes\PWClient;
use App\Classes\Tibernet;
use Illuminate\Console\Command;

class CheckApplications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tibernet:CheckApplications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Checks the statuses of applicants's application";

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
        $applicants = Noob::where('member', false)->get();

        foreach ($applicants as $applicant)
        {
            echo $applicant->forum_id;
            if (Tibernet::is_accepted($applicant->forum_id) > 0)
            {

                $pw = new PWClient();
                $pw->login();
                $pw->acceptMember($applicant->nation_ruler);
                $applicant->member = true;
                $applicant->created_at = date("Y-m-d H:i:s");
                $applicant->updated_at = date("Y-m-d H:i:s");
                $applicant->save();

            }

            elseif (Tibernet::is_rejected($applicant->forum_id) > 0)
            {
                $pw = new PWClient();
                $pw->login();
                $pw->removeMember($applicant->nation_ruler);
                $applicant->delete();
            }
        }
    }
}
