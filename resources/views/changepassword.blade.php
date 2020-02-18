@extends('layouts.master')
@section('content')
<div class="container">
    <form method="post" class="form-horizontal" action="{{ route('changepassword') }}">
        @csrf
        <div class="form-group">
            <label for="oldpwd">Old password</label>
            <input type="password" id="oldpwd" name="oldpwd" class="form-control" placeholder="Old password">
        </div>
        <div class="form-group">
            <label for="password">New password</label>
            <input type="password" id="password" name="password" class="form-control" placeholder="New password">
        </div>
        <div class="form-group">
            <label for="password_confirmation">Repeat</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Repeat">
        </div>
        <button type="submit" class="btn btn-primary">Change password</button>
    </form>
</div>
@endsection()
