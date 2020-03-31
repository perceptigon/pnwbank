<?php

namespace App\Models\Recruiting;

use App\Classes\Nation;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $connection = 'recruiting';
    public $table = "status";
    public $timestamps = false;

    /**
     * Build our recruitment message
     *
     * @param Nation $nation
     * @return string
     */
    public static function buildRecruitmentMessage(Nation $nation) : string
    {
        $topMessage = "Hey {$nation->leader},\n\n";
        $endMessage = "\n\nI

As always, if you have any questions about The Rothschild Family or the game, in general, let me know and I will get back to you as soon as possible.

Thanks!";

        $customMessage = self::where("name", "recruitMessage")->first();
        $customMessage = $customMessage->value;

        $message = $topMessage . $customMessage . $endMessage; // Build the message so beautifully

        return $message;
    }
}
