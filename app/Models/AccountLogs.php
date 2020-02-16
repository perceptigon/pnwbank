<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountLogs extends Model
{
    public $table = "accountlogs";

    /**
     * Relationship between the log and the account
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account()
    {
        return $this->belongsTo('\App\Models\Accounts', "accountID");
    }
}
