<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForumUser extends Model
{
    protected $connection = 'forums';
    protected $table = 'ipb_core_members';

}
