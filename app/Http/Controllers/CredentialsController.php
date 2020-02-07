<?php
namespace App\Http\Controllers;

use App\Credential;
use App\Encryptedcredential;
use App\Helpers\Encryption;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CredentialsController extends Controller
{
    public function store(Request $request)
    {
        $params = $this->validate($request, [
            'creds' => 'required',
            'credu' => 'required',
            'credp' => 'required',
            'credn' => 'nullable',
            'currentgroupid' => 'required',
        ]);

        if (!auth()->user()->groups->contains('id', $params['currentgroupid'])) {
            $params['currentgroupid'] = auth()->user()->primarygroup;
        }

        $credential = new Credential;
        $credential->groupid = $params['currentgroupid'];
        $credential->site = $params['creds'];
        $credential->username = $params['credu'];
        $credential->notes = $params['credn'];
        $credential->save();

        $group = \App\Group::where('id', $params['currentgroupid'])->first();
        $users = $group->users()->get()->pluck('pubkey', 'id');

        foreach ($users as $userid => $pubkey) {
            $encrypted = new Encryptedcredential;
            $encrypted->credentialid = $credential->id;
            $encrypted->userid = $userid;
            $encrypted->data = base64_encode(app(Encryption::class)->encWithPub($params['credp'], $pubkey));
            $encrypted->save();
        }

        return response(['status' => 'OK']);
    }

    public function update(Request $request, Credential $credential)
    {
        $this->authorize('update', $credential);
        $params = $this->validate($request, [
            'creds' => 'required',
            'credu' => 'required',
            'credp' => 'required',
            'currentgroupid' => 'required',
            'credn' => 'nullable',
        ]);

        if ($credential->groupid != $params['currentgroupid']) {
            Credential::addCredentials($params);
            $credential->delete();
        } else {
            Credential::updateCredentials($credential, $params);
        }

        return response(['status' => 'OK']);
    }

    public function delete(Request $request, Credential $credential)
    {
        $this->authorize('delete', $credential);
        $credential->deleteCredential();

        return response(['status' => 'OK']);
    }
}
