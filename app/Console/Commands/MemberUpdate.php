<?php

namespace App\Console\Commands;

use App\Classes\Nation;
use App\Models\Members;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MemberUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'members:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the Members and Member History tables';

    private $nation;

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
        // TODO this shit
        //$nIDs = PWFunctions::getAllianceNationIDs(7399);
        $nIDs = [10472];

        // Get all the nation IDs in the table so we can delete the ones that are no longer in BK
        $allNIDs = Members::getAllNIDs();

        for ($x = 0; $x < count($nIDs); $x++)
        {
            $nation = new Nation($nIDs[$x]);

            if ($nation->alliancePosition > 1)
            {
                $this->updateMembers();
                $this->updateMemberHistory();

                // Remove from nIDs to get a list of nations who are no longer in BK
                unset($nIDs[$x]); // TODO finish this
            }
        }
    }

    private function updateMembers()
    {
        // Check if there's already a record for this member
        // If there is no record, it will throw an exception
        try
        {
            $member = Members::getMember($this->nation->nID);
            $member->update($this->nation);
        }
        catch (ModelNotFoundException $e) // Model not found, create a new one
        {
            $member = new Members();
            $member->update($this->nation);
        }
    }
}
