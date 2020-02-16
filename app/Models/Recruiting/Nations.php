<?php

namespace App\Models\Recruiting;

use Illuminate\Database\Eloquent\Model;

class Nations extends Model
{
    protected $connection = 'recruiting';
    public $timestamps = false;
    public $table = "nations";
}
