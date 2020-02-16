<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class Market extends Model
{
    public $timestamps = false;
    protected $table = 'market';

    /**
     * Get the information about a resource including how much we're selling and PPU.
     *
     * @param string $resource
     * @return Market
     */
    public static function getResourceInfo(string $resource) : Market
    {
        return self::where("resource", $resource)->first();
    }

    /**
     * Get all offers.
     *
     * @return Collection
     */
    public static function getOffers() : Collection
    {
        return self::all();
    }
}
