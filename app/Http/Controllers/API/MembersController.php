<?php

namespace App\Http\Controllers\API;

use App\Defense\DefenseNations;
use App\Http\Controllers\Controller;

class MembersController extends Controller
{
    /**
     * Gets a list of members with their sign in stuff and returns a json response.
     *
     * @return mixed
     */
    public function members()
    {
        $x = DefenseNations::where("inBK", true)->paginate(50);

        return response($x->toJson(149))->header("Content-Type", "application/json");
    }
}
