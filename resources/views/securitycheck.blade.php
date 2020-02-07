@extends('layouts.master')
@section('content')
<div class="container">
    <div class="clearfix">
        <div class="float-left" style="margin-bottom: 20px">
            <h3>Security check</h3>
            <p>The security check groups credentials that share the same password together. Consider changing the passwords for one or several credentials in each group to make sure that you use an unique password for each application/site.</p>
        </div>
    </div>
    @if (count($data) > 0)
    @foreach ($data as $group)
    <div class="card mb-4 border-secondary">
        <h5 class="card-header">Password group</h5>
        <div class="card-body">
            <div class="card-columns">
                @foreach ($group as $row)
                    @include('partials._credentialCard', ['credential' => (object)$row, 'showGroupName' => false])
                @endforeach
            </div>
        </div>
    </div>
    @endforeach
    @else
    <div class="alert alert-success" role="alert">
        <strong>No credentials found!</strong> This means that your credentials all have different passwords.
    </div>
    @endif
    @include('partials._modalshowCred')
</div>
@endsection
