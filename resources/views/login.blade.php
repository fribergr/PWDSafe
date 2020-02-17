@extends('layouts.master')
@section('content')
<div class="container">
    <div class="card-container max-w-sm px-12 py-10 mx-auto shadow-md bg-gray-200 border">
        <div class="w-full text-center my-8 mb-16">
            <i class="fas fa-lock text-6xl text-gray-600"></i>
        </div>
        <form class="form-signin" method="post">
            @csrf
            <div class="form-group
            @if ($errors->any())
                has-error
            @endif
            " id="loginForm">
                @if ($errors->any())
                <div class="alert alert-danger">Wrong username or password</div>
                @endif
                <div class="alert alert-success d-none" id="regsuccess">Account registered successfully</div>
                <div class="alert alert-info d-none" id="working">Working on it...</div>
                <input type="text" name="email" id="inputEmail" class="form-control mb-1" placeholder="Username" required autofocus>
                <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password" required>
            </div>
            <button class="btn-signin font-bold h-8 bg-gray-600 hover:bg-gray-700 w-full rounded text-white transition-colors duration-100" type="submit">Sign in</button>
            @if (!config('ldap.enabled'))
            <button class="btn-reg font-bold h-8 bg-gray-600 hover:bg-gray-700 w-full rounded text-white mt-1 transition-colors duration-100" type="button">Register</button>
            @endif
        </form>
    </div>
</div>
@endsection
