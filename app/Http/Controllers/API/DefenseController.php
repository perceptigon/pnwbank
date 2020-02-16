<?php
namespace App\Http\Controllers\API;

use App\Defense\DefenseSignin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class DefenseController extends Controller
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * DefenseController constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Do the sign in with the API
     */
    public function signin()
    {
        $this->validate($this->request, [
            "nID" => "required|integer|min:0",
            "money" => "required|numeric|min:0",
            "food" => "required|integer|min:0",
            "steel" => "required|integer|min:0",
            "gas" => "required|integer|min:0",
            "munitions" => "required|integer|min:0",
            "aluminum" => "required|integer|min:0",
            "discord" => "required|in:yes,no",
            "update.*" => "required|in:never,monday,tuesday,wednesday,thursday,friday,saturday,sunday"
        ]);

        try
        {
            $signin = DefenseSignin::doSignIn($this->request);

            return response($signin->toJson())->header("Content-Type", "application/json");
        }
        catch (\Exception $e)
        {
            return response(\json_encode(["error" => $e->getMessage()]), 422)->header("Content-Type", "application/json");
        }
    }

}