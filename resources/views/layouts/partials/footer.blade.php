<footer class="mt-auto bg-cyan-800 border-t shadow-sm z-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex flex-col md:flex-row items-center justify-center gap-3">
            <div class="text-sm text-white">
                &copy; {{ date('Y') }} <span class="font-semibold">Toko Bangunan Jaya Prana</span>. All rights reserved.
            </div>

            {{-- <div class="flex items-center space-x-4">
                <a href="{{ route('dashboard') }}" class="text-sm text-slate-600 hover:text-slate-800">Dashboard</a>
                <a href="{{ route('stokbarang') }}" class="text-sm text-slate-600 hover:text-slate-800">Stok</a>
                @if(Route::has('barang'))
                    <a href="{{ route('barang') }}" class="text-sm text-slate-600 hover:text-slate-800">Barang</a>
                @endif
                <span class="text-xs text-slate-400">v1.0</span>
            </div> --}}
        </div>
    </div>
</footer>
