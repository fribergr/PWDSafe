@extends('layouts.master')
@section('content')
<div class="container">
    <form method="post" class="form-horizontal" id="pwdchangeform">
        <div class="form-group">
            <label for="oldpwd">Old password</label>
            <input type="password" id="oldpwd" name="oldpwd" class="form-control" placeholder="Old password">
        </div>
        <div class="form-group">
            <label for="newpwd1">New password</label>
            <input type="password" id="newpwd1" name="newpwd1" class="form-control" placeholder="New password">
        </div>
        <div class="form-group">
            <label for="newpwd2">Repeat</label>
            <input type="password" id="newpwd2" name="newpwd2" class="form-control" placeholder="Repeat">
        </div>
        <button type="button" id="changePwd" class="btn btn-primary">Change password</button>
    </form>
</div>
@endsection()
