<?php

namespace App\Http\Controllers;

use App\Group;
use Illuminate\Http\Request;

class GroupDeleteController extends Controller
{
    public function index(Group $group)
    {
        $this->authorize('delete', $group);

        return view('group.delete', compact('group'));
    }

    public function delete(Request $request, Group $group)
    {
        $this->authorize('delete', $group);
        $group->deleteGroup();

        return $request->wantsJson() ? response(['status' => 'OK']) : redirect('/');
    }
}
