<?php

namespace App\Classes;

use Illuminate\Support\Collection;

class Wars
{
    /**
     * Stores the wars.
     *
     * @var Collection
     */
    protected $result;

    /**
     * Wars constructor.
     *
     * Grabs the wars from the API and stores them in a collection
     *
     * @param int $numWars
     */
    public function __construct(int $numWars = 500)
    {
        $client = new PWClient();
        $json = $client->getPage("http://politicsandwar.com/api/wars/{$numWars}/?key=".env("PW_API_KEY"));
        $decoded = \json_decode($json, true);
        $this->result = Collection::make($decoded["wars"]);
    }

    /**
     * Calls two methods to grab both offensive and defensive wars and returns one collection.
     *
     * @param string $name
     * @return Collection
     */
    public function getWarsByAllianceName(string $name) : Collection
    {
        $offensiveWars = $this->getAttackingWarsByAllianceName($name);
        $defensiveWars = $this->getDefendingWarsByAllianceName($name);

        return $offensiveWars->merge($defensiveWars);
    }

    /**
     * Filters through the wars and returns a collection of defensive wars by alliance name.
     *
     * @param string $name
     * @return Collection
     */
    public function getDefendingWarsByAllianceName(string $name) : Collection
    {
        return $this->result->where("defenderAA", $name);
    }

    /**
     * Filters through the wars and returns a collection of offensive wars by alliance name.
     *
     * @param string $name
     * @return Collection
     */
    public function getAttackingWarsByAllianceName(string $name) : Collection
    {
        return $this->result->where("attackerAA", $name);
    }

    public function getWarsMinutesAgo(int $mins)
    {

    }

    public function getWarsByNation(int $nID) : Int
    {
        $numWars = 0;

        $client = new PWClient();
        $html = $client->getPage("https://politicsandwar.com/nation/id={$nID}&display=war");

        while (strpos($html, "Active War"))
        {
            $html = stristr($html, "Active War");
            $numWars++;
        }

        return $numWars;
    }
}
