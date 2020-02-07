@extends('layouts.master')
@section('content')
<div class="container">
    <div class="alert alert-danger">
        <p><strong>Are you sure</strong> you wish to delete group "<strong>{{ $group->name }}</strong>" and all of its saved credentials?</p>
        <button class="btn btn-danger" id="deletegroup" data-id="{{ $group->id }}">Yes, I'm sure</button>
    </div>
</div>
@endsection
