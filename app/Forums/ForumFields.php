<?php

namespace App\Forums;

use Illuminate\Database\Eloquent\Model;

class ForumFields extends Model
{
    protected $connection = "forums2";
    protected $table = "ipb_core_pfields_content";
}
