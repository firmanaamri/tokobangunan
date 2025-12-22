{{-- <aside 
    @click.away="sidebarOpen = false"
    x-cloak
    class="fixed inset-y-0 left-0 z-50 w-64 bg-gray-800 text-gray-300 p-6 space-y-6 
           transform -translate-x-full transition-transform duration-300 
           md:relative md:translate-x-0 md:flex md:flex-col md:justify-between"
    :class="{ 'translate-x-0': sidebarOpen }">
    
    <div>
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 mb-10">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M8 7v8a2 2 0 002 2h4a2 2 0 002-2V7a2 2 0 00-2-2h-4a2 2 0 00-2 2z" />
              <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h.01M7 10h.01M17 10h.01M21 10h.01M3 14h.01M7 14h.01M17 14h.01M21 14h.01" />
            </svg>
            <span class="text-2xl font-bold text-white">Inventori</span>
        </a>
        
        <nav class="space-y-2">
            <h3 class="text-xs uppercase text-gray-500 pt-2 pb-2 px-3 font-semibold">Menu Utama</h3>
            
            <a href="{{ route('dashboard') }}" 
               class="flex items-center space-x-3 px-3 py-2.5 rounded-md hover:bg-gray-700 hover:text-white {{ request()->routeIs('dashboard') ? 'bg-gray-700 text-white' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6-4h.01M12 12h.01M15 12h.01M12 9h.01M15 9h.01M9 9h.01" /></svg>
                <span>Dashboard</span>
            </a>
            
            <a href="#" class="flex items-center space-x-3 px-3 py-2.5 rounded-md hover:bg-gray-700 hover:text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" /></svg>
                <span>Stok Barang</span>
            </a>
            <a href="#" class="flex items-center space-x-3 px-3 py-2.5 rounded-md hover:bg-gray-700 hover:text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V7M16 3h-1a4 4 0 00-8 0H6a2 2 0 00-2 2v2h16V5a2 2 0 00-2-2z" /></svg>
                <span>Supplier</span>
            </a>
            
            <a href="#" class="flex items-center space-x-3 px-3 py-2.5 rounded-md hover:bg-gray-700 hover:text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-6a2 2 0 012-2h6M9 17H5a2 2 0 01-2-2v-6a2 2 0 012-2h4m4 10h4a2 2 0 002-2v-6a2 2 0 00-2-2h-4m0 10v-6a2 2 0 012-2h6" /></svg>
                <span>Pelaporan</span>
            </a>\
            
            
            </nav>
    </div>

    <div class="border-t border-gray-700 pt-4">
        <div class="flex items-center mb-4 px-3">
            <img class="h-10 w-10 rounded-full object-cover" src="https://via.placeholder.com/100" alt="Foto Profil">
            <div class="ml-3">
                <p class="text-sm font-medium text-white">{{ Auth::user()->name ?? 'Admin' }}</p>
                <p class="text-xs text-gray-400">Online</p>
            </div>
        </div>
        
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <a href="{{ route('logout') }}"
               onclick="event.preventDefault(); this.closest('form').submit();"
               class="flex items-center space-x-3 px-3 py-2.5 rounded-md text-red-400 hover:bg-red-500 hover:text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                <span>Keluar (Logout)</span>
            </a>
        </form>
    </div>
</aside> --}}

<aside 
    @click.away="sidebarOpen = false"
    x-cloak
    x-data="{ sidebarOpen: true }" {{-- Tambahkan state Alpine.js --}}
    :class="sidebarOpen ? 'w-64' : 'w-20'" {{-- Atur lebar berdasarkan state --}}
    class="fixed inset-y-0 left-0 z-50 bg-gradient-to-b from-slate-900 via-slate-800 to-slate-900 text-gray-300 p-3 transition-all duration-500 ease-in-out shadow-2xl border-r border-slate-700 overflow-hidden
           md:relative md:translate-x-0 md:flex md:flex-col md:justify-between"
>
    
    <div class="flex items-center space-x-3 mb-10 p-4 rounded-lg hover:bg-white/5 transition-colors duration-300">
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 min-w-0" :class="sidebarOpen ? '' : 'justify-center w-full'">
             <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-emerald-400 flex-shrink-0 transition-transform duration-500" fill="currentColor" viewBox="0 0 24 24">
                 <path d="M3 13h2v8H3zM17 6h2v15h-2zM10 9h2v12h-2z" />
             </svg>
             {{-- Teks Logo hanya muncul saat sidebar terbuka --}}
             <span class="text-xl font-bold bg-gradient-to-r from-emerald-400 to-cyan-400 bg-clip-text text-transparent whitespace-nowrap transition-all duration-500" :style="sidebarOpen ? 'opacity: 1; transform: translateX(0)' : 'opacity: 0; transform: translateX(-10px)'">Jaya Prana</span>
        </a>
        
        <button @click="sidebarOpen = !sidebarOpen" 
                class="hidden md:flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-white/10 transition-all duration-300 flex-shrink-0" 
                :class="sidebarOpen ? 'ml-auto' : 'ml-0'">
            {{-- Ikon panah sesuai state --}}
            <svg x-show="sidebarOpen" x-transition:enter="transition-transform duration-300" x-transition:leave="transition-transform duration-300" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" /></svg>
            <svg x-show="!sidebarOpen" x-transition:enter="transition-transform duration-300" x-transition:leave="transition-transform duration-300" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" /></svg>
        </button>
    </div>

    <div class="flex flex-col flex-grow overflow-y-auto scrollbar-thin scrollbar-thumb-slate-700 scrollbar-track-slate-800">
        <div class="space-y-6">
            <nav class="space-y-1">
                {{-- Judul Menu --}}
                <h3 class="text-xs uppercase tracking-wider text-slate-500 pt-2 pb-3 px-4 font-bold transition-all duration-500 overflow-hidden" :style="sidebarOpen ? 'opacity: 1; max-height: 100px' : 'opacity: 0; max-height: 0'">Menu Utama</h3>
                
                {{-- Item Navigasi Dashboard --}}
                <a href="{{ route('dashboard') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-300 ease-in-out group
                          {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-emerald-500/20 to-cyan-500/20 text-emerald-300 shadow-lg shadow-emerald-500/10 border-l-2 border-emerald-500' : 'text-gray-400 hover:bg-white/5 hover:text-gray-200' }}"
                   :class="sidebarOpen ? 'justify-start' : 'justify-center space-x-0'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0 transition-transform duration-300 group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6-4h.01M12 12h.01M15 12h.01M12 9h.01M15 9h.01M9 9h.01" /></svg>
                    <span class="font-medium whitespace-nowrap transition-all duration-500 overflow-hidden" :style="sidebarOpen ? 'opacity: 1; transform: translateX(0); max-width: 500px' : 'opacity: 0; transform: translateX(-10px); max-width: 0'">Dashboard</span>
                </a>
                
                {{-- Item Navigasi Stok Barang --}}
                <a href="{{ route('stokbarang') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-300 ease-in-out group
                          {{ request()->routeIs('stokbarang') ? 'bg-gradient-to-r from-emerald-500/20 to-cyan-500/20 text-emerald-300 shadow-lg shadow-emerald-500/10 border-l-2 border-emerald-500' : 'text-gray-400 hover:bg-white/5 hover:text-gray-200' }}"
                   :class="sidebarOpen ? 'justify-start' : 'justify-center space-x-0'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0 transition-transform duration-300 group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" /></svg>
                    <span class="font-medium whitespace-nowrap transition-all duration-500 overflow-hidden" :style="sidebarOpen ? 'opacity: 1; transform: translateX(0); max-width: 500px' : 'opacity: 0; transform: translateX(-10px); max-width: 0'">Stok Barang</span>
                </a>

                {{-- Item Navigasi Barang Masuk --}}
                <a href="{{ route('barang-masuk.index') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-300 ease-in-out group
                          {{ request()->routeIs('barang-masuk.*') ? 'bg-gradient-to-r from-emerald-500/20 to-cyan-500/20 text-emerald-300 shadow-lg shadow-emerald-500/10 border-l-2 border-emerald-500' : 'text-gray-400 hover:bg-white/5 hover:text-gray-200' }}"
                   :class="sidebarOpen ? 'justify-start' : 'justify-center space-x-0'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0 transition-transform duration-300 group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                    <span class="font-medium whitespace-nowrap transition-all duration-500 overflow-hidden" :style="sidebarOpen ? 'opacity: 1; transform: translateX(0); max-width: 500px' : 'opacity: 0; transform: translateX(-10px); max-width: 0'">Barang Masuk</span>
                </a>

                {{-- Item Navigasi Barang Keluar --}}
                <a href="{{ route('barang-keluar.index') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-300 ease-in-out group
                          {{ request()->routeIs('barang-keluar.*') ? 'bg-gradient-to-r from-emerald-500/20 to-cyan-500/20 text-emerald-300 shadow-lg shadow-emerald-500/10 border-l-2 border-emerald-500' : 'text-gray-400 hover:bg-white/5 hover:text-gray-200' }}"
                   :class="sidebarOpen ? 'justify-start' : 'justify-center space-x-0'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0 transition-transform duration-300 group-hover:scale-110 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 3H6a2 2 0 00-2 2v14a2 2 0 002 2h4" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12l-6-6v4H9v4h6v4l6-6z" />
                    </svg>
                    <span class="font-medium whitespace-nowrap transition-all duration-500 overflow-hidden" :style="sidebarOpen ? 'opacity: 1; transform: translateX(0); max-width: 500px' : 'opacity: 0; transform: translateX(-10px); max-width: 0'">Barang Keluar</span>
                </a>
                
                {{-- Item Navigasi Supplier --}}
                <a href="#" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-400 hover:bg-white/5 hover:text-gray-200 transition-all duration-300 ease-in-out group"
                   :class="sidebarOpen ? 'justify-start' : 'justify-center space-x-0'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0 transition-transform duration-300 group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V7M16 3h-1a4 4 0 00-8 0H6a2 2 0 00-2 2v2h16V5a2 2 0 00-2-2z" /></svg>
                    <span class="font-medium whitespace-nowrap transition-all duration-500 overflow-hidden" :style="sidebarOpen ? 'opacity: 1; transform: translateX(0); max-width: 500px' : 'opacity: 0; transform: translateX(-10px); max-width: 0'">Supplier</span>
                </a>
                
                {{-- Item Navigasi Pelaporan --}}
                <a href="#" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-400 hover:bg-white/5 hover:text-gray-200 transition-all duration-300 ease-in-out group"
                   :class="sidebarOpen ? 'justify-start' : 'justify-center space-x-0'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0 transition-transform duration-300 group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-6a2 2 0 012-2h6M9 17H5a2 2 0 01-2-2v-6a2 2 0 012-2h4m4 10h4a2 2 0 002-2v-6a2 2 0 00-2-2h-4m0 10v-6a2 2 0 012-2h6" /></svg>
                    <span class="font-medium whitespace-nowrap transition-all duration-500 overflow-hidden" :style="sidebarOpen ? 'opacity: 1; transform: translateX(0); max-width: 500px' : 'opacity: 0; transform: translateX(-10px); max-width: 0'">Pelaporan</span>
                </a>
                
                {{-- Item Navigasi Penjualan (Sales) --}}
                <a href="{{ route('sales.index') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-300 ease-in-out group
                          {{ request()->routeIs('sales.*') ? 'bg-gradient-to-r from-emerald-500/20 to-cyan-500/20 text-emerald-300 shadow-lg shadow-emerald-500/10 border-l-2 border-emerald-500' : 'text-gray-400 hover:bg-white/5 hover:text-gray-200' }}"
                   :class="sidebarOpen ? 'justify-start' : 'justify-center space-x-0'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0 transition-transform duration-300 group-hover:scale-110 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M3 6h18M3 14h18M3 18h18" /></svg>
                    <span class="font-medium whitespace-nowrap transition-all duration-500 overflow-hidden" :style="sidebarOpen ? 'opacity: 1; transform: translateX(0); max-width: 500px' : 'opacity: 0; transform: translateX(-10px); max-width: 0'">Penjualan</span>
                </a>

                {{-- Item Navigasi Keranjang (Cart) --}}
                <a href="{{ route('cart.index') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-300 ease-in-out group
                          {{ request()->routeIs('cart.*') ? 'bg-gradient-to-r from-emerald-500/20 to-cyan-500/20 text-emerald-300 shadow-lg shadow-emerald-500/10 border-l-2 border-emerald-500' : 'text-gray-400 hover:bg-white/5 hover:text-gray-200' }}"
                   :class="sidebarOpen ? 'justify-start' : 'justify-center space-x-0'">
                    <div class="relative">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0 transition-transform duration-300 group-hover:scale-110 text-cyan-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4"/></svg>
                        @if(session('cart') && count(session('cart')) > 0)
                            <span class="absolute -top-2 -right-2 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-500 rounded-full">{{ count(session('cart')) }}</span>
                        @endif
                    </div>
                    <span class="font-medium whitespace-nowrap transition-all duration-500 overflow-hidden" :style="sidebarOpen ? 'opacity: 1; transform: translateX(0); max-width: 500px' : 'opacity: 0; transform: translateX(-10px); max-width: 0'">Keranjang</span>
                </a>
                                
            </nav>
        </div>
        
        <div class="mt-auto border-t border-slate-700 pt-4 pb-2">
            <div class="flex items-center mb-4 px-4 py-2 rounded-lg hover:bg-white/5 transition-colors duration-300" :class="sidebarOpen ? 'justify-start' : 'justify-center'">
                <img class="h-10 w-10 rounded-lg object-cover flex-shrink-0 ring-2 ring-emerald-500/50 transition-all duration-300" src="profile.jpg" alt="Foto Profil">
                <div class="ml-3 transition-all duration-500 overflow-hidden min-w-0" :style="sidebarOpen ? 'opacity: 1; transform: translateX(0); max-width: 500px' : 'opacity: 0; transform: translateX(-10px); max-width: 0'">
                    <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->name ?? 'Admin' }}</p>
                    <p class="text-xs text-emerald-400 font-medium">Online</p>
                </div>
            </div>
            
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <a href="{{ route('logout') }}"
                   onclick="event.preventDefault(); this.closest('form').submit();"
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg text-red-400 hover:bg-red-500/10 hover:text-red-300 transition-all duration-300 ease-in-out font-medium group"
                   :class="sidebarOpen ? 'justify-start' : 'justify-center space-x-0'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0 transition-transform duration-300 group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                    <span class="whitespace-nowrap transition-all duration-500 overflow-hidden" :style="sidebarOpen ? 'opacity: 1; transform: translateX(0); max-width: 500px' : 'opacity: 0; transform: translateX(-10px); max-width: 0'">Keluar</span>
                </a>
            </form>
        </div>
    </div>
</aside>