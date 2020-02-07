<?php

namespace App\Http\Controllers;

use App\Encryptedcredential;
use App\Group;
use App\User;
use App\Helpers\Encryption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GroupUnshareController extends Controller
{
    public function store(Request $request, Group $group, User $user)
    {
        $this->authorize('update', $group);

        \App\Encryptedcredential::where('userid', $user->id)->delete();
        $user->groups()->detach($group);


        return response(['status' => 'OK']);
    }
}
