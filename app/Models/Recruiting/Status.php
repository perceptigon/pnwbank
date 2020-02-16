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
        $endMessage = "\n\nIf you would like to join BK, then I think you made the right choice. Getting the process of joining started is easy. Just follow these steps:
-- Join our alliance in-game here: https://politicsandwar.com/alliance/id=4937
-- [link=http://bkpw.net/register/?core_pfield_11={$nation->nID}]Register an account on our forums here[/link] (We've filled out the nation ID field for you) 
-- Submit an application. [link=http://bkpw.net/topic/356-how-to-apply-to-the-black-knights/]Instructions here[/link] 

However, if you feel that BK isn't for you, I wish you the best of luck and I hope you are happy with the alliance you chose. 

As always, if you have any questions about BK or the game, in general, let me know and I will get back to you as soon as possible. 

Thanks!";

        $customMessage = self::where("name", "recruitMessage")->first();
        $customMessage = $customMessage->value;

        $message = $topMessage . $customMessage . $endMessage; // Build the message so beautifully

        return $message;
    }
}
