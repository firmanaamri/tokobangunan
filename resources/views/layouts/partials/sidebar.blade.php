<style>
.sidebar-collapsed .sidebar-label { display: none; }
.sidebar-collapsed nav a { justify-content: center; }
.sidebar-collapsed .section-title { display: none; }
.sidebar-collapsed .profile-info { display: none; }
.sidebar-collapsed .logout-text { display: none; }
</style>

<div 
    x-cloak
    :class="sidebarOpen ? 'w-64 translate-x-0' : 'w-20 sidebar-collapsed -translate-x-full md:translate-x-0'"
    class="bg-gradient-to-b from-[#FAF7F2] via-[#F8F4EE] to-[#F5F0E8] text-slate-800 p-3 transition-all duration-500 ease-in-out shadow-2xl border-r border-[#E8DFD3] overflow-hidden
           fixed inset-y-0 left-0 z-50 
           md:relative md:z-auto md:flex md:flex-col md:justify-between"
>
    
    <div class="flex items-center justify-center mb-10 p-4">
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 min-w-0 hover:bg-black/10 rounded-lg p-2 transition-all duration-300">
             <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-amber-700 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                 <path d="M3 13h2v8H3zM17 6h2v15h-2zM10 9h2v12h-2z" />
             </svg>
             <span class="text-xl font-bold text-slate-900 whitespace-nowrap sidebar-label">Jaya Prana</span>
        </a>
    </div>

    <div class="flex flex-col flex-grow overflow-y-auto scrollbar-thin scrollbar-thumb-amber-400 scrollbar-track-amber-100">
        <div class="space-y-6">
            <nav class="space-y-1">
                {{-- Dashboard --}}
                <a href="{{ route('dashboard') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-300 group
                          {{ request()->routeIs('dashboard') ? 'bg-amber-200/80 text-slate-900 shadow-lg border-l-4 border-amber-700' : 'text-slate-700 hover:bg-amber-100/60 hover:text-slate-900' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0 transition-transform duration-300 group-hover:scale-110 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6-4h.01M12 12h.01M15 12h.01M12 9h.01M15 9h.01M9 9h.01" /></svg>
                    <span class="font-medium whitespace-nowrap sidebar-label">Dashboard</span>
                </a>

                {{-- SECTION: MASTER DATA --}}
                <div class="pt-6">
                    <h3 class="text-xs uppercase tracking-wider text-amber-700 px-4 pb-3 font-bold section-title">Master Data</h3>
                </div>
                
                <a href="{{ route('stokbarang') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-300 group
                          {{ request()->routeIs('stokbarang') || request()->routeIs('barang.*') ? 'bg-amber-200/80 text-slate-900 shadow-lg border-l-4 border-amber-700' : 'text-slate-700 hover:bg-amber-100/60 hover:text-slate-900' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0 transition-transform duration-300 group-hover:scale-110 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" /></svg>
                    <span class="font-medium whitespace-nowrap sidebar-label">Barang</span>
                </a>

                <a href="{{ route('suppliers.index') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-300 group
                          {{ request()->routeIs('suppliers.*') ? 'bg-amber-200/80 text-slate-900 shadow-lg border-l-4 border-amber-700' : 'text-slate-700 hover:bg-amber-100/60 hover:text-slate-900' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0 transition-transform duration-300 group-hover:scale-110 text-pink-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V7M16 3h-1a4 4 0 00-8 0H6a2 2 0 00-2 2v2h16V5a2 2 0 00-2-2z" /></svg>
                    <span class="font-medium whitespace-nowrap sidebar-label">Supplier</span>
                </a>

                {{-- SECTION: TRANSAKSI --}}
                <div class="pt-6">
                    <h3 class="text-xs uppercase tracking-wider text-amber-700 px-4 pb-3 font-bold section-title">Transaksi</h3>
                </div>

                <a href="{{ route('purchase-requests.index') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-300 group
                          {{ request()->routeIs('purchase-requests.*') ? 'bg-amber-200/80 text-slate-900 shadow-lg border-l-4 border-amber-700' : 'text-slate-700 hover:bg-amber-100/60 hover:text-slate-900' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0 transition-transform duration-300 group-hover:scale-110 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    <span class="font-medium whitespace-nowrap sidebar-label">Pengajuan Pembelian</span>
                </a>

                @can('isAdmin')
                <a href="{{ route('purchase-approvals.index') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-300 group relative
                          {{ request()->routeIs('purchase-approvals.*') ? 'bg-amber-200/80 text-slate-900 shadow-lg border-l-4 border-amber-700' : 'text-slate-700 hover:bg-amber-100/60 hover:text-slate-900' }}">
                    <div class="relative">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0 transition-transform duration-300 group-hover:scale-110 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        @php
                            $pendingCount = \App\Models\PurchaseRequest::where('status', 'pending')->count();
                            $recentPending = \App\Models\PurchaseRequest::where('status', 'pending')
                                ->where('created_at', '>=', now()->subHours(24))
                                ->count();
                        @endphp
                        @if($recentPending > 0)
                            <span class="absolute -top-1 -right-1 flex h-3 w-3">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                            </span>
                        @endif
                    </div>
                    <span class="font-medium whitespace-nowrap sidebar-label">Persetujuan Pembelian</span>
                    @if($pendingCount > 0)
                        <span class="ml-2 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-500 rounded-full animate-pulse">{{ $pendingCount }}</span>
                    @endif
                </a>

                <a href="{{ route('purchases.index') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-300 group
                          {{ request()->routeIs('purchases.*') ? 'bg-amber-200/80 text-slate-900 shadow-lg border-l-4 border-amber-700' : 'text-slate-700 hover:bg-amber-100/60 hover:text-slate-900' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0 transition-transform duration-300 group-hover:scale-110 text-orange-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                    <span class="font-medium whitespace-nowrap sidebar-label">Pembelian Barang</span>
                </a>

                <a href="{{ route('goods-receipts.index') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-300 group
                          {{ request()->routeIs('goods-receipts.*') ? 'bg-amber-200/80 text-slate-900 shadow-lg border-l-4 border-amber-700' : 'text-slate-700 hover:bg-amber-100/60 hover:text-slate-900' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0 transition-transform duration-300 group-hover:scale-110 text-teal-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
                    <span class="font-medium whitespace-nowrap sidebar-label">Penerimaan Barang</span>
                    @php
                        $readyToReceive = \App\Models\Purchase::where('status_pembayaran', 'lunas')->whereDoesntHave('goodsReceipt')->count();
                    @endphp
                    @if($readyToReceive > 0)
                        <span class="ml-2 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-green-500 rounded-full">{{ $readyToReceive }}</span>
                    @endif
                </a>
                @endcan
                
                     <a href="{{ route('daily-sales.index') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-300 group
                                  {{ request()->routeIs('daily-sales.*') || request()->routeIs('cart.*') ? 'bg-amber-200/80 text-slate-900 shadow-lg border-l-4 border-amber-700' : 'text-slate-700 hover:bg-amber-100/60 hover:text-slate-900' }}">
                    <div class="relative">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0 transition-transform duration-300 group-hover:scale-110 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M3 6h18M3 14h18M3 18h18" /></svg>
                        @if(session('cart') && count(session('cart')) > 0)
                            <span class="absolute -top-2 -right-2 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-500 rounded-full">{{ count(session('cart')) }}</span>
                        @endif
                    </div>
                    <span class="font-medium whitespace-nowrap sidebar-label">Catat Penjualan</span>
                </a>

                {{-- SECTION: LAPORAN (Admin Only) --}}
                @can('isAdmin')
                <div class="pt-6">
                    <h3 class="text-xs uppercase tracking-wider text-amber-700 px-4 pb-3 font-bold section-title">Laporan</h3>
                </div>

                <a href="{{ route('barang-masuk.index') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-300 group
                          {{ request()->routeIs('barang-masuk.*') ? 'bg-amber-200/80 text-slate-900 shadow-lg border-l-4 border-amber-700' : 'text-slate-700 hover:bg-amber-100/60 hover:text-slate-900' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0 transition-transform duration-300 group-hover:scale-110 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    <span class="font-medium whitespace-nowrap sidebar-label">Barang Masuk</span>
                </a>

                <a href="{{ route('barang-keluar.index') }}" 
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-300 group
                          {{ request()->routeIs('barang-keluar.*') ? 'bg-amber-200/80 text-slate-900 shadow-lg border-l-4 border-amber-700' : 'text-slate-700 hover:bg-amber-100/60 hover:text-slate-900' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0 transition-transform duration-300 group-hover:scale-110 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    <span class="font-medium whitespace-nowrap sidebar-label">Barang Keluar</span>
                </a>

                     @php $pendingQuarantine = \App\Models\Quarantine::where('status','pending')->count(); @endphp
                     <a href="{{ route('admin.quarantines.index') }}"
                         class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-300 group
                                  {{ request()->routeIs('admin.quarantines.*') ? 'bg-amber-200/80 text-slate-900 shadow-lg border-l-4 border-amber-700' : 'text-slate-700 hover:bg-amber-100/60 hover:text-slate-900' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0 transition-transform duration-300 group-hover:scale-110 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01" />
                    </svg>
                    <span class="font-medium whitespace-nowrap sidebar-label">Karantina Barang</span>
                    @if($pendingQuarantine > 0)
                        <span class="ml-2 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-500 rounded-full">{{ $pendingQuarantine }}</span>
                    @endif
                </a>
                
                    <!-- Pengaturan (Admin) -->
                    <div class="pt-6">
                        <h3 class="text-xs uppercase tracking-wider text-amber-700 px-4 pb-3 font-bold section-title">Pengaturan</h3>
                    </div>

                    <a href="{{ route('admin.users.index') }}" 
                       class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-300 group
                              {{ request()->routeIs('admin.users.*') ? 'bg-amber-200/80 text-slate-900 shadow-lg border-l-4 border-amber-700' : 'text-slate-700 hover:bg-amber-100/60 hover:text-slate-900' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0 transition-transform duration-300 group-hover:scale-110 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                        <span class="font-medium whitespace-nowrap sidebar-label">Manajemen User</span>
                    </a>

                    @endcan
                                
            </nav>
        </div>
        
        <div class="mt-auto border-t border-[#E8DFD3] pt-4 pb-2 bg-gradient-to-b from-transparent to-[#F5F0E8]/30 rounded-lg">
            <div class="flex items-center mb-4 px-4 py-2 rounded-lg hover:bg-white/50 transition-all duration-300">
                <img class="h-10 w-10 rounded-lg object-cover flex-shrink-0 ring-2 ring-amber-600" src="{{ asset('profile.jpg') }}" alt="Foto Profil">
                <div class="ml-3 min-w-0 profile-info">
                    <p class="text-sm font-semibold text-slate-900 truncate">{{ Auth::user()->name ?? 'Admin' }}</p>
                    <p class="text-xs text-amber-700 font-medium">Online</p>
                </div>
            </div>
            
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <a href="{{ route('logout') }}"
                   onclick="event.preventDefault(); confirmLogout(this.closest('form'));"
                   class="flex items-center space-x-3 px-4 py-3 rounded-lg text-red-700 hover:bg-red-100/40 hover:text-red-800 transition-all duration-300 font-medium group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0 transition-transform duration-300 group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                    <span class="whitespace-nowrap logout-text">Keluar</span>
                </a>
            </form>
        </div>
    </div>
</div>