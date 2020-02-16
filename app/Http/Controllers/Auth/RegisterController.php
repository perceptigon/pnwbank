<?php

namespace App\Http\Controllers\Auth;

use App\Classes\Nation;
use App\Jobs\SendVerifyMessage;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    protected $username = 'username';

    protected $nID = "nID";

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $validator = Validator::make($data, [
            'username' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
            'nID' => 'required|unique:users,nID',
        ]);

        $validator->after(function($validator) use ($data) { // Now validate the nation
            try
            {
                $nation = new Nation($data["nID"]);

                if ($nation->aID != 4937)
                    $validator->errors()->add('field', "That nation isn't in BK");

                if ($nation->alliancePosition == 1)
                    $validator->errors()->add('field', "Please wait until you are accept in-game to create your account");
            }
            catch (\Exception $e)
            {
                $validator->errors()->add('field', "That nation doesn't exist");
            }
        });

        return $validator;
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'nID' => $data['nID'],
            'verifyToken' => $this->genVerifyToken(),
        ]);

        // Dispatch the message job
        dispatch(new SendVerifyMessage($user));

        return $user;
    }

    /**
     * Generates a random string to validate the account
     *
     * @return string
     */
    protected function genVerifyToken() : string
    {
        return bin2hex(random_bytes(16));
    }
}
