<?php
namespace App\Http\Controllers;

use App\Credential;
use App\Encryptedcredential;
use App\Helpers\Encryption;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CredentialsController extends Controller
{
    public function index(Request $request, Credential $credential) {
        $this->authorize('delete', $credential);

        return view('credential.index', compact('credential'));
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

        if ($request->wantsJson()) {
            return response(['status' => 'OK']);
        } else {
            return redirect(route('group', $params['currentgroupid']));
        }
    }

    public function delete(Request $request, Credential $credential)
    {
        $this->authorize('delete', $credential);
        $group = $credential->groupid;
        $credential->deleteCredential();

        return redirect(route('group', $group));
    }
}
