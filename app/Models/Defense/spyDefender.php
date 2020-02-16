<?php

namespace App\Models\Defense;

use Illuminate\Database\Eloquent\Model;

class spyDefender extends Model
{
    protected $connection = 'defense';
    protected $table = 'spyDefenders';

    public function assignments()
    {
        return $this->hasMany(spyAssignment::class, 'defender_id');
    }
}
