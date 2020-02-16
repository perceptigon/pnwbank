<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attacker extends Model
{
    protected $connection = 'defense';

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }
}
