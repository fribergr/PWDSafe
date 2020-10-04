<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\LdapAuthentication;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function attemptLogin(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!config('ldap.enabled') && Auth::attempt($credentials)) {
            session()->put('password', $credentials['password']);

            return true;
        } else if (config('ldap.enabled') && LdapAuthentication::login($credentials['email'], $credentials['password'])) {
            $user = \App\User::where('email', $credentials['email'])->first();

            if (!$user) {
                \App\User::registerUser($credentials['email'], $credentials['password']);
                $user = \App\User::where('email', $credentials['email'])->first();
            }

            Auth::loginUsingId($user->id);
            session()->put('password', $credentials['password']);

            return true;
        }

        return false;
    }

    protected function authenticated(Request $request, $user)
    {
        return redirect(route('group', $user->primarygroup));
    }
}
