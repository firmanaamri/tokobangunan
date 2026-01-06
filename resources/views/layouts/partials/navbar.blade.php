<nav class="bg-white border-b shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                {{-- Toggle Sidebar Button --}}
                <button @click="sidebarOpen = !sidebarOpen" 
                        class="mr-4 p-2 rounded-md text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition-all duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    {{-- Ikon hamburger saat terbuka, close saat tertutup --}}
                    <svg x-show="!sidebarOpen" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg x-show="sidebarOpen" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
                    <div class="text-2xl font-bold text-emerald-600">JP</div>
                    <div class="hidden sm:block">
                        <span class="text-lg font-semibold text-slate-800">Toko Bangunan</span>
                        <div class="text-xs text-slate-400">Inventori & Penjualan</div>
                    </div>
                </a>

                <div class="hidden md:ml-8 md:flex md:space-x-2 md:items-center">
                    <a href="{{ route('dashboard') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:bg-slate-100' }}">Dashboard</a>
                    <a href="{{ route('stokbarang') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('stokbarang') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:bg-slate-100' }}">Stok</a>
                    <a href="{{ route('barang') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('barang') || request()->routeIs('barang.*') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:bg-slate-100' }}">Barang</a>
                    <a href="{{ route('barang-masuk.index') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('barang-masuk.*') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:bg-slate-100' }}">Barang Masuk</a>
                    <a href="{{ route('barang-keluar.index') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('barang-keluar.*') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:bg-slate-100' }}">Barang Keluar</a>
                    @if (Route::has('kategori.index'))
                        <a href="{{ route('kategori.index') }}" class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('kategori.*') ? 'bg-emerald-50 text-emerald-700' : 'text-slate-600 hover:bg-slate-100' }}">Kategori</a>
                    @endif
                </div>
            </div>

            <div class="flex items-center space-x-4">
                <form action="{{ url()->current() }}" method="GET" class="hidden sm:block">
                    <div class="relative">
                        <input type="search" name="search" placeholder="Cari produk..." class="block w-full pl-3 pr-10 py-2 border rounded-md text-sm text-slate-600 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-emerald-300"/>
                        <button type="submit" class="absolute right-0 top-0 mt-1 mr-1 text-slate-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.9 14.32a8 8 0 111.414-1.414l4.387 4.386a1 1 0 01-1.414 1.415l-4.387-4.387zM8 14a6 6 0 100-12 6 6 0 000 12z" clip-rule="evenodd" /></svg>
                        </button>
                    </div>
                </form>

                <div class="flex items-center">
                    <a href="{{ route('profile.edit') }}" class="text-sm text-slate-600 hover:text-slate-800">Profil</a>
                </div>
            </div>
        </div>
    </div>
</nav>
