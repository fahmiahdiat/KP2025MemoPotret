<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Memo Potret') }}</title>

    <!-- CDN Flatpickr -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    @stack('styles')
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <!-- Navigation -->
        <nav class="bg-white border-b border-gray-100 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <!-- Logo Section -->
                    <div class="flex items-center">
                        <!-- Logo -->
                        <div class="shrink-0 flex items-center">
                            <a href="{{ route('home') }}" class="flex items-center">
                                <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                                <span class="ml-2 text-xl font-bold text-gray-800">Memo Potret</span>
                            </a>
                        </div>

                        <!-- Navigation Links -->
                        @auth
                            @if(auth()->user()->isOwner())
                                <!-- Navigation Links for OWNER -->
                                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                    <x-nav-link href="{{ route('owner.dashboard') }}"
                                        :active="request()->routeIs('owner.dashboard')">
                                        {{ __('Dashboard') }}
                                    </x-nav-link>
                                    <x-nav-link href="{{ route('owner.reports.index') }}"
                                        :active="request()->routeIs('owner.reports.*')">
                                        {{ __('Laporan') }}
                                    </x-nav-link>
                                    <x-nav-link href="{{ route('owner.settings.index') }}"
                                        :active="request()->routeIs('owner.settings.*')">
                                        {{ __('Pengaturan') }}
                                    </x-nav-link>
                                    <x-nav-link href="{{ route('owner.users.index') }}"
                                        :active="request()->routeIs('owner.users.*')">
                                        {{ __('Kelola User') }}
                                    </x-nav-link>
                                </div>
                            @elseif(auth()->user()->isAdmin())
                                <!-- Navigation Links for ADMIN -->
                                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                    <x-nav-link href="{{ route('admin.dashboard') }}"
                                        :active="request()->routeIs('admin.dashboard')">
                                        {{ __('Dashboard') }}
                                    </x-nav-link>
                                    <x-nav-link href="{{ route('admin.bookings.index') }}"
                                        :active="request()->routeIs('admin.bookings.*')">
                                        {{ __('Booking') }}
                                    </x-nav-link>
                                    <x-nav-link href="{{ route('admin.packages.index') }}"
                                        :active="request()->routeIs('admin.packages.*')">
                                        {{ __('Paket') }}
                                    </x-nav-link>
                                    <x-nav-link href="{{ route('admin.calendar') }}"
                                        :active="request()->routeIs('admin.calendar')">
                                        {{ __('Kalender') }}
                                    </x-nav-link>
                                </div>
                            @elseif(auth()->user()->isClient())
                                <!-- Navigation Links for CLIENT -->
                                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                    <x-nav-link href="{{ route('client.dashboard') }}"
                                        :active="request()->routeIs('client.dashboard')">
                                        {{ __('Dashboard') }}
                                    </x-nav-link>
                                    <x-nav-link href="{{ route('client.bookings.create') }}"
                                        :active="request()->routeIs('client.bookings.create')">
                                        {{ __('Booking Baru') }}
                                    </x-nav-link>
                                    <x-nav-link href="{{ route('packages') }}" :active="request()->routeIs('packages')">
                                        {{ __('Paket') }}
                                    </x-nav-link>
                                </div>
                            @endif
                        @else
                            <!-- Navigation Links for GUEST -->
                            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                <x-nav-link href="{{ route('home') }}" :active="request()->routeIs('home')">
                                    {{ __('Beranda') }}
                                </x-nav-link>
                                <x-nav-link href="{{ route('packages') }}" :active="request()->routeIs('packages')">
                                    {{ __('Paket') }}
                                </x-nav-link>
                            </div>
                        @endauth
                    </div>

                    <!-- Right Side Section -->
                    <div class="flex items-center">
                        <!-- Auth Links / User Dropdown -->
                        @auth
                            <!-- User Dropdown for Authenticated Users -->
                            <div class="hidden sm:flex sm:items-center">
                                <x-dropdown align="right" width="48">
                                    <x-slot name="trigger">
                                        <button
                                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition ease-in-out duration-150">
                                            <div class="flex items-center">
                                                <i class="fas fa-user-circle mr-2"></i>
                                                <span>{{ Auth::user()->name }}</span>
                                                <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        </button>
                                    </x-slot>

                                    <x-slot name="content">
                                        @if(auth()->user()->isClient())
                                            <x-dropdown-link href="{{ route('client.dashboard') }}">
                                                <i class="fas fa-tachometer-alt mr-2"></i>
                                                {{ __('Dashboard Client') }}
                                            </x-dropdown-link>
                                        @endif

                                        @if(auth()->user()->isAdmin())
                                            <x-dropdown-link href="{{ route('admin.dashboard') }}">
                                                <i class="fas fa-cog mr-2"></i>
                                                {{ __('Dashboard Admin') }}
                                            </x-dropdown-link>
                                        @endif

                                        @if(auth()->user()->isOwner())
                                            <x-dropdown-link href="{{ route('owner.dashboard') }}">
                                                <i class="fas fa-crown mr-2"></i>
                                                {{ __('Dashboard Owner') }}
                                            </x-dropdown-link>
                                        @endif

                                        <div class="border-t border-gray-200"></div>

                                        <!-- Authentication -->
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <x-dropdown-link href="{{ route('logout') }}" onclick="event.preventDefault();
                                                        this.closest('form').submit();"
                                                class="text-red-600 hover:text-red-700">
                                                <i class="fas fa-sign-out-alt mr-2"></i>
                                                {{ __('Log Out') }}
                                            </x-dropdown-link>
                                        </form>
                                    </x-slot>
                                </x-dropdown>
                            </div>
                        @else
                            <!-- Separate Login and Register Buttons for Guests -->
                            <div class="hidden sm:flex sm:items-center space-x-4">
                                <!-- Login Button -->
                                <a href="{{ route('login') }}"
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition ease-in-out duration-150">
                                    <i class="fas fa-sign-in-alt mr-2"></i>
                                    {{ __('Login') }}
                                </a>

                                <!-- Register Button (same outline style as login) -->
                                <a href="{{ route('register') }}"
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition ease-in-out duration-150">
                                    <i class="fas fa-user-plus mr-2"></i>
                                    {{ __('Daftar') }}
                                </a>
                            </div>
                        @endauth

                        <!-- Hamburger Menu Button for Mobile -->
                        <div class="flex items-center sm:hidden ml-2">
                            <button @click="open = ! open"
                                class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                                        stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16" />
                                    <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden"
                                        stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Mobile Menu -->
                <div :class="{'block': open, 'hidden': ! open}" class="sm:hidden">
                    <div class="pt-2 pb-3 space-y-1">
                        <!-- Navigation Links for Mobile -->
                        @auth
                            @if(auth()->user()->isClient())
                                <x-responsive-nav-link href="{{ route('client.dashboard') }}"
                                    :active="request()->routeIs('client.*')">
                                    <i class="fas fa-tachometer-alt mr-3"></i>
                                    {{ __('Dashboard') }}
                                </x-responsive-nav-link>
                                <x-responsive-nav-link href="{{ route('client.bookings.create') }}"
                                    :active="request()->routeIs('client.bookings.create')">
                                    <i class="fas fa-calendar-plus mr-3"></i>
                                    {{ __('Booking Baru') }}
                                </x-responsive-nav-link>
                                <x-responsive-nav-link href="{{ route('packages') }}" :active="request()->routeIs('packages')">
                                    <i class="fas fa-box mr-3"></i>
                                    {{ __('Paket') }}
                                </x-responsive-nav-link>
                            @endif

                            @if(auth()->user()->isAdmin())
                                <x-responsive-nav-link href="{{ route('admin.dashboard') }}"
                                    :active="request()->routeIs('admin.*')">
                                    <i class="fas fa-cog mr-3"></i>
                                    {{ __('Admin Dashboard') }}
                                </x-responsive-nav-link>
                                <x-responsive-nav-link href="{{ route('admin.bookings.index') }}">
                                    <i class="fas fa-calendar mr-3"></i>
                                    {{ __('Booking') }}
                                </x-responsive-nav-link>
                                <x-responsive-nav-link href="{{ route('admin.packages.index') }}">
                                    <i class="fas fa-boxes mr-3"></i>
                                    {{ __('Paket') }}
                                </x-responsive-nav-link>
                            @endif

                            @if(auth()->user()->isOwner())
                                <x-responsive-nav-link href="{{ route('owner.dashboard') }}"
                                    :active="request()->routeIs('owner.*')">
                                    <i class="fas fa-crown mr-3"></i>
                                    {{ __('Owner Dashboard') }}
                                </x-responsive-nav-link>
                                <x-responsive-nav-link href="{{ route('owner.reports.index') }}">
                                    <i class="fas fa-chart-bar mr-3"></i>
                                    {{ __('Laporan') }}
                                </x-responsive-nav-link>
                            @endif
                        @else
                            <x-responsive-nav-link href="{{ route('home') }}" :active="request()->routeIs('home')">
                                <i class="fas fa-home mr-3"></i>
                                {{ __('Beranda') }}
                            </x-responsive-nav-link>
                            <x-responsive-nav-link href="{{ route('packages') }}" :active="request()->routeIs('packages')">
                                <i class="fas fa-box mr-3"></i>
                                {{ __('Paket') }}
                            </x-responsive-nav-link>
                        @endauth
                    </div>

                    <!-- Auth Section for Mobile -->
                    @auth
                        <div class="pt-4 pb-1 border-t border-gray-200 bg-gray-50">
                            <div class="px-4 py-3">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-user-circle text-2xl text-gray-400"></i>
                                    </div>
                                    <div class="ml-3">
                                        <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                                        <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3 space-y-1">
                                @if(auth()->user()->isClient())
                                    <x-responsive-nav-link href="{{ route('client.dashboard') }}">
                                        <i class="fas fa-tachometer-alt mr-3"></i>
                                        {{ __('Dashboard Client') }}
                                    </x-responsive-nav-link>
                                @endif

                                @if(auth()->user()->isAdmin())
                                    <x-responsive-nav-link href="{{ route('admin.dashboard') }}">
                                        <i class="fas fa-cog mr-3"></i>
                                        {{ __('Dashboard Admin') }}
                                    </x-responsive-nav-link>
                                @endif

                                @if(auth()->user()->isOwner())
                                    <x-responsive-nav-link href="{{ route('owner.dashboard') }}">
                                        <i class="fas fa-crown mr-3"></i>
                                        {{ __('Dashboard Owner') }}
                                    </x-responsive-nav-link>
                                @endif

                                <div class="border-t border-gray-200"></div>

                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-responsive-nav-link href="{{ route('logout') }}" onclick="event.preventDefault();
                                                this.closest('form').submit();" class="text-red-600 hover:text-red-700">
                                        <i class="fas fa-sign-out-alt mr-3"></i>
                                        {{ __('Log Out') }}
                                    </x-responsive-nav-link>
                                </form>
                            </div>
                        </div>
                    @else
                        <!-- Mobile Login/Register Buttons -->
                        <div class="pt-4 pb-3 border-t border-gray-200 bg-gray-50">
                            <div class="px-4 py-3 space-y-3">
                                <!-- Login Button Mobile -->
                                <a href="{{ route('login') }}"
                                    class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition ease-in-out duration-150">
                                    <i class="fas fa-sign-in-alt mr-2"></i>
                                    {{ __('Login') }}
                                </a>

                                <!-- Register Button Mobile (same style as login) -->
                                <a href="{{ route('register') }}"
                                    class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition ease-in-out duration-150">
                                    <i class="fas fa-user-plus mr-2"></i>
                                    {{ __('Daftar') }}
                                </a>
                            </div>
                        </div>
                    @endauth
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>

    <!-- Scripts di bagian bawah body -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>

    @stack('scripts')
</body>

</html>