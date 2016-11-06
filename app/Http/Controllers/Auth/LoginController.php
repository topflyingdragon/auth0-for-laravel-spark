<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Spark\Spark;

class LoginController extends Controller
{
    use AuthenticatesUsers {
        AuthenticatesUsers::login as traitLogin;
    }

    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {

        $this->middleware('guest', ['except' => 'logout']);
    }

    public function auth0Login(){
        return view("auth0login");
    }

    public function auth0callback(Request $request)
    {
        $service = \App::make('auth0');
        $userData = $service->getUser();
        if($userData){
            $profile = $userData['profile'];
            $email = $profile['email'];
            $user = User::where("email", $email)->first();

            if ($user === null) {
                $user = new User();
                $user->name = $profile['name'];
                $user->email = $profile['email'];
                $user->photo_url = $profile['picture'];
                $user->password = bcrypt('123456');
                $user->save();
            }
            //login manually
            Auth::login($user);


            $request->session()->put('spark:auth-remember', $request->remember);
            $user = Spark::user()->where('email', $email)->first();
            if (Spark::usesTwoFactorAuth() && $user && $user->uses_two_factor_auth) {
                $request->merge(['remember' => '']);
            }

            return redirect()->intended('/home');
        }else{
            return redirect()->intended('/auth0/login');
        }

    }
}
