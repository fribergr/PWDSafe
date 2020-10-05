<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <link href="/components/fontawesome/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <title>PWDSafe</title>
</head>
<body class="bg-gray-100">
<div id="app">
    @auth
        <nav class="bg-white shadow">
            <div class="mx-auto px-2 sm:px-4 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex px-2 lg:px-0">
                        <div class="flex-shrink-0 flex items-center">
                            <a href="/">PWDSafe</a>
                        </div>
                        <div class="hidden lg:ml-6 lg:flex">
                        <span
                            class="inline-flex items-center px-1 pt-1 border-b-2 {{ (request()->is('groups/*')) ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium leading-5 focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out ml-8">
                        <dropdown-menu>
                            <template v-slot:trigger>
                                Groups
                            </template>
                            <dropdown-link href="{{ route('groupCreate') }}">Create group</dropdown-link>
                            <div class="my-1 border-b"></div>
                            @foreach (auth()->user()->groups()->withCount('credentials')->get() as $group)
                                <dropdown-link href="{{ route('group', $group->id) }}">
                                    <span class="flex items-center justify-between">
                                        @if ($group->id === auth()->user()->primarygroup)
                                            Private
                                        @else
                                            {{ $group->name }}
                                        @endif
                                    <span
                                        class="bg-indigo-300  text-indigo-800 p-1 ml-2 rounded-md">{{ $group->credentials_count }}</span>
                                    </span>
                                </dropdown-link>
                            @endforeach
                        </dropdown-menu>
                            </span>
                            <a href="{{ route('securitycheck') }}"
                               class="inline-flex items-center px-1 pt-1 border-b-2 {{ (request()->is('securitycheck')) ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium leading-5 focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out ml-8">
                                Security check
                            </a>
                        </div>
                    </div>
                    <div class="flex-1 flex items-center justify-center px-2 lg:ml-6 lg:justify-end">
                        <div class="max-w-lg w-full lg:max-w-xs">
                            <label for="search" class="sr-only">Search</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                              d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                              clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <form method="post" action="{{ route('search') }}">
                                    @csrf
                                    <input id="search" name="search"
                                           class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:border-indigo-500 focus:shadow-outline-blue sm:text-sm transition duration-150 ease-in-out"
                                           placeholder="Search" type="search">
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center lg:hidden">
                        <!-- Mobile menu button -->
                        <button
                            class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
                            aria-label="Main menu" aria-expanded="false">
                            <!-- Icon when menu is closed. -->
                            <!--
                              Heroicon name: menu

                              Menu open: "hidden", Menu closed: "block"
                            -->
                            <svg class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                            <!-- Icon when menu is open. -->
                            <!--
                              Heroicon name: x

                              Menu open: "block", Menu closed: "hidden"
                            -->
                            <svg class="hidden h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <div class="hidden lg:ml-4 lg:flex lg:items-center">

                        <!-- Profile dropdown -->
                        <dropdown-menu>
                            <template v-slot:trigger="{ open }">
                                <span class="flex items-center hover:text-gray-700 focus:text-gray-700"
                                      :class="{'text-gray-500': !open, 'text-gray-700': open}">
                                    <span class="far fa-user mr-1"></span> {{ auth()->user()->email }}
                                </span>
                            </template>
                            @if (!config('ldap.enabled'))
                                <dropdown-link href="{{ route('changepassword') }}">Change password</dropdown-link>
                                <div class="my-1 border-b"></div>
                            @endif
                            <form method="post" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="w-full text-left block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                    Logout
                                </button>
                            </form>
                        </dropdown-menu>
                    </div>
                </div>
            </div>

            <!--
              Mobile menu, toggle classes based on menu state.

              Menu open: "block", Menu closed: "hidden"
            -->
            <div class="hidden lg:hidden">
                <div class="pt-2 pb-3">
                    <a href="#"
                       class="block pl-3 pr-4 py-2 border-l-4 border-indigo-500 text-base font-medium text-indigo-700 bg-indigo-50 focus:outline-none focus:text-indigo-800 focus:bg-indigo-100 focus:border-indigo-700 transition duration-150 ease-in-out">Dashboard</a>
                    <a href="#"
                       class="mt-1 block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out">Team</a>
                    <a href="#"
                       class="mt-1 block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out">Projects</a>
                    <a href="#"
                       class="mt-1 block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out">Calendar</a>
                </div>
                <div class="pt-4 pb-3 border-t border-gray-200">
                    <div class="flex items-center px-4">
                        <div class="flex-shrink-0">
                            <img class="h-10 w-10 rounded-full"
                                 src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                                 alt="">
                        </div>
                        <div class="ml-3">
                            <div class="text-base font-medium leading-6 text-gray-800">Tom Cook</div>
                            <div class="text-sm font-medium leading-5 text-gray-500">tom@example.com</div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="#"
                           class="block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100 focus:outline-none focus:text-gray-800 focus:bg-gray-100 transition duration-150 ease-in-out">
                            Your Profile
                        </a>
                        <a href="#"
                           class="mt-1 block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100 focus:outline-none focus:text-gray-800 focus:bg-gray-100 transition duration-150 ease-in-out">
                            Settings
                        </a>
                        <a href="#"
                           class="mt-1 block px-4 py-2 text-base font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-100 focus:outline-none focus:text-gray-800 focus:bg-gray-100 transition duration-150 ease-in-out">
                            Sign out
                        </a>
                    </div>
                </div>
            </div>
        </nav>
    @endauth
    <div class="container mx-auto mt-4 px-4 sm:px-8">
        @yield('content')
    </div>
</div>
<script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
