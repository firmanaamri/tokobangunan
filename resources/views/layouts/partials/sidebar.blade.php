@php
    $user = Auth::user();
    $userName = $user ? $user->name : 'Administrator';
    $initial = strtoupper(substr($userName, 0, 1));

    $gradients = [
        'from-blue-400 to-indigo-500',
        'from-sky-400 to-blue-500',
        'from-cyan-400 to-teal-500',
        'from-teal-400 to-emerald-500',
        'from-indigo-400 to-purple-500',
    ];
    
    $hash = 0;
    for ($i = 0; $i < strlen($userName); $i++) {
        $hash = ord($userName[$i]) + (($hash << 5) - $hash);
    }
    $colorIndex = abs($hash) % count($gradients);
    $userColorClass = $gradients[$colorIndex];
@endphp

<style>
    .sidebar-collapsed .sidebar-label { display: none; }
    .sidebar-collapsed .section-title { display: none; }
    .sidebar-collapsed .profile-info { display: none; }
    .sidebar-collapsed .logout-text { display: none; }
    .sidebar-collapsed nav a { justify-content: center; }
    
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #3674B5; border-radius: 10px; }
    .custom-scrollbar:hover::-webkit-scrollbar-thumb { background-color: #4a85c2; } 
</style>

{{-- CONTAINER UTAMA --}}
<div 
    x-cloak
    :class="sidebarOpen ? 'w-64 translate-x-0' : 'w-20 sidebar-collapsed -translate-x-full md:translate-x-0'"
    class="bg-[#5409DA] text-blue-50 p-3 transition-all duration-500 ease-in-out shadow-xl border-r border-white/10 overflow-hidden
           fixed inset-y-0 left-0 z-50 
           flex flex-col justify-between md:relative md:z-auto"
>
    
    {{-- BAGIAN 1: HEADER & LOGO --}}
    <div class="flex items-center justify-center mb-6 p-4 shrink-0 border-b border-white/10 pb-6">
        <a href="{{ route('dashboard') }}" class="flex items-center justify-center min-w-0 group transition-transform hover:scale-105 duration-300">
             <img src="{{ asset('favicon.png') }}" alt="JP" class="h-10 w-auto object-contain drop-shadow-[0_0_5px_rgba(255,255,255,0.3)]">
             <span class="ml-3 text-xl font-extrabold text-white tracking-wide sidebar-label transition-colors duration-300">Jaya Prana</span>
        </a>
    </div>

    {{-- BAGIAN 2: MENU --}}
    <div class="flex flex-col flex-grow overflow-y-auto custom-scrollbar pr-1">
        <div class="space-y-6">
            <nav class="space-y-1.5">
                
                {{-- Dashboard: AMBER (KUNING EMAS) --}}
                <a href="{{ route('dashboard') }}" 
                   class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-300 group font-medium mb-1
                          {{ request()->routeIs('dashboard') 
                             ? 'bg-[#2a5a8d] text-white shadow-md ring-1 ring-white/20' 
                             : 'text-blue-100 hover:bg-white/10 hover:text-white hover:shadow-sm' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0 transition-colors {{ request()->routeIs('dashboard') ? 'text-white' : 'text-blue-200 group-hover:text-amber-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    <span class="whitespace-nowrap sidebar-label">Dashboard</span>
                </a>

                <div class="pt-5 pb-2">
                    <h3 class="text-[10px] uppercase tracking-wider text-blue-200/70 px-3 font-bold section-title">Master Data</h3>
                </div>
                
                {{-- Barang: FUCHSIA (UNGU CERAH) --}}
                <a href="{{ route('stokbarang') }}" 
                   class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-300 group font-medium mb-1
                          {{ request()->routeIs('stokbarang') || request()->routeIs('barang.*') 
                             ? 'bg-[#2a5a8d] text-white shadow-md ring-1 ring-white/20' 
                             : 'text-blue-100 hover:bg-white/10 hover:text-white hover:shadow-sm' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0 transition-colors {{ request()->routeIs('stokbarang') ? 'text-white' : 'text-blue-200 group-hover:text-fuchsia-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    <span class="whitespace-nowrap sidebar-label">Barang</span>
                </a>

                {{-- Supplier: ROSE (PINK) --}}
                @can('isAdmin')
                <a href="{{ route('suppliers.index') }}" 
                   class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-300 group font-medium mb-1
                          {{ request()->routeIs('suppliers.*') 
                             ? 'bg-[#2a5a8d] text-white shadow-md ring-1 ring-white/20' 
                             : 'text-blue-100 hover:bg-white/10 hover:text-white hover:shadow-sm' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0 transition-colors {{ request()->routeIs('suppliers.*') ? 'text-white' : 'text-blue-200 group-hover:text-rose-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <span class="whitespace-nowrap sidebar-label">Supplier</span>
                </a>
                @endcan

                <div class="pt-5 pb-2">
                    <h3 class="text-[10px] uppercase tracking-wider text-blue-200/70 px-3 font-bold section-title">Transaksi</h3>
                </div>

                {{-- Pengajuan: CYAN (BIRU LANGIT) --}}
                <a href="{{ route('purchase-requests.index') }}" 
                   class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-300 group font-medium mb-1
                          {{ request()->routeIs('purchase-requests.*') 
                             ? 'bg-[#2a5a8d] text-white shadow-md ring-1 ring-white/20' 
                             : 'text-blue-100 hover:bg-white/10 hover:text-white hover:shadow-sm' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0 transition-colors {{ request()->routeIs('purchase-requests.*') ? 'text-white' : 'text-blue-200 group-hover:text-cyan-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="whitespace-nowrap sidebar-label">Pengajuan Beli</span>
                </a>

                {{-- Penerimaan (GRN) untuk Staff: tampilkan juga bagi staff --}}
                @if(Auth::user() && Auth::user()->isStaff())
                    @php $readyToReceiveStaff = \App\Models\Purchase::where('status_pembayaran', 'lunas')->whereDoesntHave('goodsReceipt')->count(); @endphp
                    <a href="{{ route('goods-receipts.index') }}" 
                       class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-300 group font-medium mb-1
                              {{ request()->routeIs('goods-receipts.*') 
                                 ? 'bg-[#2a5a8d] text-white shadow-md ring-1 ring-white/20' 
                                 : 'text-blue-100 hover:bg-white/10 hover:text-white hover:shadow-sm' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0 transition-colors {{ request()->routeIs('goods-receipts.*') ? 'text-white' : 'text-blue-200 group-hover:text-teal-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                        <span class="whitespace-nowrap sidebar-label flex-1">Penerimaan</span>
                        @if($readyToReceiveStaff > 0)
                            <span class="sidebar-label ml-2 px-2 py-0.5 text-[10px] font-bold text-[#2a5a8d] bg-teal-300 rounded-full">{{ $readyToReceiveStaff }}</span>
                        @endif
                    </a>
                @endif

                @can('isAdmin')
                {{-- Approval: RED/ROSE (MERAH MUDA - Urgent) --}}
                <a href="{{ route('purchase-approvals.index') }}" 
                   class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-300 group relative font-medium mb-1
                          {{ request()->routeIs('purchase-approvals.*') 
                             ? 'bg-[#2a5a8d] text-white shadow-md ring-1 ring-white/20' 
                             : 'text-blue-100 hover:bg-white/10 hover:text-white hover:shadow-sm' }}">
                    <div class="relative">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0 transition-colors {{ request()->routeIs('purchase-approvals.*') ? 'text-white' : 'text-blue-200 group-hover:text-red-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        @php $pendingCount = \App\Models\PurchaseRequest::where('status', 'pending')->count(); @endphp
                        @if($pendingCount > 0)
                            <span class="absolute -top-1 -right-1 flex h-2.5 w-2.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500 border border-[#2a5a8d]"></span>
                            </span>
                        @endif
                    </div>
                    <span class="whitespace-nowrap sidebar-label flex-1">Approval</span>
                    @if($pendingCount > 0)
                        <span class="sidebar-label ml-2 px-2 py-0.5 text-[10px] font-bold text-white bg-red-500 rounded-full shadow-sm">{{ $pendingCount }}</span>
                    @endif
                </a>

                {{-- Pembelian: ORANGE (ORANYE) --}}
                <a href="{{ route('purchases.index') }}" 
                   class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-300 group font-medium mb-1
                          {{ request()->routeIs('purchases.*') 
                             ? 'bg-[#2a5a8d] text-white shadow-md ring-1 ring-white/20' 
                             : 'text-blue-100 hover:bg-white/10 hover:text-white hover:shadow-sm' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0 transition-colors {{ request()->routeIs('purchases.*') ? 'text-white' : 'text-blue-200 group-hover:text-orange-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <span class="whitespace-nowrap sidebar-label">Pembelian (PO)</span>
                </a>

                {{-- Penerimaan: TEAL (HIJAU KEBIRUAN) --}}
                <a href="{{ route('goods-receipts.index') }}" 
                   class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-300 group font-medium mb-1
                          {{ request()->routeIs('goods-receipts.*') 
                             ? 'bg-[#2a5a8d] text-white shadow-md ring-1 ring-white/20' 
                             : 'text-blue-100 hover:bg-white/10 hover:text-white hover:shadow-sm' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0 transition-colors {{ request()->routeIs('goods-receipts.*') ? 'text-white' : 'text-blue-200 group-hover:text-teal-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                    </svg>
                    <span class="whitespace-nowrap sidebar-label flex-1">Penerimaan</span>
                    @php $readyToReceive = \App\Models\Purchase::where('status_pembayaran', 'lunas')->whereDoesntHave('goodsReceipt')->count(); @endphp
                    @if($readyToReceive > 0)
                        <span class="sidebar-label ml-2 px-2 py-0.5 text-[10px] font-bold text-[#2a5a8d] bg-teal-300 rounded-full">{{ $readyToReceive }}</span>
                    @endif
                </a>
                @endcan
                
                {{-- Penjualan: LIME (HIJAU MUDA) --}}
                <a href="{{ route('daily-sales.index') }}" 
                   class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-300 group font-medium mb-1
                          {{ request()->routeIs('daily-sales.*') || request()->routeIs('cart.*') 
                             ? 'bg-[#2a5a8d] text-white shadow-md ring-1 ring-white/20' 
                             : 'text-blue-100 hover:bg-white/10 hover:text-white hover:shadow-sm' }}">
                    <div class="relative">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0 transition-colors {{ request()->routeIs('daily-sales.*') ? 'text-white' : 'text-blue-200 group-hover:text-lime-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M3 6h18M3 14h18M3 18h18" />
                        </svg>
                        @if(session('cart') && count(session('cart')) > 0)
                            <span class="absolute -top-1.5 -right-1.5 flex h-2.5 w-2.5">
                                 <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500 border border-[#2a5a8d]"></span>
                            </span>
                        @endif
                    </div>
                    <span class="whitespace-nowrap sidebar-label">Catat Penjualan</span>
                </a>

                {{-- SECTION: ADMIN --}}
                @can('isAdmin')
                <div class="pt-5 pb-2">
                    <h3 class="text-[10px] uppercase tracking-wider text-blue-200/70 px-3 font-bold section-title">Laporan & Admin</h3>
                </div>

                {{-- Barang Masuk: EMERALD (HIJAU) --}}
                <a href="{{ route('barang-masuk.index') }}" 
                   class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-300 group font-medium mb-1
                          {{ request()->routeIs('barang-masuk.*') 
                             ? 'bg-[#2a5a8d] text-white shadow-md ring-1 ring-white/20' 
                             : 'text-blue-100 hover:bg-white/10 hover:text-white hover:shadow-sm' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0 transition-colors {{ request()->routeIs('barang-masuk.*') ? 'text-white' : 'text-blue-200 group-hover:text-emerald-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                    </svg>
                    <span class="whitespace-nowrap sidebar-label">Laporan Masuk</span>
                </a>

                {{-- Barang Keluar: RED (MERAH TERANG) --}}
                <a href="{{ route('barang-keluar.index') }}" 
                   class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-300 group font-medium mb-1
                          {{ request()->routeIs('barang-keluar.*') 
                             ? 'bg-[#2a5a8d] text-white shadow-md ring-1 ring-white/20' 
                             : 'text-blue-100 hover:bg-white/10 hover:text-white hover:shadow-sm' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0 transition-colors {{ request()->routeIs('barang-keluar.*') ? 'text-white' : 'text-blue-200 group-hover:text-red-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 16l-4-4m0 0l4-4m-4 4h18" />
                    </svg>
                    <span class="whitespace-nowrap sidebar-label">Laporan Keluar</span>
                </a>
                
                {{-- Karantina: YELLOW (KUNING) --}}
                @php $pendingQuarantine = \App\Models\Quarantine::where('status','pending')->count(); @endphp
                <a href="{{ route('admin.quarantines.index') }}"
                     class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-300 group font-medium mb-1
                            {{ request()->routeIs('admin.quarantines.*') 
                               ? 'bg-[#2a5a8d] text-white shadow-md ring-1 ring-white/20' 
                               : 'text-blue-100 hover:bg-white/10 hover:text-white hover:shadow-sm' }}">
                    <div class="relative">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0 transition-colors {{ request()->routeIs('admin.quarantines.*') ? 'text-white' : 'text-blue-200 group-hover:text-yellow-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01" />
                        </svg>
                        @if($pendingQuarantine > 0)
                            <span class="absolute -top-1 -right-1 flex h-2.5 w-2.5">
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500 border border-[#2a5a8d]"></span>
                            </span>
                        @endif
                    </div>
                    <span class="whitespace-nowrap sidebar-label flex-1">Karantina</span>
                    @if($pendingQuarantine > 0)
                        <span class="sidebar-label ml-2 px-2 py-0.5 text-[10px] font-bold text-white bg-red-500 rounded-full">{{ $pendingQuarantine }}</span>
                    @endif
                </a>
                
                {{-- User: INDIGO --}}
                <a href="{{ route('admin.users.index') }}" 
                   class="flex items-center space-x-3 px-3 py-2.5 rounded-xl transition-all duration-300 group font-medium mb-1
                          {{ request()->routeIs('admin.users.*') 
                             ? 'bg-[#2a5a8d] text-white shadow-md ring-1 ring-white/20' 
                             : 'text-blue-100 hover:bg-white/10 hover:text-white hover:shadow-sm' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0 transition-colors {{ request()->routeIs('admin.users.*') ? 'text-white' : 'text-blue-200 group-hover:text-indigo-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 12a4 4 0 100-8 4 4 0 000 8z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-4-4h-1" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 20H4v-2a4 4 0 014-4h1" />
                    </svg>
                    <span class="whitespace-nowrap sidebar-label">Manajemen User</span>
                </a>

                @endcan
                            
            </nav>
        </div>
    </div>
    
    {{-- BAGIAN 3: FOOTER / PROFILE --}}
    <div class="mt-auto border-t border-white/10 pt-4 pb-2 bg-transparent shrink-0">
        {{-- Profile --}}
        <div class="flex items-center mb-4 px-2 py-2 rounded-lg hover:bg-white/10 transition-all duration-300 group cursor-default">
            
            <div class="h-10 w-10 rounded-full bg-gradient-to-br {{ $userColorClass }} text-white flex items-center justify-center font-bold text-lg shadow-lg shrink-0 ring-2 ring-white/30 group-hover:ring-white transition-all">
                {{ $initial }}
            </div>
            
            <div class="ml-3 min-w-0 profile-info">
                <p class="text-sm font-bold text-white truncate">{{ $userName }}</p>
                <div class="flex items-center gap-1.5">
                    <span class="h-2 w-2 rounded-full bg-emerald-400 animate-pulse shadow-[0_0_8px_rgba(16,185,129,0.8)]"></span>
                    <p class="text-xs text-blue-200 font-medium">Online</p>
                </div>
            </div>
        </div>

        {{-- Logout --}}
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <a href="{{ route('logout') }}"
               onclick="event.preventDefault(); confirmLogout(this.closest('form'));"
               class="flex items-center w-full space-x-3 px-3 py-2.5 rounded-xl text-blue-100 hover:bg-white/10 hover:text-white transition-all duration-300 font-medium group cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0 transition-transform duration-300 group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span class="whitespace-nowrap logout-text">Keluar Aplikasi</span>
            </a>
        </form>
    </div>
</div>