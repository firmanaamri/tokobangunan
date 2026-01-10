<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard - Jaya Prana</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style> [x-cloak] { display: none !important; } </style>
</head>
<body class="bg-gray-900 font-sans antialiased text-gray-300">

    <div class="flex h-screen overflow-hidden" 
         x-data="{ 
            sidebarOpen: false, 
            sidebarCollapsed: false, 
            mdScreen: window.innerWidth >= 768 
         }"
         @resize.window="mdScreen = window.innerWidth >= 768; if (mdScreen) sidebarOpen = false">

        <aside
            @click.away="sidebarOpen = false"
            x-cloak
            :class="{ 
                'w-20': sidebarCollapsed && mdScreen,
                'w-64': !sidebarCollapsed || !mdScreen,
                'transform -translate-x-full': !sidebarOpen && !mdScreen,
                'translate-x-0': sidebarOpen && !mdScreen,
                'fixed inset-y-0 left-0 z-50 bg-gray-800 text-gray-300 p-6 space-y-6 transition-all duration-300 ease-in-out md:relative md:translate-x-0 md:flex md:flex-col md:justify-between'
            }"
        >
            <div :class="{ 'px-1': sidebarCollapsed && mdScreen }">
                <a href="#" class="flex items-center space-x-3 mb-10" @click.prevent="sidebarCollapsed = !sidebarCollapsed">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    <span class="text-2xl font-bold text-white whitespace-nowrap overflow-hidden"
                        x-show="!sidebarCollapsed || !mdScreen"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 w-0"
                        x-transition:enter-end="opacity-100 w-auto">
                        Jaya Prana
                    </span>
                </a>

                <nav class="space-y-2">
                    <h3 class="text-xs uppercase text-gray-500 pt-2 pb-2 px-3 font-semibold whitespace-nowrap overflow-hidden"
                        x-show="!sidebarCollapsed || !mdScreen">
                        Menu Staff
                    </h3>
                    
                    <template x-for="item in [
                        { route: '{{ route('staff.dashboard') }}', icon: 'M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z', name: 'Dashboard' },
                        { route: '#', icon: 'M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4', name: 'Stok Barang' },
                        { route: '#', icon: 'M19 14l-7 7m0 0l-7-7m7 7V3', name: 'Barang Masuk' },
                        { route: '#', icon: 'M5 10l7-7m0 0l7 7m-7-7v18', name: 'Barang Keluar' },
                        { route: '#', icon: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01', name: 'Pengajuan Stok' }
                    ]" :key="item.name">
                        <a :href="item.route"
                           :class="{ 'bg-gray-700 text-white': window.location.href === item.route, 'justify-center': sidebarCollapsed && mdScreen }"
                           class="flex items-center space-x-3 px-3 py-2.5 rounded-md hover:bg-gray-700 hover:text-white relative group transition-colors">
                            
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" :d="item.icon" />
                            </svg>
                            
                            <span class="whitespace-nowrap overflow-hidden" 
                                  x-show="!sidebarCollapsed || !mdScreen"
                                  x-transition:enter="transition ease-out duration-300"
                                  x-transition:enter-start="opacity-0 w-0"
                                  x-transition:enter-end="opacity-100 w-auto">
                                <span x-text="item.name"></span>
                            </span>

                            <div x-show="sidebarCollapsed && mdScreen" x-transition
                                 class="absolute left-full ml-3 px-3 py-2 bg-gray-700 text-white text-xs rounded-md shadow-lg opacity-0 group-hover:opacity-100 transition-opacity z-20 whitespace-nowrap">
                                <span x-text="item.name"></span>
                            </div>
                        </a>
                    </template>
                </nav>
            </div>

            <div class="border-t border-gray-700 pt-4" :class="{ 'px-1': sidebarCollapsed && mdScreen }">
                <div class="flex items-center mb-4 px-3" x-show="!sidebarCollapsed || !mdScreen">
                    <img class="h-9 w-9 rounded-full object-cover" src="https://ui-avatars.com/api/?name=Staff+Toko&background=random" alt="Avatar">
                    <div class="ml-3 overflow-hidden">
                        <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name ?? 'Staff Toko' }}</p>
                        <p class="text-xs text-gray-500">Staff Gudang</p>
                    </div>
                </div>
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();"
                       :class="{ 'justify-center': sidebarCollapsed && mdScreen }"
                       class="flex items-center space-x-3 px-3 py-2 rounded-md text-red-400 hover:bg-red-500/10 hover:text-red-300 group relative">
                        <svg class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                        <span x-show="!sidebarCollapsed || !mdScreen" class="whitespace-nowrap overflow-hidden transition-all duration-300">Logout</span>
                    </a>
                </form>
            </div>
        </aside>

        <div class="flex-1 flex flex-col overflow-hidden bg-gray-900">
            
            <header class="flex items-center justify-between p-4 bg-gray-800 md:hidden border-b border-gray-700">
                <button @click="sidebarOpen = true" class="text-gray-300 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                </button>
                <span class="text-lg font-bold text-white">Jaya Prana Staff</span>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto p-6">
                
                <h1 class="text-2xl font-bold text-white mb-6">Dashboard Staff</h1>

                @if(isset($notifications) && count($notifications) > 0)
                <div class="space-y-3 mb-8">
                    @foreach($notifications as $notif)
                        <div x-data="{ show: true }" x-show="show" class="flex items-start p-4 rounded-lg border-l-4 {{ $notif->status == 'approved' ? 'bg-green-900/30 border-green-500' : 'bg-red-900/30 border-red-500' }}">
                            <div class="flex-shrink-0">
                                @if($notif->status == 'approved')
                                    <svg class="h-5 w-5 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                @else
                                    <svg class="h-5 w-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                @endif
                            </div>
                            <div class="ml-3 w-full">
                                <h3 class="text-sm font-medium {{ $notif->status == 'approved' ? 'text-green-300' : 'text-red-300' }}">
                                    Pengajuan {{ $notif->product->name }} {{ ucfirst($notif->status == 'approved' ? 'Disetujui' : 'Ditolak') }}
                                </h3>
                                <p class="text-xs text-gray-400 mt-1">
                                    {{ $notif->quantity }} Pcs ({{ $notif->type == 'in' ? 'Masuk' : 'Keluar' }}) • {{ $notif->updated_at->diffForHumans() }}
                                </p>
                            </div>
                            <button @click="show = false" class="text-gray-500 hover:text-white ml-auto"><svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"/></svg></button>
                        </div>
                    @endforeach
                </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-gray-800 rounded-lg p-5 shadow border border-gray-700 flex items-center justify-between">
                        <div>
                            <p class="text-gray-400 text-sm">Total Jenis Barang</p>
                            <h3 class="text-2xl font-bold text-white">1,240</h3>
                        </div>
                        <div class="p-3 bg-blue-900/50 rounded-full text-blue-400">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                        </div>
                    </div>

                    <div class="bg-gray-800 rounded-lg p-5 shadow border border-gray-700 flex items-center justify-between">
                        <div>
                            <p class="text-gray-400 text-sm">Menunggu Persetujuan</p>
                            <h3 class="text-2xl font-bold text-yellow-400">5</h3>
                        </div>
                        <div class="p-3 bg-yellow-900/50 rounded-full text-yellow-400">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                    </div>
                </div>

                <h3 class="text-lg font-semibold text-gray-200 mb-4">Akses Cepat</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    
                    <a href="#" class="block p-6 bg-gray-800 border border-gray-700 rounded-lg hover:bg-gray-750 hover:border-blue-500 transition group">
                        <div class="flex items-center mb-2">
                            <div class="p-2 bg-green-900/50 text-green-400 rounded-lg mr-3 group-hover:bg-green-600 group-hover:text-white transition">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" /></svg>
                            </div>
                            <h4 class="text-lg font-medium text-white">Barang Masuk</h4>
                        </div>
                        <p class="text-gray-400 text-sm">Catat penerimaan barang dari supplier untuk menambah stok.</p>
                    </a>

                    <a href="#" class="block p-6 bg-gray-800 border border-gray-700 rounded-lg hover:bg-gray-750 hover:border-red-500 transition group">
                        <div class="flex items-center mb-2">
                            <div class="p-2 bg-red-900/50 text-red-400 rounded-lg mr-3 group-hover:bg-red-600 group-hover:text-white transition">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" /></svg>
                            </div>
                            <h4 class="text-lg font-medium text-white">Barang Keluar</h4>
                        </div>
                        <p class="text-gray-400 text-sm">Catat barang terjual atau keluar gudang untuk mengurangi stok.</p>
                    </a>

                    <a href="#" class="block p-6 bg-gray-800 border border-gray-700 rounded-lg hover:bg-gray-750 hover:border-purple-500 transition group">
                        <div class="flex items-center mb-2">
                            <div class="p-2 bg-purple-900/50 text-purple-400 rounded-lg mr-3 group-hover:bg-purple-600 group-hover:text-white transition">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            </div>
                            <h4 class="text-lg font-medium text-white">Pengajuan Baru</h4>
                        </div>
                        <p class="text-gray-400 text-sm">Buat form permintaan persetujuan perubahan stok ke Admin.</p>
                    </a>
                </div>

            </main>
        </div>
    </div>
</body>
</html>