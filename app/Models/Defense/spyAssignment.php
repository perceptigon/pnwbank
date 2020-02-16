<?php

namespace App\Models\Defense;

use Illuminate\Database\Eloquent\Model;

class spyAssignment extends Model
{
    protected $connection = 'defense';
    protected $table = 'spyAssignments';

    public function attacker()
    {
        return $this->belongsTo(spyAttacker::class);
    }

    public function defender()
    {
        return $this->belongsTo(spyDefender::class);
    }
}
