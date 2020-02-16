<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Defender extends Model
{

    protected $connection = 'defense';

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }
}
