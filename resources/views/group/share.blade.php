@extends('layouts.master')
@section('content')
<div class="container">
    <h3 style="margin-bottom: 28px">{{ $group->name }}</h3>
    @if ($group->usersWithoutCurrentUser->count() > 0)
    <table class="table table-bordered">
        <thead>
        <th>Username</th>
        <th style="width: 1px;">Actions</th>
        </thead>
        <tbody>
        @foreach ($group->usersWithoutCurrentUser as $user)
        <tr>
            <td>{{ $user->email }}</td>
            <td><a class="btn btn-danger removeUser" data-id="{{ $user->id }}" data-groupid="{{ $group->id }}" href="#"><i class="far fa-trash-alt"></i> Remove</a></td>
        </tr>
        @endforeach
        </tbody>
    </table>
    @else
    <div class="alert alert-info" role="alert">
        <strong>Not shared!</strong> This group isn't shared with anyone yet.
    </div>
    @endif
    <input type="hidden" id="currentgroupid" value="{{ $group->id }}">
    <button id="shareGroup" class="btn btn-primary">Share group</button>
    @include('partials._modalShareGroup')
</div>
@endsection
