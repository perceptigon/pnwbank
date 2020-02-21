<?php

namespace App\Classes;

// I don't feel like commenting this right now and making it nice
class City
{
    public function __construct($cID) {
        $jsondata = file_get_contents("https://politicsandwar.com/api/city/id=$cID&key=".env"("PW_API_KEY")");
        $json = json_decode($jsondata, true);
        $this->name = $json["name"];
        $this->founded = $json["founded"];
        $this->age = $json["age"];
        $this->powered = $json["powered"];
        $this->infra = $json["infrastructure"];
        $this->land = $json["land"];
        $this->population = $json["population"];
        $this->crime = $json["crime"];
        $this->disease = $json["disease"];
        $this->commerce = $json["commerce"];
        $this->avgIncome = $json["avgincome"];
        $this->pollution = $json["pollution"];
        $this->nukePollution = $json["nuclearpollution"];
        $this->coalPower = $json["imp_coalpower"];
        $this->oilPower = $json["imp_oilpower"];
        $this->nuclearPower = $json["imp_nuclearpower"];
        $this->windPower = $json["imp_windpower"];
        $this->coalMine = $json["imp_coalmine"];
        $this->oilWell = $json["imp_oilwell"];
        $this->ironMine = $json["imp_ironmine"];
        $this->bauxiteMine = $json["imp_bauxitemine"];
        $this->leadMine = $json["imp_leadmine"];
        $this->uraniumMine = $json["imp_uramine"];
        $this->farm = $json["imp_farm"];
        $this->oilRefinery = $json["imp_gasrefinery"];
        $this->steelMill = $json["imp_steelmill"];
        $this->aluminumRefinery = $json["imp_aluminumrefinery"];
        $this->munitionsFactory = $json["imp_munitionsfactory"];
        $this->policeStation = $json["imp_policestation"];
        $this->hospital = $json["imp_hospital"];
        $this->recyclingCenter = $json["imp_recyclingcenter"];
        $this->subway = $json["imp_subway"];
        $this->supermarket = $json["imp_supermarket"];
        $this->bank = $json["imp_bank"];
        $this->shoppingMall = $json["imp_mall"];
        $this->stadium = $json["imp_stadium"];
        $this->barracks = $json["imp_barracks"];
        $this->factory = $json["imp_factory"];
        $this->airBase = $json["imp_hangar"];
        $this->drydock = $json["imp_drydock"]; // TODO fix this stuff
        $this->basepop = $json["basepop"];
        $this->basePopDensity = $json["basepopdensity"];
        $this->minWage = $json["minimumwage"];
        $this->popLostDisease = $json["poplostdisease"];
        $this->popLostCrime = $json["poplostcrime"];
    }

    public function countImprovementSlots()
    {
        $slots = 0;
        $slots += $this->coalPower;
        $slots += $this->oilPower;
        $slots += $this->nuclearPower;
        $slots += $this->windPower;
        $slots += $this->coalMine;
        $slots += $this->ironMine;
        $slots += $this->oilWell;
        $slots += $this->bauxiteMine;
        $slots += $this->leadMine;
        $slots += $this->uraniumMine;
        $slots += $this->farm;
        $slots += $this->oilRefinery;
        $slots += $this->steelMill;
        $slots += $this->aluminumRefinery;
        $slots += $this->munitionsFactory;
        $slots += $this->policeStation;
        $slots += $this->hospital;
        $slots += $this->recyclingCenter;
        $slots += $this->subway;
        $slots += $this->supermarket;
        $slots += $this->bank;
        $slots += $this->shoppingMall;
        $slots += $this->stadium;
        $slots += $this->barracks;
        $slots += $this->factory;
        $slots += $this->airBase;
        $slots += $this->drydock;

        return $slots;
    }

    public function totalImprovementSlots()
    {
        $slots = floor($this->infra / 50);

        return $slots;
    }
}
