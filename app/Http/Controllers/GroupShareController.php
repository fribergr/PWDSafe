<?php

namespace App\Http\Controllers;

use App\Encryptedcredential;
use App\Group;
use App\Helpers\Encryption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GroupShareController extends Controller
{
    public function index(Request $request, Group $group)
    {
        $this->authorize('update', $group);

        return view('group.share', compact('group'));
    }

    public function store(Request $request, Group $group)
    {
        $this->authorize('update', $group);
        $params = $this->validate($request, ['email' => 'required']);

        $user = \App\User::where('email', $params['email'])->first();
        if (is_null($user)) {
            return response(['status' => 'Fail', 'reason' => 'User does not exist'], 404);
        }

        if ($user->groups->contains('id', $group->id)) {
            return response(['status' => 'Fail', 'reason' => 'User already in group'], 202);
        }

        $user->groups()->attach($group);

        $sql = "SELECT encryptedcredentials.data, encryptedcredentials.credentialid FROM encryptedcredentials
                        INNER JOIN credentials ON credentials.id = encryptedcredentials.credentialid
                        INNER JOIN groups ON credentials.groupid = groups.id
                        INNER JOIN usergroups ON usergroups.groupid = groups.id
                        WHERE usergroups.groupid = :groupid AND usergroups.userid = :userid
                        AND encryptedcredentials.userid = :userid2";
        $result = DB::select($sql, [
            'groupid' => $group->id,
            'userid' => auth()->user()->id,
            'userid2' => auth()->user()->id,
        ]);
        $encryption = app(Encryption::class);

        foreach ($result as $row) {
            $data = $encryption->decWithPriv(
                base64_decode($row->data),
                $encryption->dec(auth()->user()->privkey, session()->get('password'))
            );
            $encryptedcred = new Encryptedcredential;
            $encryptedcred->credentialid = $row->credentialid;
            $encryptedcred->userid = $user->id;
            $encryptedcred->data = base64_encode($encryption->encWithPub($data, $user->pubkey));
            $encryptedcred->save();
        }

        return response(['status' => 'OK']);
    }
}
