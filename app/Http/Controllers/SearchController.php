<?php
namespace App\Http\Controllers;

use App\Credential;
use App\Policies\CredentialPolicy;
use Illuminate\Database\Eloquent\Model as Eloquent;

class SearchController extends Eloquent
{
    public function index($search) {
        $groups = auth()->user()->groups->pluck('id');
        $credentials = Credential::with('group:id,name')->whereIn('groupid', $groups)->where(function($query) use ($search) {
            $query->where('site', 'like', '%' . $search . '%')
                ->orWhere('username', 'like', '%' . $search . '%');
        })->get();

        return view('search', ['data' => $credentials]);
    }
}
