<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,400;1,600&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .font-luxury { font-family: 'Cormorant Garamond', serif; }
        .font-modern { font-family: 'Plus Jakarta Sans', sans-serif; }
        .shadow-dreamy { box-shadow: 0 25px 50px -12px rgba(168, 162, 158, 0.15); }
    </style>
</head>

<body class="font-modern antialiased text-stone-900 bg-[#FDFCF8]">
    <div class="fixed inset-0 -z-10 pointer-events-none overflow-hidden">
        <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-orange-50/60 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3"></div>
        <div class="absolute bottom-0 left-0 w-[600px] h-[600px] bg-rose-50/60 rounded-full blur-3xl translate-y-1/3 -translate-x-1/3"></div>
    </div>

    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 px-4">
        <div class="mb-6 text-center">
            <a href="/" class="flex flex-col items-center group">
                <div class="w-12 h-12 bg-stone-900 rounded-full flex items-center justify-center text-white mb-3 shadow-lg group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                </div>
                <h1 class="font-luxury text-3xl font-bold text-stone-900">Memo Potret</h1>
            </a>
        </div>

        <div class="w-full sm:max-w-md mt-2 px-8 py-10 bg-white shadow-dreamy border border-stone-100 rounded-[2rem] relative overflow-hidden">
            {{ $slot }}
        </div>
        
        <div class="mt-8 text-center text-xs text-stone-400">
            &copy; {{ date('Y') }} Memo Potret Studio. All rights reserved.
        </div>
    </div>
</body>
</html>