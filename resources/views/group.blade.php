@extends('layouts.master')
@section('content')
<div class="container">
    <div class="clearfix">
        <div class="float-left" style="margin-bottom: 20px">
            <h3>
                @if ($group->id !== auth()->user()->primarygroup)
                    {{ $group->name }}
                @else
                    Private
                @endif
            </h3>
        </div>
        <div class="btn-group float-right">
            <button id="addCred" class="btn btn-outline-primary"><i class="fa fa-plus"></i> Add</button>
            <button id="importCred" class="btn btn-outline-secondary"><i class="fa fa-file-import"></i> Import</button>
            @if (auth()->user()->primarygroup != $group->id)
            <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-cog"></i>
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="#" id="changeGroupName">Change name</a>
                <a class="dropdown-item" href="/groups/{{ $group->id }}/share">Share</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="/groups/{{ $group->id }}/delete"><i class="far fa-trash-alt"></i> Delete</a>
            </div>
            @include('partials._modalChangeGroupName', ['groupname' => $group->name])
            @endif
        </div>
    </div>
    @if ($credentials->count() > 0)
    <div class="card-columns">
        @foreach($credentials as $credential)
            @include('partials._credentialCard', ['credential' => $credential, 'showGroupName' => false])
        @endforeach
    </div>
    @else
    <div class="alert alert-info" role="alert">
        <strong>No credentials!</strong> You can add some below if you'd like.
    </div>
    @endif
    <input type="hidden" id="groupid" value="{{ $group->id }}">
    @include('partials._modaladdCred')
    @include('partials._modalimportCred', ['groupid' => $group->id])
    @include('partials._modalshowCred', ['currentgroup' => $group])
</div>
@endsection('content')
