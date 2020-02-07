@extends('layouts.master')
@section('content')
<div class="container">
    <div class="clearfix">
        <div class="float-left" style="margin-bottom: 20px">
            <h3>Search</h3>
        </div>
    </div>
    @if ($data->count() > 0)
    <div class="card-columns">
        @foreach ($data as $row)
            @include('partials._credentialCard', ['credential' => $row, 'showGroupName' => true])
        @endforeach
    </div>
    @else
    <div class="alert alert-info" role="alert">
        <strong>No credentials found!</strong> Try searching for something else.
    </div>
    @endif
    @include('partials._modalshowCred')
</div>
@endsection
