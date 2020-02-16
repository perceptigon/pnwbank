<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unmask extends Model
{
    protected $connection = 'ia';

    protected $table = "unmasks";
}
