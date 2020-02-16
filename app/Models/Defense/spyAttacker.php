<?php

namespace App\Models\Defense;

use Illuminate\Database\Eloquent\Model;

class spyAttacker extends Model
{
    protected $connection = 'defense';
    protected $table = 'spyAttackers';

    public function assignments()
    {
        return $this->hasMany(spyAssignment::class, 'attacker_id');
    }
}
