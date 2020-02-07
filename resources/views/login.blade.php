@extends('layouts.master')
@section('content')
<link rel="stylesheet" type="text/css" href="css/login.css">
<div class="container">
    <div class="card card-container">
        <div class="login-img-header">
            <i class="lock-login fas fa-lock"></i>
        </div>
        <p id="profile-name" class="profile-name-card"></p>
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
                <input type="text" name="email" id="inputEmail" class="form-control" placeholder="Username" required autofocus>
                <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password" required>
            </div>
            <button class="btn btn-lg btn-primary btn-block btn-signin" type="submit">Sign in</button>
            @if (!config('ldap.enabled'))
            <button class="btn btn-lg btn-primary btn-block btn-reg" type="button">Register</button>
            @endif
        </form>
    </div>
</div>
@endsection
