<?php

namespace App\Http\Controllers;

use App\Classes\Output;
use Illuminate\Http\Request;
use Auth;

class VerifyController extends Controller
{
    protected $request;
    protected $output;

    public function __construct(Request $request)
    {
        $this->middleware("auth");

        $this->request = $request;
        $this->output = new Output();
    }

    public function verifyAccount(string $token = null)
    {
        if (Auth::user()->isVerified)
            return redirect("/");

        $this->output->addWarning("If you did not create this account, <strong>DO NOT</strong> verify the account. Contact a Government member ASAP");

        return view("verify", [
            "output" => $this->output,
            "token" => $token
        ]);
    }

    public function verifyAccountPost()
    {
        $this->validate($this->request, [
            "verifyToken" => "required",
            "g-recaptcha-response" => "required"
        ]);

        // Verify the captcha
        $response = \json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".env("G-CAPTCHA-SECRET")."&response=".urlencode($this->request->input('g-recaptcha-response'))."&remoteip=".$this->request->ip()));

        if ($response->success == false)
        {
            $this->output->addError("You failed the captcha YOU FUCKING ROBOT ASS");
            return $this->verifyAccount();
        }

        // Captcha passed, now let's verify
        if ($this->request->verifyToken != Auth::user()->verifyToken)
        {
            $this->output->addError("That token is invalid");
            return $this->verifyAccount();
        }

        // Validation passed, verify the account
        Auth::user()->isVerified = true;
        Auth::user()->save();

        return redirect("/accounts");
    }

    public function notVerified()
    {
        return view("notVerified");
    }
}
