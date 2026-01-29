<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Bangunan Jaya Prana </title>
    
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-[#E8F9FF] text-stone-800 antialiased selection:bg-sky-100 selection:text-sky-900">

    <header x-data="{ mobileMenuOpen: false }" 
            class="fixed w-full top-0 z-50 transition-all duration-300 backdrop-blur-md bg-[#E8F9FF]/90 border-b border-sky-100 shadow-sm">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" aria-label="Top">
            <div class="w-full py-4 flex items-center justify-between border-b border-sky-100 lg:border-none">
                <div class="flex items-center">
                    <a href="#" class="flex items-center gap-3 group">
                        <div class="bg-white p-1.5 rounded-lg shadow-lg group-hover:scale-105 transition-transform">
                            <img src="{{ asset('favicon.png') }}" alt="JP Logo" class="h-6 w-6 brightness-200 grayscale-0">
                        </div>
                        <span class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-emerald-800 to-sky-700">
                            Jaya Prana
                        </span>
                    </a>
                </div>
                
                <div class="hidden ml-10 space-x-8 lg:flex items-center">
                    <a href="#" class="text-sm font-medium text-stone-600 hover:text-sky-700 transition-colors">Beranda</a>
                    <a href="#kategori" class="text-sm font-medium text-stone-600 hover:text-sky-700 transition-colors">Kategori</a>
                    <a href="#produk" class="text-sm font-medium text-stone-600 hover:text-sky-700 transition-colors">Produk</a>
                    <a href="#kontak" class="text-sm font-medium text-stone-600 hover:text-sky-700 transition-colors">Kontak</a>

                    <div class="border-l border-stone-300 h-6 mx-2"></div>

                    @if (auth()->check())
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center px-5 py-2 border border-transparent text-sm font-medium rounded-full text-white bg-sky-600 hover:bg-sky-700 shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-5 py-2 border border-sky-600 text-sm font-medium rounded-full text-sky-700 bg-white hover:bg-sky-50 transition-colors">
                            Masuk
                        </a>
                    @endif
                </div>

                <div class="ml-10 space-x-4 flex items-center lg:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="bg-white p-2 rounded-md text-stone-400 hover:text-stone-500 hover:bg-sky-50 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-sky-500 shadow-sm">
                        <span class="sr-only">Open menu</span>
                        <svg x-show="!mobileMenuOpen" class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg x-show="mobileMenuOpen" x-cloak class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <div x-show="mobileMenuOpen" x-cloak x-transition:enter="duration-200 ease-out" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="duration-100 ease-in" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2" class="py-4 flex flex-col space-y-2 lg:hidden border-b border-stone-200 bg-[#E8F9FF]">
                <a href="#" class="block px-3 py-2 rounded-md text-base font-medium text-stone-700 hover:text-sky-700 hover:bg-sky-100">Beranda</a>
                <a href="#produk" class="block px-3 py-2 rounded-md text-base font-medium text-stone-700 hover:text-sky-700 hover:bg-sky-100">Produk</a>
                <a href="#kontak" class="block px-3 py-2 rounded-md text-base font-medium text-stone-700 hover:text-sky-700 hover:bg-sky-100">Kontak</a>
                <div class="border-t border-stone-200 pt-4 mt-2">
                    @if (auth()->check())
                        <a href="{{ route('dashboard') }}" class="block px-3 py-2 text-center rounded-lg font-bold bg-sky-600 text-white shadow-sm">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="block px-3 py-2 text-center rounded-lg font-bold bg-white border border-sky-600 text-sky-700">Masuk</a>
                    @endif
                </div>
            </div>
        </nav>
    </header>

    <main>
        <div class="relative pt-24 pb-16 sm:pb-24 overflow-hidden">
            <div class="absolute inset-0">
                <img class="w-full h-full object-cover" src="https://images.unsplash.com/photo-1504307651254-35680f356dfd?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" alt="Construction Site">
                <div class="absolute inset-0 bg-gradient-to-r from-slate-900/95 via-slate-900/85 to-sky-900/40 mix-blend-multiply"></div>
            </div>

            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10 sm:mt-24">
                <div class="md:w-2/3 lg:w-1/2">
                    {{-- <div class="inline-flex items-center px-3 py-1 rounded-full border border-sky-400/30 bg-sky-400/10 text-sky-200 text-xs font-semibold tracking-wide uppercase mb-6 backdrop-blur-sm">
                        <span class="w-2 h-2 bg-sky-400 rounded-full mr-2 animate-pulse"></span>
                        Partner Konstruksi Terpercaya
                    </div> --}}
                    <h1 class="text-4xl sm:text-5xl lg:text-5xl font-extrabold tracking-tight text-white leading-[1.15]">
                        <span class="whitespace-nowrap">Sistem Manajemen Inventori</span> <br>
                        TB. <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-400 to-emerald-300">Jaya Prana</span>
                    </h1>
                    <p class="mt-6 text-lg sm:text-xl text-slate-300 max-w-2xl leading-relaxed">
                        Menyediakan material bangunan berkualitas tinggi dan alat pertukangan lengkap. 
                    </p>
                    <div class="mt-10 flex flex-col sm:flex-row gap-4">
                        <a href="#produk" class="inline-flex justify-center items-center px-8 py-4 border border-transparent text-base font-bold rounded-xl text-white bg-sky-500 hover:bg-sky-400 transition-all shadow-[0_0_20px_rgba(14,165,233,0.3)] hover:shadow-[0_0_30px_rgba(14,165,233,0.5)]">
                            Lihat Katalog
                            <svg class="ml-2 -mr-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </a>
                        <a href="#kontak" class="inline-flex justify-center items-center px-8 py-4 border border-white/20 backdrop-blur-md text-base font-bold rounded-xl text-white hover:bg-white/10 transition-all">
                            Hubungi Kami
                        </a>
                    </div>
                </div>
            </div>
        </div>

       {{-- STATS SECTION DENGAN ANIMASI --}}

        <div class="bg-white relative z-10 -mt-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 rounded-2xl shadow-xl border border-sky-100">
            {{-- 
                Kita definisikan data langsung di parent div agar script lebih rapi 
                Atau gunakan fungsi global sederhana.
            --}}
            <div class="grid grid-cols-2 md:grid-cols-4 divide-x divide-sky-100">
                
                {{-- Item 1: 50+ --}}
                <div class="p-6 text-center group hover:bg-[#E8F9FF] transition rounded-l-2xl">
                    {{-- x-init="start()" artinya animasi langsung jalan saat halaman dimuat --}}
                    <div x-data="counter(50, 1000)" x-init="start()">
                        <p class="text-3xl font-bold text-sky-700 group-hover:scale-110 transition-transform cursor-default">
                            <span x-text="current">0</span>+
                        </p>
                        <p class="text-sm font-medium text-stone-500 mt-1">Jenis Material</p>
                    </div>
                </div>

                {{-- Item 2: 500+ --}}
                <div class="p-6 text-center group hover:bg-[#E8F9FF] transition">
                    <div x-data="counter(500, 2000)" x-init="start()">
                        <p class="text-3xl font-bold text-sky-700 group-hover:scale-110 transition-transform cursor-default">
                            <span x-text="current">0</span>+
                        </p>
                        <p class="text-sm font-medium text-stone-500 mt-1">Pelanggan Puas</p>
                    </div>
                </div>

                {{-- Item 3: 100% --}}
                <div class="p-6 text-center group hover:bg-[#E8F9FF] transition">
                    <div x-data="counter(100, 1500)" x-init="start()">
                        <p class="text-3xl font-bold text-sky-700 group-hover:scale-110 transition-transform cursor-default">
                            <span x-text="current">0</span>%
                        </p>
                        <p class="text-sm font-medium text-stone-500 mt-1">Kualitas Asli</p>
                    </div>
                </div>

                {{-- Item 4: Teks Statis --}}
                <div class="p-6 text-center group hover:bg-[#E8F9FF] transition rounded-r-2xl">
                    <p class="text-3xl font-bold text-sky-700 group-hover:scale-110 transition-transform cursor-default">
                        Kami
                    </p>
                    <p class="text-sm font-medium text-stone-500 mt-1">Buka Setiap Hari</p>
                </div>
            </div>
        </div>

        
        <script>
            function counter(target, duration = 2000) {
                return {
                    current: 0,
                    target: target,
                    time: duration,
                    start() {
                        let startTimestamp = null;
                        const step = (timestamp) => {
                            if (!startTimestamp) startTimestamp = timestamp;
                            const progress = Math.min((timestamp - startTimestamp) / this.time, 1);
                            
                            // Hitung angka progress
                            this.current = Math.floor(progress * this.target);
                            
                            // Lanjut animasi jika belum selesai
                            if (progress < 1) {
                                window.requestAnimationFrame(step);
                            }
                        };
                        window.requestAnimationFrame(step);
                    }
                };
            }
        </script>

        <section id="kategori" class="py-20 bg-[#E8F9FF]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-base text-sky-600 font-semibold tracking-wide uppercase">Koleksi Lengkap</h2>
                    <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-stone-900 sm:text-4xl">Kategori Pilihan</p>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                    @foreach ($categories as $category)
                    <a href="{{ route('landing', ['q' => $category->nama_kategori]) }}" class="group relative bg-white rounded-xl shadow-sm hover:shadow-md transition-all p-6 text-center border border-sky-100 hover:border-sky-400 overflow-hidden">
                        <div class="absolute inset-0 bg-[#D0F0FD] opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <div class="relative z-10">
                            <div class="mx-auto h-12 w-12 text-sky-600 mb-3 bg-sky-50 rounded-full flex items-center justify-center group-hover:bg-white group-hover:scale-110 transition-all">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            </div>
                            <h3 class="text-sm font-bold text-stone-900 group-hover:text-sky-800 transition-colors">{{ $category->nama_kategori }}</h3>
                            <p class="text-xs text-stone-500 mt-1">{{ $category->barang_count }} Item</p>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
        </section>

        <section id="produk" class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row justify-between items-end md:items-center mb-10 gap-6">
                    <div>
                        <h2 class="text-3xl font-extrabold text-stone-900">Katalog Produk</h2>
                        <p class="mt-2 text-stone-600">Temukan material terbaik untuk kebutuhan proyek Anda.</p>
                    </div>

                    <form method="GET" action="{{ route('landing') }}" class="w-full md:w-1/3 relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-stone-400 group-focus-within:text-sky-600 transition-colors" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input type="text" name="q" value="{{ $query }}" 
                               class="block w-full pl-10 pr-3 py-3 border border-stone-200 rounded-xl leading-5 bg-[#E8F9FF] text-stone-900 placeholder-stone-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-sky-500 focus:border-sky-500 sm:text-sm transition-all shadow-sm" 
                               placeholder="Cari nama barang atau kategori...">
                        @if($query)
                            <a href="{{ route('landing') }}" class="absolute inset-y-0 right-0 pr-3 flex items-center text-stone-400 hover:text-red-500">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </a>
                        @endif
                    </form>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    @forelse ($products as $product)
                    <div class="group relative bg-white border border-stone-200 rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col h-full overflow-hidden">
                        
                        <div class="aspect-w-1 aspect-h-1 w-full overflow-hidden bg-stone-100 relative h-48 flex items-center justify-center group-hover:opacity-95 transition-opacity">
                            <div class="absolute top-3 right-3 z-10">
                                @if($product->stok_saat_ini <= 0)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-stone-200 text-stone-600 border border-stone-300 shadow-sm">Habis</span>
                                @elseif($product->stok_saat_ini < 10)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700 border border-red-200 shadow-sm animate-pulse">Menipis</span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-sky-100 text-sky-700 border border-sky-200 shadow-sm">Ready Stok</span>
                                @endif
                            </div>
                            
                            @if($product->gambar)
                                <img src="{{ asset('storage/' . $product->gambar) }}" 
                                     alt="{{ $product->nama_barang }}" 
                                     class="w-full h-full object-cover object-center group-hover:scale-110 transition-transform duration-500">
                            @else
                                <svg class="h-20 w-20 text-stone-300 group-hover:scale-110 transition-transform duration-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            @endif
                        </div>

                        <div class="flex-1 p-5 flex flex-col justify-between">
                            <div>
                                <h3 class="text-lg font-bold text-stone-900 group-hover:text-sky-700 transition-colors line-clamp-2">
                                    {{ $product->nama_barang }}
                                </h3>
                                <div class="flex items-center gap-2 mt-2">
                                    <span class="inline-block w-2 h-2 rounded-full bg-sky-400"></span>
                                    <p class="text-sm text-stone-500">{{ $product->kategori->nama_kategori ?? 'Umum' }}</p>
                                </div>
                            </div>
                            
                            <div class="mt-4 pt-4 border-t border-stone-100">
                                <div class="flex items-end justify-between">
                                    <div>
                                        <p class="text-xs text-stone-400 uppercase tracking-wide font-semibold mb-1">Harga Satuan</p>
                                        <p class="text-lg font-extrabold text-sky-700">Rp {{ number_format($product->harga ?? 0, 0, ',', '.') }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs text-stone-400 uppercase tracking-wide font-semibold mb-1">Stok Tersedia</p>
                                        <p class="text-lg font-bold text-stone-800">
                                            {{ $product->stok_saat_ini }} 
                                            <span class="text-xs font-normal text-stone-500">{{ $product->satuan }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full text-center py-20 bg-stone-50 rounded-3xl border border-dashed border-stone-300">
                        <div class="inline-block p-4 rounded-full bg-white mb-4 shadow-sm">
                            <svg class="h-10 w-10 text-stone-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-stone-900">Produk tidak ditemukan</h3>
                        <p class="text-stone-500 mt-1">Kami tidak dapat menemukan produk dengan kata kunci "<strong>{{ $query }}</strong>".</p>
                        @if($query)
                            <a href="{{ route('landing') }}" class="mt-6 inline-flex items-center px-4 py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700 font-medium transition-colors shadow-sm">
                                Reset Pencarian
                            </a>
                        @endif
                    </div>
                    @endforelse
                </div>

                @if($products instanceof \Illuminate\Contracts\Pagination\Paginator)
                <div class="mt-12">
                    {{ $products->links() }}
                </div>
                @endif
            </div>
        </section>

        <section id="kontak" class="relative py-20 bg-slate-900 overflow-hidden">
            <div class="absolute top-0 left-0 -ml-20 -mt-20 w-80 h-80 rounded-full bg-sky-600/10 blur-3xl"></div>
            <div class="absolute bottom-0 right-0 -mr-20 -mb-20 w-80 h-80 rounded-full bg-emerald-600/10 blur-3xl"></div>

            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-3xl font-extrabold text-white sm:text-4xl">
                    Siap Memulai Proyek Anda?
                </h2>
                <p class="mt-4 text-xl text-slate-400 max-w-2xl mx-auto">
                    Kunjungi toko kami untuk mendapatkan penawaran harga terbaik.
                </p>

                <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="bg-white/5 backdrop-blur-sm p-6 rounded-2xl border border-white/10 hover:border-sky-500/50 transition-colors">
                        <div class="w-12 h-12 bg-sky-600 rounded-lg flex items-center justify-center mx-auto mb-4 text-white">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <h3 class="text-lg font-semibold text-white">Kunjungi Kami</h3>
                        <p class="text-slate-400 mt-2 text-sm">Jl. Pelita No. 2, Sarwodadi,<br>Comal, Pemalang</p>
                    </div>
                    
                    <div class="bg-white/5 backdrop-blur-sm p-6 rounded-2xl border border-white/10 hover:border-sky-500/50 transition-colors">
                        <div class="w-12 h-12 bg-sky-600 rounded-lg flex items-center justify-center mx-auto mb-4 text-white">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        </div>
                        <h3 class="text-lg font-semibold text-white">Hubungi Sales</h3>
                        <p class="text-slate-400 mt-2 text-sm">Telepon: (021) 1234-5678<br>WA: 0812-3456-7890</p>
                    </div>

                    <div class="bg-white/5 backdrop-blur-sm p-6 rounded-2xl border border-white/10 hover:border-sky-500/50 transition-colors">
                        <div class="w-12 h-12 bg-sky-600 rounded-lg flex items-center justify-center mx-auto mb-4 text-white">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <h3 class="text-lg font-semibold text-white">Email Support</h3>
                        <p class="text-slate-400 mt-2 text-sm">info@jayaprana.com<br>sales@jayaprana.com</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="bg-slate-900 border-t border-slate-800 pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                <div class="col-span-1 md:col-span-1">
                    <a href="#" class="flex items-center gap-2 mb-4">
                        <img src="{{ asset('favicon.png') }}" alt="JP Logo" class="h-8 w-8 brightness-200 grayscale-0">
                        <span class="text-xl font-bold text-white">Jaya Prana</span>
                    </a>
                    <p class="text-slate-400 text-sm leading-relaxed">
                        Distributor material bangunan terpercaya dengan komitmen kualitas dan pelayanan terbaik untuk kepuasan pelanggan.
                    </p>
                </div>
                
                <div>
                    <h4 class="text-white font-semibold mb-4">Navigasi</h4>
                    <ul class="space-y-2 text-sm text-slate-400">
                        <li><a href="#" class="hover:text-sky-400 transition">Beranda</a></li>
                        <li><a href="#produk" class="hover:text-sky-400 transition">Katalog Produk</a></li>
                        <li><a href="#tentang" class="hover:text-sky-400 transition">Tentang Kami</a></li>
                        <li><a href="#kontak" class="hover:text-sky-400 transition">Hubungi Kami</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-white font-semibold mb-4">Kategori Populer</h4>
                    <ul class="space-y-2 text-sm text-slate-400">
                        @foreach($categories->take(4) as $cat)
                            <li><a href="{{ route('landing', ['q' => $cat->nama_kategori]) }}" class="hover:text-sky-400 transition">{{ $cat->nama_kategori }}</a></li>
                        @endforeach
                    </ul>
                </div>

                <div>
                    <h4 class="text-white font-semibold mb-4">Jam Operasional</h4>
                    <ul class="space-y-2 text-sm text-slate-400">
                        <li class="flex justify-between"><span>Senin - Ahad</span> <span>08:00 - 17:00</span></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-slate-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-slate-500 text-sm text-center md:text-left">
                    &copy; {{ date('Y') }} Jaya Prana Building Store. All rights reserved.
                </p>
                <div class="flex space-x-6 text-sm text-slate-500">
                    <a href="#" class="hover:text-sky-400 transition">Privacy Policy</a>
                    <a href="#" class="hover:text-sky-400 transition">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>