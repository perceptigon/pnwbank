<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stats extends Model
{
    public $timestamps = false;

    public $fillable = [
        "date", "type", "value",
    ];
}
