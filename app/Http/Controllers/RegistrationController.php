<?php

namespace App\Http\Controllers;

use App\Helpers\Encryption;
use App\User;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    public function post(Request $request)
    {
        abort_if(config('ldap.enabled'), 403);
        $this->validate($request, [
            'user' => ['required', 'email', 'unique:users,email'],
            'pass' => ['required', 'min:8'],
        ]);

        User::registerUser($request->input('user'), $request->input('pass'));

        return ['status' => 'OK'];
    }
}
