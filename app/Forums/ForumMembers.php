<?php

namespace App\Forums;

use Illuminate\Database\Eloquent\Model;

class ForumMembers extends Model
{
    protected $connection = "forums";
    protected $table = "ipb_core_members";
}
