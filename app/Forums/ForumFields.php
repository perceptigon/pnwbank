<?php

namespace App\Forums;

use Illuminate\Database\Eloquent\Model;

class ForumFields extends Model
{
    protected $connection = "forums2";
    protected $table = "core_pfields_content";
}
