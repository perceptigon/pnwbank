<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Noob;
use App\Classes\Nation;

class CheckCities extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tibernet:CheckCities';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates number of cities, and when last city was built.';

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
        $noobs = Noob::where('member', true)->get();

        foreach ($noobs as $noob)
        {
            $nation = new Nation($noob->nation_id);

            if ($nation->cities >= 8)
            {
                $noob->delete();
            }

            else if (is_null($noob->cities) || $nation->cities != $noob->cities)
            {
                $noob->cities = $nation->cities;
                $noob->last_city_built = date("Y-m-d H:i:s");
                $noob->save();
            }
        }
    }
}
