<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ChangePasswordController extends Controller
{
    public function index()
    {
        return view('changepassword');
    }

    public function post(Request $request)
    {
        abort_if(config('ldap'), '403');

        $validated = $request->validate([
            'oldpwd' => 'required',
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        if (session()->get('password') !== $validated['oldpwd']) {
            throw ValidationException::withMessages(['oldpwd' => 'Old password missmatch']);
        }

        auth()->user()->changePassword($validated['password']);

        return redirect()->back()->with('success');
    }
}
