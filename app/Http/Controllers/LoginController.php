<?php

namespace App\Http\Controllers;

use App\Helpers\LdapAuthentication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function post(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!config('ldap.enabled') && Auth::attempt($credentials)) {
            session()->put('password', $credentials['password']);
            return redirect()->intended('groups/' . Auth::user()->primarygroup);
        } else if (config('ldap.enabled') && LdapAuthentication::login($credentials['email'], $credentials['password'])) {
            $user = \App\User::where('email', $credentials['email'])->first();

            if (!$user) {
                \App\User::registerUser($credentials['email'], $credentials['password']);
                $user = \App\User::where('email', $credentials['email'])->first();
            }

            Auth::loginUsingId($user->id);
            session()->put('password', $credentials['password']);
            return redirect()->intended('groups/' . $user->primarygroup);
        }

        return redirect()->back()->withErrors(['msg' => 'Authentication failure']);
    }
}
