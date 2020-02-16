<?php

namespace App\Classes;

class PWFunctions
{
    /**
     * @deprecated 3.1.0
     * @deprecated Will return nothing and will log an error
     * @return void
     */
    public static function endMessage()
    {

    }

    /**
     * Gets all the nation IDs in an alliance.
     *
     * For backwards comparability, make the $aID optional but it is not needed anymore.
     *
     * @param int $aID Alliance ID
     * @return array
     */
    public static function getAllianceNationIDs(int $aID = null) : array
    {
        $nIDs = [];

        $client = new PWClient();
        $jsondata = $client->getPage("https://politicsandwar.com/api/nations/?key=".env("PW_API_KEY"));
        $json = json_decode($jsondata);

        foreach ($json->nations as $nation)
        {
            if ($nation->allianceid == $aID)
                array_push($nIDs, $nation->nationid);
        }

        return $nIDs;
    }
}
