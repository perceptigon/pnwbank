<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deposits extends Model
{
    public $guarded = [];

    /**
     * Relationship between the deposit and it's associated account.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account()
    {
        return $this->belongsTo('App\Models\Accounts', 'accountID');
    }

    /**
     * Returns a collection of active deposits
     *
     * @return mixed
     */
    public static function getActiveDeposits()
    {
        return self::where("pending", true)->get();
    }
}
