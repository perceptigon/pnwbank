<?php

namespace App\Forums;

use Illuminate\Database\Eloquent\Model;

class ForumFields extends Model
{
    protected $connection = "forums";
    protected $table = "ipb_core_pfields_content";
}
