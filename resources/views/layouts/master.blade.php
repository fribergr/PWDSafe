<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <link href="/components/fontawesome/css/all.min.css" rel="stylesheet">
    <title>PWDSafe</title>
</head>
<body class="mt-20">
@auth
<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
    <a class="navbar-brand" href="/">PWDSafe</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main-menu" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="main-menu">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Groups <span class="caret"></span></a>
                <div class="dropdown-menu" id="grouplist">
                    <a class="dropdown-item" href="{{ route('groupCreate') }}">Create group</a>
                    <div class="dropdown-divider"></div>
                    @foreach (auth()->user()->groups as $group)
                        <a class="dropdown-item d-flex align-items-center justify-content-between" href="{{ route('group', $group->id) }}">
                            @if ($group->id === auth()->user()->primarygroup)
                                Private
                            @else
                                {{ $group->name }}
                            @endif
                            <span class="badge badge-info badge-pill ml-2">{{ $group->credentials->count() }}</span>
                        </a>
                    @endforeach
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('securitycheck') }}">Security check</a>
            </li>
        </ul>
        <ul class="navbar-nav">
            <li class="nav-item">
                <form class="d-inline" id="searchform">
                    <div class="input-group">
                        <input type="text" class="form-control border border-right-0" name="search" placeholder="Search...">
                        <span class="input-group-append">
                            <button class="btn btn-outline-secondary border border-left-0" type="submit" id="searchbtn">
                                <i class="fa fa-search"></i>
                            </button>
                        </span>
                    </div>
                </form>
            </li>
            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="far fa-user"></span> {{ auth()->user()->email }} <span class="caret"></span></a>
                <div class="dropdown-menu dropdown-menu-right">
                    @if (!config('ldap.enabled'))
                        <a class="dropdown-item" href="{{ route('changepassword') }}">Change password</a>
                        <div class="dropdown-divider"></div>
                    @endif
                    <form method="post" action="{{ route('logout') }}">
                        @csrf
                        <button class="dropdown-item" type="submit">Logout</button>
                    </form>
                </div>
            </li>
        </ul>
    </div>
</nav>
@endauth
@yield('content')
<script src="{{ mix('js/app.js') }}"></script>
<script type="text/javascript" src="/components/jquery.popconfirm.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#searchform').submit(function(event) {
            event.preventDefault();
            var searchstring = $('input[name="search"]').val().trim();
            if (searchstring.length !== 0) {
                window.location.href = "/search/" + searchstring;
            }
        });
    });
</script>
<script src="{{ mix('js/pwdsafe.js') }}"></script>
</body>
</html>
