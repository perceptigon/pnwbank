<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    public $guarded = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function fromAccountRel()
    {
        return $this->belongsTo('\App\Models\Accounts', 'fromAccountID');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function toAccountRel()
    {
        return $this->belongsTo('App\Models\Accounts', 'toAccountID');
    }

    /**
     * @param int $accountID
     * @param int $amount
     * @return mixed
     */
    public static function getLastTransactions(int $accountID, int $amount = 50)
    {
        return self::where("toAccountID", $accountID)
            ->orWhere("fromAccountID", $accountID)
            ->paginate($amount);
    }
}
