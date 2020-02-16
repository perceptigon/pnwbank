<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    public $timestamps = false;

    /**
     * Get the current settings.
     *
     * @return array
     */
    public static function getSettings() : array
    {
        $query = self::all(); // Get all the settings
        $settings = []; // Setup empty array

        foreach ($query as $q) // Put them in an array to return
        {
            $settings[$q->sKey] = $q->value;
        }

        return $settings;
    }
}
