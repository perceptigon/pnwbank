<?php

namespace App\Classes;

use App\Exceptions\UserErrorException;
use App\Models\Deposits;
use App\Models\Loans;
use App\Models\MarketDeals;

class Codes
{
    /**
     * Generates a unique code to allow users to deposit money.
     *
     * @return int
     * @throws \Exception
     */
    public static function generateCode() : int
    {
        $loop = 0;
        $code = "";
        while (true)
        {
            if ($loop === 20) // If the loop ran 20 times, just kill it. This way if it can't generate a code it won't inf loop
                throw new UserErrorException("Couldn't generate unique code, try again");
            $code = rand(1000, 2000000);
            // Check if the code is unique in each of the needed databases
            $uniqueCheck = Loans::where("code", $code)->count();
            $uniqueCheck2 = MarketDeals::where("code", $code)->count();
            $uniqueCheck3 = Deposits::where("code", $code)->count();
            if ($uniqueCheck === 0 && $uniqueCheck2 === 0 && $uniqueCheck3 === 0) // TODO a better way of this shit lol
                break; // Code is unique, break out of the loop

            $loop++;
        }

        return $code;
    }
}
