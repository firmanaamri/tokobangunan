<x-app-layout>

    <x-slot:title>
        Dashboard Staff
    </x-slot:title>

    <x-slot:header>
        Dashboard
    </x-slot:header>

    <div class="min-h-screen bg-gradient-to-br from-[#FAF7F2] via-[#F8F4EE] to-[#FAF7F2] p-4 sm:p-6">
        <div class="max-w-7xl mx-auto space-y-8">

            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold text-slate-900">Ringkasan Operasional</h1>
                    <p class="text-slate-600 mt-2">Status stok, supplier, dan aktivitas terkini</p>
                </div>
                <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                    <a href="{{ route('purchase-requests.create') }}" class="w-full sm:w-auto text-center bg-gradient-to-r from-blue-500 to-cyan-500 hover:from-blue-600 hover:to-cyan-600 text-white font-semibold px-4 py-2 rounded-lg shadow">+ Ajukan Pembelian</a>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 sm:gap-6">
                <div class="p-5 sm:p-6 rounded-xl shadow-lg border border-emerald-100 bg-gradient-to-br from-emerald-50 via-white to-emerald-50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs uppercase font-semibold text-emerald-600">Stok</p>
                            <h4 class="text-sm font-medium text-slate-600">Total Produk</h4>
                        </div>
                        <div class="h-10 w-10 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center">
                            <i class="fas fa-box"></i>
                        </div>
                    </div>
                    <p class="text-3xl font-bold text-slate-900 mt-3">{{ number_format($totalProduk) }}</p>
                    <span class="text-xs text-emerald-600 font-semibold block mt-2">+{{ $produkBaru }} item baru</span>
                </div>

                <div class="p-5 sm:p-6 rounded-xl shadow-lg border border-rose-100 bg-gradient-to-br from-rose-50 via-white to-rose-50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs uppercase font-semibold text-rose-600">Risiko</p>
                            <h4 class="text-sm font-medium text-slate-600">Stok Akan Habis</h4>
                        </div>
                        <div class="h-10 w-10 rounded-lg bg-rose-100 text-rose-600 flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                    </div>
                    <p class="text-3xl font-bold text-rose-600 mt-3">{{ $stokHabis }}</p>
                    <span class="text-xs text-rose-600 font-semibold block mt-2">Butuh restock segera</span>
                </div>

                <div class="p-5 sm:p-6 rounded-xl shadow-lg border border-blue-100 bg-gradient-to-br from-blue-50 via-white to-blue-50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs uppercase font-semibold text-blue-600">Pengajuan</p>
                            <h4 class="text-sm font-medium text-slate-600">Total PR Saya</h4>
                        </div>
                        <div class="h-10 w-10 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                            <i class="fas fa-file-alt"></i>
                        </div>
                    </div>
                    <p class="text-3xl font-bold text-slate-900 mt-3">{{ number_format($myPRs) }}</p>
                    <span class="text-xs text-blue-600 font-semibold block mt-2">{{ $pendingPRs }} pending</span>
                </div>

                <div class="p-5 sm:p-6 rounded-xl shadow-lg border border-amber-100 bg-gradient-to-br from-amber-50 via-white to-amber-50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs uppercase font-semibold text-amber-600">Barang Keluar</p>
                            <h4 class="text-sm font-medium text-slate-600">Jumlah Keluar (Bulan Ini)</h4>
                        </div>
                        <div class="h-10 w-10 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center">
                            <i class="fas fa-truck-loading"></i>
                        </div>
                    </div>
                    <p class="text-3xl font-bold text-slate-900 mt-3">{{ number_format($barangKeluarThisMonth) }}</p>
                    <span class="text-xs text-amber-600 font-semibold block mt-2">Total {{ number_format($totalBarangKeluar) }} transaksi</span>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg border border-slate-200 overflow-hidden">
                <div class="p-6 border-b border-slate-200 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">Aktivitas Stok Terbaru</h3>
                        <p class="text-sm text-slate-500">Penerimaan dan pengeluaran stok terkini</p>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[720px]">
                        <thead class="bg-gradient-to-r from-slate-800 to-slate-700 text-white">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase">Nama Produk</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase">Tipe</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase">Jumlah</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase">Oleh</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @forelse($aktivitas as $item)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">{{ $item['tanggal']->format('d M Y, H:i') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">{{ $item['nama_barang'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $isMasuk = $item['tipe'] === 'Stok Masuk';
                                        $badge = $isMasuk ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800';
                                    @endphp
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded {{ $badge }}">
                                        {{ $item['tipe'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">{{ $item['jumlah'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">{{ $item['oleh'] }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-slate-500">
                                    <i class="fas fa-inbox text-4xl text-slate-300 mb-3"></i>
                                    Belum ada aktivitas stok.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg border border-slate-200 overflow-hidden">
                <div class="p-6 border-b border-slate-200 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">Pengajuan Pembelian Saya</h3>
                        <p class="text-sm text-slate-500">Daftar PR yang saya buat</p>
                    </div>
                    <a href="{{ route('purchase-requests.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-semibold">Lihat Semua →</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[720px]">
                        <thead class="bg-gradient-to-r from-slate-800 to-slate-700 text-white">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase">No. PR</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase">Barang</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase">Jumlah</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @forelse($recentPRs as $pr)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-mono font-bold text-slate-900">{{ $pr->nomor_pr }}</td>
                                <td class="px-6 py-4 text-sm font-medium text-slate-900">{{ $pr->barang->nama_barang ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">{{ $pr->jumlah_diminta }} {{ $pr->satuan }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($pr->status == 'pending')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded bg-amber-100 text-amber-800">Pending</span>
                                    @elseif($pr->status == 'approved')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded bg-green-100 text-green-800">Approved</span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded bg-red-100 text-red-800">Rejected</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">{{ $pr->created_at->format('d M Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-slate-500">
                                    <i class="fas fa-inbox text-4xl text-slate-300 mb-3"></i>
                                    <p>Belum ada pengajuan pembelian</p>
                                    <a href="{{ route('purchase-requests.create') }}" class="inline-block mt-3 text-blue-600 hover:text-blue-700 font-semibold">Buat Pengajuan Baru</a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

</x-app-layout>
