<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LogoutController extends Controller
{
    public function store()
    {
        Auth::logout();
        Session::flush();

        return redirect('/');
    }
}
