<?php

namespace App\Http\Controllers;

use App\Group;
use Illuminate\Http\Request;

class GroupChangeNameController extends Controller
{
    public function index(Request $request, Group $group)
    {
        $this->authorize('updateExceptPrimary', $group);

        return view('group.name', compact('group'));
    }

    public function store(Request $request, Group $group)
    {
        $this->authorize('updateExceptPrimary', $group);
        $params = $this->validate($request, [
            'groupname' => 'required|max:100'
        ]);

        $groupname = preg_replace('/[^\p{L}\p{N}\-_ ]/u', "", trim($params['groupname']));

        abort_if(strlen($groupname) === 0, 400);

        $group->name = $groupname;
        $group->save();

        return $request->wantsJson() ? response(['status' => 'OK']) : redirect(route('group', $group->id));
    }
}
