<?php

namespace App\Http\Controllers;

use App\Classes\ForumProfile;
use App\Models\Deposits;
use Illuminate\Http\Response;

class APIController extends Controller
{
    /**
     * Get the city grant info and display it as JSON.
     *
     * @param int $gID
     * @return $this
     */
    public function gInfo(int $gID)
    {
        $gInfo = \App\Models\Grants\CityGrant::getGrantInfoByID($gID);

        $gInfo->notes = str_replace("<", "&lt;", $gInfo->notes);

        return (new Response($gInfo->toJson(128), 200))
            ->header("content-type", "application/json");
    }

    /**
     * Find the member ID of the forum from a nation ID.
     *
     * @param int $nID
     * @return $this
     */
    public function memberID(int $nID)
    {
        try
        {
            $profile = new ForumProfile($nID);
            $profile->getForumProfile();

            return (new Response(\json_encode(["memberID" => $profile->profile["id"]], 128), 200))
                ->header("content-type", "application/json");
        }
        catch (\Exception $e)
        {
            return (new Response(\json_encode(["error" => $e->getMessage()], 128), 404))
                ->header("content-type", "application/json");
        }
    }

    /**
     * Return the trading API view.
     *
     * @param int $nID
     * @param string $resource
     * @return mixed
     */
    public function trading(int $nID, string $resource)
    {
        return view("api.trading", [
            "nID" => $nID,
            "resource" => $resource,
        ]);
    }

    public function deposit(int $acct)
    {
        $deposit = Deposits::where('accountID', $acct)
            ->orderBy('created_at', 'desc')
            ->first();

        return view("api.deposit_code", [
            "code" => $deposit->code,
        ]);

    }

    /**
     * Grab the trade info from the PW API and determine if $nID != the lowestBuy.
     *
     * @param int $nID
     * @param string $resource
     */
    public function tradeTracker(int $nID, string $resource)
    {
        $json = json_decode(file_get_contents("https://politicsandwar.com/api/tradeprice/resource={$resource}&key=".env("PW_API_KEY")));

        if ($json->lowestbuy->nationid != $nID)
        {
            echo json_encode(["bool" => true, "nID" => $json->lowestbuy->nationid, "price" => $json->lowestbuy->price]);
        }
        else
        {
            echo json_encode(["bool" => false]);
        }
    }
}
