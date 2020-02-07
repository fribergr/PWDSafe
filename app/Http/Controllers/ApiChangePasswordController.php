<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ApiChangePasswordController extends Controller
{
    public function store(Request $request)
    {
        $params = $this->validate($request, [
            'username' => 'required',
            'old_password' => 'required',
            'new_password' => 'required',
        ]);

        $user = \App\User::where('email', $params['username'])->first();

        abort_if(is_null($user), 403);
        abort_unless(Hash::check($params['old_password'], $user->password), 403);

        $user->password = Hash::make($params['new_password']);
        $user->save();

        return response(['status' => 'OK']);
    }
}
