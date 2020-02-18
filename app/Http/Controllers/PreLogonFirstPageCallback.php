<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class PreLogonFirstPageCallback extends Controller
{
    public function index() {
        return redirect()->to('/groups/' . Auth::user()->primarygroup);
    }
}
