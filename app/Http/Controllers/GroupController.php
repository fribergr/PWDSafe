<?php

namespace App\Http\Controllers;

use App\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function index(Request $request, Group $group)
    {
        $this->authorize('view', $group);

        $credentials = \App\Credential::with('group:id,name')->where('groupid', $group->id)->get();

        return view('group', compact('group', 'credentials'));
    }

    public function create()
    {
        return view('group.create');
    }

    public function store(Request $request)
    {
        $params = $this->validate($request, ['groupname' => 'required']);
        $group = new Group;
        $group->name = $params['groupname'];
        $group->save();
        auth()->user()->groups()->attach($group);

        return response([
            'status' => "OK",
            "groupid" => $group->id
        ]);
    }
}
