@extends('layouts.master')
@section('content')
<div class="container">
    <form method="post" class="form-horizontal" id="createGroupForm">
        @csrf
        <div class="form-group">
            <label for="groupname">Group name</label>
            <input type="text" id="groupname" name="groupname" class="form-control" placeholder="Group name">
        </div>
        <button type="button" id="createGroup" class="btn btn-primary">Create group</button>
    </form>
</div>
@endsection
