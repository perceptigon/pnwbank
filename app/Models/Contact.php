<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class Contact extends Model
{
    public $timestamps = false;
    protected $table = "contact";

    /**
     * Get the pending contact requests.
     *
     * @return Collection
     */
    public static function getPendReqs() : Collection
    {
        return self::where("status", "pending")->get();
    }

    /**
     * Get the contact request by it's ID.
     *
     * @param int $cID
     * @return Contact
     */
    public static function getReqByID(int $cID) : Contact
    {
        return self::where("id", $cID)->firstOrFail();
    }

    /**
     * Set the contact request to complete.
     *
     * @param int $cID
     */
    public static function setComplete(int $cID)
    {
        $req = self::getReqByID($cID);
        $req->status = "complete";
        $req->save();
    }

    /**
     * Create a new contact request.
     *
     * @param Request $request
     */
    public static function createReq(Request $request)
    {
        if ($req->nID == 0) {
            return;
          }
        $req = new self;
        $req->discord = $request->discord;
        $req->nID = $request->nID;
        $req->leader = $request->leader;
        $req->message = $request->message;
        $req->save();
    }

    /**
     * Count the pending contact requests.
     *
     * @return null|int
     */
    public static function countPendReqs()
    {
        $num = self::where("status", "pending")->count();

        return $num > 0 ? $num : null;
    }
}
