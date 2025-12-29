<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Memo Potret') }}</title>

    <!-- CDN Flatpickr -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/l10n/id.min.js"></script>

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
    @auth
        @if(auth()->user()->isOwner() || auth()->user()->isAdmin())
            <!-- SIDEBAR LAYOUT untuk Owner & Admin -->
            <div class="min-h-screen bg-gray-50">
                <!-- Sidebar Desktop -->
                <div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-64 lg:flex-col">
                    <!-- Sidebar component -->
                    <div class="flex grow flex-col gap-y-5 overflow-y-auto border-r border-gray-200 bg-white px-6 pb-4">
                        <div class="flex h-auto shrink-0 items-center justify-center py-4">
                            <a href="{{ auth()->user()->isOwner() ? route('owner.dashboard') : route('admin.dashboard') }}"
                                class="flex flex-col items-center gap-2">
                                <x-application-logo class="block h-8 w-auto fill-current text-gray-800" />

                                @if(auth()->user()->isOwner())
                                    <span
                                        class="text-xs font-bold bg-amber-100 text-amber-800 px-3 py-1 rounded-full border border-amber-200">
                                        Owner
                                    </span>
                                @else
                                    <span
                                        class="text-xs font-bold bg-indigo-100 text-indigo-800 px-3 py-1 rounded-full border border-indigo-200">
                                        Admin
                                    </span>
                                @endif
                            </a>
                        </div>

                        <!-- Navigation -->
                        <nav class="flex flex-1 flex-col">
                            <ul role="list" class="flex flex-1 flex-col gap-y-7">
                                <li>
                                    <ul role="list" class="-mx-2 space-y-1">
                                        @if(auth()->user()->isOwner())
                                            <!-- OWNER MENU -->
                                            <li>
                                                <a href="{{ route('owner.dashboard') }}"
                                                    class="{{ request()->routeIs('owner.dashboard') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700 hover:bg-gray-50 hover:text-indigo-600' }} group flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6">
                                                    <i
                                                        class="fas fa-tachometer-alt {{ request()->routeIs('owner.dashboard') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600' }} mt-0.5 h-5 w-5 shrink-0"></i>
                                                    Dashboard
                                                </a>
                                            </li>
                                            
                                            <!-- REPORTS DROPDOWN -->
                                            <li x-data="{ open: {{ request()->routeIs('owner.reports.*') ? 'true' : 'false' }} }">
                                                <button @click="open = !open"
                                                    class="w-full text-left group flex items-center justify-between rounded-md p-2 text-sm font-semibold leading-6 {{ request()->routeIs('owner.reports.*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700 hover:bg-gray-50 hover:text-indigo-600' }}">
                                                    <div class="flex items-center gap-x-3">
                                                        <i
                                                            class="fas fa-chart-bar {{ request()->routeIs('owner.reports.*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600' }} mt-0.5 h-5 w-5 shrink-0"></i>
                                                        Laporan
                                                    </div>
                                                    <i class="fas fa-chevron-down text-xs transition-transform duration-200" 
                                                       :class="{ 'rotate-180': open }"></i>
                                                </button>
                                                
                                                <!-- Dropdown Reports -->
                                                <ul x-show="open" x-collapse
                                                    class="ml-6 mt-1 space-y-1 border-l border-gray-200 pl-3">
                                                    <li>
                                                        <a href="{{ route('owner.reports.financial') }}"
                                                            class="{{ request()->routeIs('owner.reports.financial') ? 'text-indigo-600 font-medium' : 'text-gray-500 hover:text-indigo-600' }} text-sm flex items-center gap-x-2 px-2 py-1.5">
                                                            <i class="fas fa-money-bill-wave text-xs w-4"></i> Keuangan
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('owner.reports.bookings') }}"
                                                            class="{{ request()->routeIs('owner.reports.bookings') ? 'text-indigo-600 font-medium' : 'text-gray-500 hover:text-indigo-600' }} text-sm flex items-center gap-x-2 px-2 py-1.5">
                                                            <i class="fas fa-calendar-check text-xs w-4"></i> Booking
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('owner.reports.packages') }}"
                                                            class="{{ request()->routeIs('owner.reports.packages') ? 'text-indigo-600 font-medium' : 'text-gray-500 hover:text-indigo-600' }} text-sm flex items-center gap-x-2 px-2 py-1.5">
                                                            <i class="fas fa-box text-xs w-4"></i> Paket
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('owner.reports.clients') }}"
                                                            class="{{ request()->routeIs('owner.reports.clients') ? 'text-indigo-600 font-medium' : 'text-gray-500 hover:text-indigo-600' }} text-sm flex items-center gap-x-2 px-2 py-1.5">
                                                            <i class="fas fa-users text-xs w-4"></i> Klien
                                                        </a>
                                                    </li>
                                                </ul>
                                            </li>
                                            
                                            <li>
                                                <a href="{{ route('owner.users.index') }}"
                                                    class="{{ request()->routeIs('owner.users.*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700 hover:bg-gray-50 hover:text-indigo-600' }} group flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6">
                                                    <i
                                                        class="fas fa-users {{ request()->routeIs('owner.users.*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600' }} mt-0.5 h-5 w-5 shrink-0"></i>
                                                    Kelola User
                                                </a>
                                            </li>
                                        @elseif(auth()->user()->isAdmin())
                                            <!-- ADMIN MENU (tetap sama) -->
                                            <li>
                                                <a href="{{ route('admin.dashboard') }}"
                                                    class="{{ request()->routeIs('admin.dashboard') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700 hover:bg-gray-50 hover:text-indigo-600' }} group flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6">
                                                    <i
                                                        class="fas fa-tachometer-alt {{ request()->routeIs('admin.dashboard') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600' }} mt-0.5 h-5 w-5 shrink-0"></i>
                                                    Dashboard
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('admin.bookings.index') }}"
                                                    class="{{ request()->routeIs('admin.bookings.*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700 hover:bg-gray-50 hover:text-indigo-600' }} group flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6">
                                                    <i
                                                        class="fas fa-calendar-check {{ request()->routeIs('admin.bookings.*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600' }} mt-0.5 h-5 w-5 shrink-0"></i>
                                                    Booking
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('admin.packages.index') }}"
                                                    class="{{ request()->routeIs('admin.packages.*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700 hover:bg-gray-50 hover:text-indigo-600' }} group flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6">
                                                    <i
                                                        class="fas fa-box {{ request()->routeIs('admin.packages.*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600' }} mt-0.5 h-5 w-5 shrink-0"></i>
                                                    Paket
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('admin.calendar') }}"
                                                    class="{{ request()->routeIs('admin.calendar') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700 hover:bg-gray-50 hover:text-indigo-600' }} group flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6">
                                                    <i
                                                        class="fas fa-calendar-alt {{ request()->routeIs('admin.calendar') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600' }} mt-0.5 h-5 w-5 shrink-0"></i>
                                                    Kalender
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </li>

                                <li class="mt-auto">
                                    <!-- User Profile -->
                                    <div
                                        class="flex items-center gap-x-4 px-2 py-3 text-sm font-semibold leading-6 text-gray-900 hover:bg-gray-50 rounded-md">
                                        <div class="flex items-center">
                                            <div
                                                class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold">
                                                {{ substr(Auth::user()->name, 0, 1) }}
                                            </div>
                                            <div class="ml-3">
                                                <div class="font-medium">{{ Auth::user()->name }}</div>
                                                <div class="text-xs text-gray-500">{{ Auth::user()->email }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Logout -->
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                            class="w-full flex items-center gap-x-3 px-2 py-2 text-sm font-semibold leading-6 text-red-600 hover:bg-red-50 rounded-md mt-2">
                                            <i class="fas fa-sign-out-alt mt-0.5 h-5 w-5 shrink-0"></i>
                                            Keluar
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>

                <!-- Main Content Area -->
                <div class="lg:pl-64">
                    <!-- Mobile Top Bar -->
                    <div
                        class="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 border-b border-gray-200 bg-white px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8 lg:hidden">
                        <button type="button" class="-m-2.5 p-2.5 text-gray-700 lg:hidden" onclick="toggleMobileSidebar()">
                            <span class="sr-only">Open sidebar</span>
                            <i class="fas fa-bars h-6 w-6" aria-hidden="true"></i>
                        </button>

                        <!-- Logo Mobile -->
                        <div class="flex flex-1 items-center justify-end gap-x-4">
                            <a href="{{ auth()->user()->isOwner() ? route('owner.dashboard') : route('admin.dashboard') }}"
                                class="flex items-center">
                                <x-application-logo class="block h-8 w-auto fill-current text-gray-800" />
                            </a>
                        </div>

                        <!-- User Menu Mobile -->
                        <div class="flex items-center gap-x-4">
                            <div class="relative">
                                <button type="button" class="-m-1.5 flex items-center p-1.5" onclick="toggleUserMenu()">
                                    <span class="sr-only">Open user menu</span>
                                    <div
                                        class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                </button>

                                <!-- Dropdown Menu Mobile -->
                                <div id="userMenuMobile"
                                    class="hidden absolute right-0 top-full z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                                    <div class="px-4 py-3 border-b">
                                        <div class="font-medium">{{ Auth::user()->name }}</div>
                                        <div class="text-xs text-gray-500">{{ Auth::user()->email }}</div>
                                    </div>
                                    <a href="{{ route('home') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-home mr-2"></i>Beranda
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                            class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                            <i class="fas fa-sign-out-alt mr-2"></i>Keluar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile Sidebar (Hidden by default) -->
                    <div id="mobileSidebar" class="fixed inset-0 z-50 lg:hidden hidden">
                        <!-- Backdrop -->
                        <div class="fixed inset-0 bg-gray-900/80" onclick="toggleMobileSidebar()"></div>

                        <!-- Sidebar Panel -->
                        <div class="relative flex flex-1 flex-col bg-white w-full max-w-xs">
                            <!-- Close button -->
                            <div class="absolute top-0 right-0 -mr-12 pt-4">
                                <button type="button"
                                    class="ml-1 flex h-10 w-10 items-center justify-center rounded-full focus:outline-none focus:ring-2 focus:ring-white"
                                    onclick="toggleMobileSidebar()">
                                    <span class="sr-only">Close sidebar</span>
                                    <i class="fas fa-times h-6 w-6 text-white" aria-hidden="true"></i>
                                </button>
                            </div>

                            <!-- Mobile Sidebar Content -->
                            <div class="flex grow flex-col gap-y-5 overflow-y-auto px-6 pb-4">
                                <!-- Logo -->
                                <div class="flex h-16 shrink-0 items-center">
                                    <a href="{{ auth()->user()->isOwner() ? route('owner.dashboard') : route('admin.dashboard') }}"
                                        class="flex items-center">
                                        <x-application-logo class="block h-8 w-auto fill-current text-gray-800" />
                                    </a>
                                </div>

                                <!-- Navigation Mobile -->
                                <nav class="flex flex-1 flex-col">
                                    <ul role="list" class="flex flex-1 flex-col gap-y-7">
                                        <li>
                                            <ul role="list" class="-mx-2 space-y-1">
                                                @if(auth()->user()->isOwner())
                                                    <!-- OWNER MENU MOBILE -->
                                                    <li>
                                                        <a href="{{ route('owner.dashboard') }}"
                                                            class="{{ request()->routeIs('owner.dashboard') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700 hover:bg-gray-50 hover:text-indigo-600' }} group flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6">
                                                            <i
                                                                class="fas fa-tachometer-alt {{ request()->routeIs('owner.dashboard') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600' }} mt-0.5 h-5 w-5 shrink-0"></i>
                                                            Dashboard
                                                        </a>
                                                    </li>
                                                    
                                                    <!-- REPORTS DROPDOWN MOBILE -->
                                                    <li x-data="{ open: false }">
                                                        <button @click="open = !open"
                                                            class="w-full text-left group flex items-center justify-between rounded-md p-2 text-sm font-semibold leading-6 text-gray-700 hover:bg-gray-50 hover:text-indigo-600">
                                                            <div class="flex items-center gap-x-3">
                                                                <i class="fas fa-chart-bar text-gray-400 group-hover:text-indigo-600 mt-0.5 h-5 w-5 shrink-0"></i>
                                                                Laporan
                                                            </div>
                                                            <i class="fas fa-chevron-down text-xs transition-transform duration-200" 
                                                               :class="{ 'rotate-180': open }"></i>
                                                        </button>
                                                        
                                                        <!-- Dropdown Reports Mobile -->
                                                        <ul x-show="open" x-collapse
                                                            class="ml-6 mt-1 space-y-1 border-l border-gray-200 pl-3">
                                                            <li>
                                                                <a href="{{ route('owner.reports.financial') }}"
                                                                    class="text-sm flex items-center gap-x-2 px-2 py-1.5 text-gray-500 hover:text-indigo-600">
                                                                    <i class="fas fa-money-bill-wave text-xs w-4"></i> Keuangan
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="{{ route('owner.reports.bookings') }}"
                                                                    class="text-sm flex items-center gap-x-2 px-2 py-1.5 text-gray-500 hover:text-indigo-600">
                                                                    <i class="fas fa-calendar-check text-xs w-4"></i> Booking
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="{{ route('owner.reports.packages') }}"
                                                                    class="text-sm flex items-center gap-x-2 px-2 py-1.5 text-gray-500 hover:text-indigo-600">
                                                                    <i class="fas fa-box text-xs w-4"></i> Paket
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="{{ route('owner.reports.clients') }}"
                                                                    class="text-sm flex items-center gap-x-2 px-2 py-1.5 text-gray-500 hover:text-indigo-600">
                                                                    <i class="fas fa-users text-xs w-4"></i> Klien
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </li>
                                                    
                                                    <li>
                                                        <a href="{{ route('owner.users.index') }}"
                                                            class="{{ request()->routeIs('owner.users.*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700 hover:bg-gray-50 hover:text-indigo-600' }} group flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6">
                                                            <i
                                                                class="fas fa-users {{ request()->routeIs('owner.users.*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600' }} mt-0.5 h-5 w-5 shrink-0"></i>
                                                            Kelola User
                                                        </a>
                                                    </li>
                                                @elseif(auth()->user()->isAdmin())
                                                    <!-- ADMIN MENU MOBILE (tetap sama) -->
                                                    <li>
                                                        <a href="{{ route('admin.dashboard') }}"
                                                            class="{{ request()->routeIs('admin.dashboard') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700 hover:bg-gray-50 hover:text-indigo-600' }} group flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6">
                                                            <i
                                                                class="fas fa-tachometer-alt {{ request()->routeIs('admin.dashboard') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600' }} mt-0.5 h-5 w-5 shrink-0"></i>
                                                            Dashboard
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('admin.bookings.index') }}"
                                                            class="{{ request()->routeIs('admin.bookings.*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700 hover:bg-gray-50 hover:text-indigo-600' }} group flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6">
                                                            <i
                                                                class="fas fa-calendar-check {{ request()->routeIs('admin.bookings.*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600' }} mt-0.5 h-5 w-5 shrink-0"></i>
                                                            Booking
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('admin.packages.index') }}"
                                                            class="{{ request()->routeIs('admin.packages.*') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700 hover:bg-gray-50 hover:text-indigo-600' }} group flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6">
                                                            <i
                                                                class="fas fa-box {{ request()->routeIs('admin.packages.*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600' }} mt-0.5 h-5 w-5 shrink-0"></i>
                                                            Paket
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('admin.calendar') }}"
                                                            class="{{ request()->routeIs('admin.calendar') ? 'bg-indigo-50 text-indigo-600' : 'text-gray-700 hover:bg-gray-50 hover:text-indigo-600' }} group flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6">
                                                            <i
                                                                class="fas fa-calendar-alt {{ request()->routeIs('admin.calendar') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600' }} mt-0.5 h-5 w-5 shrink-0"></i>
                                                            Kalender
                                                        </a>
                                                    </li>
                                                @endif
                                            </ul>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>

                    <!-- Page Content -->
                    <main class="py-8">
                        <div class="px-4 sm:px-6 lg:px-8">
                            <!-- Header Slot -->
                            @isset($header)
                                <div class="mb-6">
                                    {{ $header }}
                                </div>
                            @endisset

                            {{ $slot }}
                        </div>
                    </main>
                </div>
            </div>
        @else
            <!-- NAVBAR LAYOUT untuk Client & Guest (tetap sama) -->
            <div class="min-h-screen bg-gray-100">
                <nav x-data="{ open: false }" class="bg-white border-b border-gray-100 shadow-sm">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="flex justify-between h-16">
                            <!-- Logo Section -->
                            <div class="flex items-center">
                                <!-- Logo -->
                                <div class="shrink-0 flex items-center">
                                    <a href="{{ route('home') }}" class="flex items-center">
                                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                                    </a>
                                </div>

                                <!-- Navigation Links -->
                                @if(auth()->user()->isClient())
                                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                        <x-nav-link href="{{ route('client.dashboard') }}"
                                            :active="request()->routeIs('client.dashboard')">
                                            {{ __('Dashboard') }}
                                        </x-nav-link>

                                        <x-nav-link href="{{ route('packages') }}" :active="request()->routeIs('packages')">
                                            {{ __('Paket') }}
                                        </x-nav-link>
                                    </div>
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
                                @endif
                            </div>

                            <!-- Right Side Section -->
                            <div class="flex items-center">
                                @auth
                                    <!-- User Dropdown for CLIENT -->
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
                                                <x-dropdown-link href="{{ route('client.dashboard') }}">
                                                    <i class="fas fa-tachometer-alt mr-2"></i>
                                                    {{ __('Dashboard Client') }}
                                                </x-dropdown-link>

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
                                        <button data-auth-modal data-auth-tab="login"
                                            class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition ease-in-out duration-150">
                                            <i class="fas fa-sign-in-alt mr-2"></i>
                                            {{ __('Login') }}
                                        </button>

                                        <!-- Register Button -->
                                        <button data-auth-modal data-auth-tab="register"
                                            class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition ease-in-out duration-150">
                                            <i class="fas fa-user-plus mr-2"></i>
                                            {{ __('Daftar') }}
                                        </button>
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
                                @auth
                                    @if(auth()->user()->isClient())
                                        <x-responsive-nav-link href="{{ route('client.dashboard') }}"
                                            :active="request()->routeIs('client.*')">
                                            <i class="fas fa-tachometer-alt mr-3"></i>
                                            {{ __('Dashboard') }}
                                        </x-responsive-nav-link>
                                        <x-responsive-nav-link href="{{ route('packages') }}" :active="request()->routeIs('packages')">
                                            <i class="fas fa-box mr-3"></i>
                                            {{ __('Paket') }}
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
                                        <x-responsive-nav-link href="{{ route('client.dashboard') }}">
                                            <i class="fas fa-tachometer-alt mr-3"></i>
                                            {{ __('Dashboard Client') }}
                                        </x-responsive-nav-link>

                                        <div class="border-t border-gray-200"></div>

                                        <!-- Authentication -->
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <x-responsive-nav-link href="{{ route('logout') }}" onclick="event.preventDefault();
                                                                                    this.closest('form').submit();"
                                                class="text-red-600 hover:text-red-700">
                                                <i class="fas fa-sign-out-alt mr-3"></i>
                                                {{ __('Log Out') }}
                                            </x-responsive-nav-link>
                                        </form>
                                    </div>
                                </div>
                            @else
                                <!-- Mobile Login/Register Buttons -->
                                <button data-auth-modal data-auth-tab="login"
                                    class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <i class="fas fa-sign-in-alt mr-2"></i>
                                    {{ __('Login') }}
                                </button>
                                <button data-auth-modal data-auth-tab="register"
                                    class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <i class="fas fa-user-plus mr-2"></i>
                                    {{ __('Daftar') }}
                                </button>
                            @endauth
                        </div>
                    </div>
                </nav>

                <!-- Page Content -->
                <main>
                    <!-- Header Slot untuk Client/Guest -->
                    @isset($header)
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    @endisset

                    {{ $slot }}
                </main>
            </div>
        @endif
    @else
        <!-- NAVBAR LAYOUT untuk Guest (Not logged in) -->
        <div class="min-h-screen bg-gray-100">
            <nav x-data="{ open: false }" class="bg-white border-b border-gray-100 shadow-sm">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center">
                            <div class="shrink-0 flex items-center">
                                <a href="{{ route('home') }}" class="flex items-center">
                                    <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                                </a>
                            </div>
                            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                <x-nav-link href="{{ route('home') }}" :active="request()->routeIs('home')">
                                    {{ __('Beranda') }}
                                </x-nav-link>
                                <x-nav-link href="{{ route('packages') }}" :active="request()->routeIs('packages')">
                                    {{ __('Paket') }}
                                </x-nav-link>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="hidden sm:flex sm:items-center space-x-4">
                                <button data-auth-modal data-auth-tab="login"
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition ease-in-out duration-150">
                                    <i class="fas fa-sign-in-alt mr-2"></i>
                                    {{ __('Login') }}
                                </button>
                                <button data-auth-modal data-auth-tab="register"
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition ease-in-out duration-150">
                                    <i class="fas fa-user-plus mr-2"></i>
                                    {{ __('Daftar') }}
                                </button>
                            </div>
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
                    <div :class="{'block': open, 'hidden': ! open}" class="sm:hidden">
                        <div class="pt-2 pb-3 space-y-1">
                            <x-responsive-nav-link href="{{ route('home') }}" :active="request()->routeIs('home')">
                                <i class="fas fa-home mr-3"></i>
                                {{ __('Beranda') }}
                            </x-responsive-nav-link>
                            <x-responsive-nav-link href="{{ route('packages') }}" :active="request()->routeIs('packages')">
                                <i class="fas fa-box mr-3"></i>
                                {{ __('Paket') }}
                            </x-responsive-nav-link>
                        </div>
                        <button data-auth-modal data-auth-tab="login"
                            class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            {{ __('Login') }}
                        </button>
                        <button data-auth-modal data-auth-tab="register"
                            class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <i class="fas fa-user-plus mr-2"></i>
                            {{ __('Daftar') }}
                        </button>
                    </div>
                </div>
            </nav>
            
            <main>
                <!-- Header Slot untuk Guest -->
                @isset($header)
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                @endisset

                {{ $slot }}
            </main>
        </div>
    @endauth

    <script>
        window.bookedDates = @json($bookedDates ?? []);

        // Fungsi untuk sidebar mobile
        function toggleMobileSidebar() {
            const sidebar = document.getElementById('mobileSidebar');
            if (sidebar.classList.contains('hidden')) {
                sidebar.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            } else {
                sidebar.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        }

        // Fungsi untuk user menu mobile
        function toggleUserMenu() {
            const menu = document.getElementById('userMenuMobile');
            menu.classList.toggle('hidden');
        }

        // Close user menu when clicking outside
        document.addEventListener('click', function (event) {
            const userMenu = document.getElementById('userMenuMobile');
            const userButton = document.querySelector('[onclick="toggleUserMenu()"]');

            if (userMenu && !userMenu.classList.contains('hidden')) {
                if (!userMenu.contains(event.target) && !userButton.contains(event.target)) {
                    userMenu.classList.add('hidden');
                }
            }
        });

        // Fungsi untuk menampilkan modal auth (existing)
        function showAuthModal(activeTab = 'login') {
            const modal = document.getElementById('authModal');
            const backdrop = document.getElementById('authModalBackdrop');

            if (modal && backdrop) {
                modal.classList.remove('hidden');
                backdrop.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');

                // Set active tab
                switchTab(activeTab);
            }
        }

        function hideAuthModal() {
            const modal = document.getElementById('authModal');
            const backdrop = document.getElementById('authModalBackdrop');

            if (modal && backdrop) {
                modal.classList.add('hidden');
                backdrop.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        }

        function switchTab(tab) {
            const loginTab = document.getElementById('loginTab');
            const registerTab = document.getElementById('registerTab');
            const loginContent = document.getElementById('loginContent');
            const registerContent = document.getElementById('registerContent');
            const switchToRegister = document.getElementById('switchToRegister');
            const switchToLogin = document.getElementById('switchToLogin');

            if (tab === 'login') {
                loginTab.classList.add('border-indigo-600', 'text-indigo-600');
                loginTab.classList.remove('border-transparent', 'text-gray-500');
                registerTab.classList.add('border-transparent', 'text-gray-500');
                registerTab.classList.remove('border-indigo-600', 'text-indigo-600');

                loginContent.classList.remove('hidden');
                registerContent.classList.add('hidden');

                switchToRegister.classList.remove('hidden');
                switchToLogin.classList.add('hidden');
            } else {
                registerTab.classList.add('border-indigo-600', 'text-indigo-600');
                registerTab.classList.remove('border-transparent', 'text-gray-500');
                loginTab.classList.add('border-transparent', 'text-gray-500');
                loginTab.classList.remove('border-indigo-600', 'text-indigo-600');

                registerContent.classList.remove('hidden');
                loginContent.classList.add('hidden');

                switchToLogin.classList.remove('hidden');
                switchToRegister.classList.add('hidden');
            }
        }

        // Event Listeners saat DOM siap
        document.addEventListener('DOMContentLoaded', function () {
            // Open modal buttons
            document.querySelectorAll('[data-auth-modal]').forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    const tab = this.getAttribute('data-auth-tab') || 'login';
                    showAuthModal(tab);
                });
            });

            // Close modal
            document.getElementById('closeAuthModal')?.addEventListener('click', hideAuthModal);
            document.getElementById('authModalBackdrop')?.addEventListener('click', hideAuthModal);

            // Switch tabs
            document.getElementById('loginTab')?.addEventListener('click', () => switchTab('login'));
            document.getElementById('registerTab')?.addEventListener('click', () => switchTab('register'));
            document.querySelector('#switchToRegister button')?.addEventListener('click', () => switchTab('register'));
            document.querySelector('#switchToLogin button')?.addEventListener('click', () => switchTab('login'));

            // Escape key to close
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    hideAuthModal();
                    const sidebar = document.getElementById('mobileSidebar');
                    if (sidebar && !sidebar.classList.contains('hidden')) {
                        toggleMobileSidebar();
                    }
                }
            });
        });
    </script>
    @stack('scripts')
    @include('components.auth-modal')
</body>

</html>