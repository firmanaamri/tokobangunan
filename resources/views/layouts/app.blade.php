{{-- <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Jaya Prana') }}</title>
        <link rel="icon" href="{{ asset('UMS.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html> --}}

<!DOCTYPE html>
<html lang="id" class="h-full bg-gray-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard Inventori' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="h-full">

<div x-data="{ sidebarOpen: false }" class="relative min-h-screen md:flex">

    @include('layouts.partials.sidebar')


    <div x-show="sidebarOpen" x-cloak class="fixed inset-0 z-40 bg-black opacity-50 md:hidden" @click="sidebarOpen = false"></div>

    <div class="flex-1 flex flex-col">
        
        <header class="sticky top-0 z-30 flex items-center justify-between px-6 py-4 bg-white border-b shadow-sm">
            
            <button @click="sidebarOpen = !sidebarOpen" class="md:hidden text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
            </button>
            
            <div class="hidden md:block">
                <h1 class="text-xl font-semibold text-gray-800">
                    {{ $header ?? 'Selamat Datang' }}
                </h1>
            </div>
            
            <div x-data="{ dropdownOpen: false }" class="relative">
                <button @click="dropdownOpen = !dropdownOpen" class="flex items-center space-x-2 text-sm text-gray-700">
                    <span>Halo, {{ Auth::user()->name ?? 'Admin' }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                </button>
                
                <div x-show="dropdownOpen" @click.away="dropdownOpen = false" x-cloak
                    class="absolute right-0 w-48 mt-2 py-2 bg-white rounded-md shadow-xl z-50">
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profil Saya</a>
                    
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="{{ route('logout') }}"
                           onclick="event.preventDefault(); this.closest('form').submit();"
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            Keluar (Logout)
                        </a>
                    </form>
                </div>
            </div>
        </header>

        <main class="flex-1 p-6 md:p-10">
            @include('layouts.partials.notification')
            
            @hasSection('content')
                @yield('content')
            @else
                {{ $slot ?? '' }}
            @endif
        </main>
        
        @includeWhen(View::exists('layouts.partials.footer'), 'layouts.partials.footer')
    </div>
    
</div>

    @stack('scripts')
</body>
</html>