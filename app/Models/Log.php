<?php

// I'm sure Laravel has a good logging system, but I'm doing it this way cuz that's the way it was before this

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class Log extends Model
{
    public $timestamps = false;

    /**
     * Create a log.
     *
     * @param string $category
     * @param string $message
     * @param array $reasons
     */
    public static function createLog(string $category, string $message, array $reasons = [])
    {
        $user = "";
        if (Auth::guest())
            $user = "Guest (".request()->ip().")";
        else
            $user = Auth::user()->username;

        $log = new self;
        $log->category = $category;
        $log->username = $user;
        $log->message = $message;
        $log->reasons = (empty($reasons)) ? null : \json_encode($reasons);
        $log->save();
    }

    /**
     * Get logs that have to do with a specific loan by it's loan id.
     *
     * @param int $loanID
     * @return Collection
     */
    public static function getLoanLogs(int $loanID) : Collection
    {
        $logs = self::where([
            ["category", "loan"],
            ["message", "LIKE", "%$loanID%"],
        ])
        ->orderBy("timestamp", "desc")
        ->get();

        return $logs;
    }

    /**
     * Get "slim" logs AKA get the 25 logs.
     *
     * @param string $logType
     * @return Collection
     */
    public static function getSlimLogs(string $logType) : Collection
    {
        return self::where("category", $logType)->orderby("timestamp", "desc")->take(25)->get();
    }
}
