<?php

namespace App\Models;

use App\Classes\Nation;
use Illuminate\Database\Eloquent\Model;

class Members extends Model
{
    // TODO This class
    public static function getMember(int $nID) : self
    {
        return self::where("nID", $nID)->firstOrFail();
    }

    public function update(Nation $nation)
    {
        $this->nID = $nation->nID;
        $this->name = $nation->name;
        $this->leader = $nation->leader;
        $this->score = $nation->score;
        $this->activity = $nation->lastActive;
        $this->cities = $nation->cities;
        $this->infra = $nation->infra;
        $this->land = $nation->land;
        $this->ironWorks = $nation->ironworks;
        $this->baxuiteWorks = $nation->bauxiteworks;
        $this->armsStockpile = $nation->armsStockpile;
        $this->gasReserve = $nation->emgGasReserve;
        $this->massIrrigation = $nation->massIrrigation;
        $this->intTradeCenter = $nation->intTradeCenter;
        $this->mlp = $nation->missilePad;
        $this->irondome = $nation->ironDome;
        $this->vitalDefSys = $nation->vitalDefSys;
        $this->cia = $nation->cia;
        $this->uraniumEnrich = $nation->uraniumEnrich;
        $this->popBureau = $nation->popBureau;
        $this->cenCivEng = $nation->cenCivEng;
        $this->save();
    }

    public static function getAllNIDs() : array
    {
        $nIDs = self::select("nID"); // This returns a collection
        return $nIDs->toArray(); // We want it to be an array. So convert it to an array and return
    }
}
