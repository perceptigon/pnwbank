<?php

namespace App\Models\Grants;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class CityGrant extends Model
{
    protected $table = 'citygrants';
    public $timestamps = false;

    /**
     * Return information about a specific city grant.
     *
     * @param int $grantNum
     * @return CityGrant
     */
    public static function getGrantInfo(int $grantNum) : CityGrant
    {
        $gInfo = self::where("grantNum", "=", $grantNum)->firstOrFail();

        return $gInfo;
    }

    /**
     * Return information about a city grant by it's ID.
     *
     * @param int $gID
     * @return CityGrant
     */
    public static function getGrantInfoByID(int $gID) : CityGrant
    {
        return self::where("id", $gID)->firstOrFail();
    }

    /**
     * Get all city grants.
     *
     * @return Collection
     */
    public static function getAllCityGrants() : Collection
    {
        return self::orderBy("grantNum")->get();
    }

    /**
     * Edit a city grant.
     *
     * @param Request $request
     */
    public static function editGrant(Request $request)
    {
        // Remove script tags as this doesn't go though that html entities thingie when displaying
        $html = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $request->notes);
        $grant = self::getGrantInfoByID($request->id);
        $grant->grantNum = $request->grantNum;
        $grant->amount = $request->amount;
        $grant->infPerCity = $request->infPerCity;
        $grant->irondome = $request->irondome ?? 0;
        $grant->NRF = $request->NRF ?? 0;
        $grant->mmrScore = $request->mmrScore;
        $grant->notes = $html;
        $grant->enabled = $request->enabled ?? 0;
        $grant->save();
        \App\Models\Log::createLog("cityGrant", "Edited grant ID - $grant->id");
    }

    /**
     * Delete a city grant.
     *
     * @param int $gID
     * @throws \Exception
     */
    public static function deleteGrant(int $gID)
    {
        $grant = self::getGrantInfoByID($gID);
        $grant->delete();
    }

    /**
     * Create a city grant.
     *
     * @param Request $request
     */
    public static function createGrant(Request $request)
    {
        $grant = new self;
        $html = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $request->notes);
        $grant->grantNum = $request->grantNum;
        $grant->amount = $request->amount;
        $grant->infPerCity = $request->infPerCity;
        $grant->irondome = $request->irondome ?? 0;
        $grant->NRF = $request->NRF ?? 0;
        $grant->mmrScore = $request->mmrScore;
        $grant->notes = $html;
        $grant->enabled = $request->enabled ?? 0;
        $grant->save();
    }
}
