<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $connection = 'defense';

    public function attacker()
    {
        return $this->belongsTo(Attacker::class);
    }

    public function defender()
    {
        return $this->belongsTo(Defender::class);
    }
}
