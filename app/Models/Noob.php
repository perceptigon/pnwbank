<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Noob extends Model
{
    protected $connection = 'ia';

    public function careBear()
    {
        return $this->belongsTo('App\Models\Carebear', "carebear_id");
    }
}
