@extends('layouts.master')
@section('content')
    <div class="container mx-auto">
        <h3 class="text-2xl mb-4">Change password</h3>
        @if (session()->has('success'))
            <pwdsafe-alert theme="success" classes="mb-4">
                {{ session()->get('success') }}
            </pwdsafe-alert>
        @endif
        <form method="post" action="{{ route('changepassword') }}" class="max-w-lg">
            @if ($errors->any())
                <pwdsafe-alert theme="danger" classes="my-4">
                @foreach($errors->all() as $error)
                    {{ $error }}
                @endforeach
                </pwdsafe-alert>
            @endif
            @csrf
            <div class="mb-4">
                <div class="mb-2">
                    <pwdsafe-label class="mb-1" for="oldpwd">Old password</pwdsafe-label>
                    <pwdsafe-input type="password" name="oldpwd" id="oldpwd" autocomplete="off" required></pwdsafe-input>
                </div>
                <div class="mb-2">
                    <pwdsafe-label class="mb-1" for="password">New password</pwdsafe-label>
                    <pwdsafe-input type="password" name="password" id="password" autocomplete="off" required></pwdsafe-input>
                </div>
                <div class="mb-2">
                    <pwdsafe-label class="mb-1" for="password_confirmation">Verify</pwdsafe-label>
                    <pwdsafe-input type="password" name="password_confirmation" id="password_confirmation" autocomplete="off" required></pwdsafe-input>
                </div>
            </div>
            <div class="flex justify-between">
                <pwdsafe-button type="submit">Change password</pwdsafe-button>
            </div>
        </form>
    </div>
@endsection()
