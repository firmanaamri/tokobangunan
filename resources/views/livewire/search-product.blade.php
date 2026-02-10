<div class="w-full relative">
    
    {{-- Input Search --}}
    <div class="relative group mb-8">
        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </div>
        <input 
            type="text" 
            wire:model.live.debounce.300ms="search" 
            class="block w-full pl-11 pr-4 py-4 border-2 border-slate-200 rounded-xl bg-white text-slate-900 focus:outline-none focus:border-sky-500 focus:ring-0 transition-all shadow-sm font-medium text-lg" 
            placeholder="Cari nama barang (contoh: Semen, Cat, Pipa)..."
        >
        
        {{-- Indikator Loading Kecil di Input --}}
        <div wire:loading wire:target="search" class="absolute inset-y-0 right-0 flex items-center pr-4">
            <svg class="animate-spin h-5 w-5 text-sky-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
    </div>

    {{-- Loading State Besar --}}
    <div wire:loading.flex wire:target="search" class="w-full flex-col items-center justify-center py-12">
        <p class="text-slate-500 font-medium animate-pulse">Sedang mencari data...</p>
    </div>

    {{-- Hasil Pencarian --}}
    <div wire:loading.remove wire:target="search" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @forelse($products as $product)
            <div class="group bg-white border border-slate-200 rounded-2xl p-4 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                
                {{-- Container Gambar --}}
                <div class="aspect-[4/3] bg-slate-50 rounded-xl mb-4 overflow-hidden relative">
                    
                    {{-- BADGE STATUS STOK (DIKEMBALIKAN) --}}
                   <div class="absolute top-3 right-3 z-10">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold border shadow-sm backdrop-blur-sm {{ $product->status_color }}">
                            {{ $product->status }}
                        </span>
                    </div>

                    {{-- Gambar Produk --}}
                    @if($product->gambar)
                        <img src="{{ asset('storage/' . $product->gambar) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-slate-300">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                    @endif
                </div>

                {{-- Detail Produk --}}
                <div class="space-y-2">
                    <h3 class="font-bold text-slate-800 line-clamp-2 min-h-[3rem] group-hover:text-sky-600 transition-colors">
                        {{ $product->nama_barang }}
                    </h3>
                    
                    <div class="flex items-end justify-between border-t border-slate-100 pt-3 mt-2">
                        <div>
                            <p class="text-xs text-slate-400 mb-0.5">Harga</p>
                            <p class="text-sky-600 font-extrabold text-lg">Rp {{ number_format($product->harga, 0, ',', '.') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-slate-400 mb-0.5">Stok</p>
                            <p class="text-sm font-medium text-slate-600">
                                {{ $product->stok_saat_ini }} <span class="text-xs text-slate-400 font-normal">{{ $product->satuan }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            @if(strlen($search) > 0)
                <div class="col-span-full text-center py-16 bg-slate-50 rounded-2xl border border-dashed border-slate-300">
                    <div class="inline-flex bg-white p-3 rounded-full shadow-sm mb-4">
                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800">Produk tidak ditemukan</h3>
                    <p class="text-slate-500 mt-1">Maaf, kami tidak menemukan barang dengan kata kunci "<strong>{{ $search }}</strong>".</p>
                </div>
            @endif
        @endforelse
    </div>
</div>