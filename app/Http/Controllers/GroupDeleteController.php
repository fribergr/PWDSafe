<?php

namespace App\Http\Controllers;

use App\Group;

class GroupDeleteController extends Controller
{
    public function index(Group $group)
    {
        $this->authorize('delete', $group);

        return view('group.delete', compact('group'));
    }

    public function delete(Group $group)
    {
        $this->authorize('delete', $group);
        $group->deleteGroup();

        return response(['status' => 'OK']);
    }
}
