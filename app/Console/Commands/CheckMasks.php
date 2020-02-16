<?php

namespace App\Console\Commands;

use App\Models\Noob;
use App\Classes\Tibernet;
use Illuminate\Console\Command;
use App\Classes\PWClient;

class CheckMasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tibernet:CheckMasks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Updates all the noobs' masks";

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
        $noobs = Noob::all();

        $pw = new PWClient();
        $pw->login(); // Login to PW

        foreach ($noobs as $noob)
        {
            $previous_mask = $noob->forum_mask;
            $mask = Tibernet::getMask($noob->forum_id);
            $noob->forum_mask = $mask;
            $noob->save();

            //removes noobs if they go from a non-3 mask to 3, cuz that means they got unmasked lel
            if ($previous_mask != 3 && $mask == 3 && $noob->member == true)
            {
                $pw->removeMember($noob->nation_id);
                $noob->delete();
            }
        }
    }
}
