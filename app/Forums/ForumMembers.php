<?php

namespace App\Forums;

use Illuminate\Database\Eloquent\Model;

class ForumMembers extends Model
{
    protected $connection = "forums2";
    protected $table = "core_members";
}
