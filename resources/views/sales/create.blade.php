@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">Buat Penjualan Baru</h1>
            <p class="text-sm text-slate-400">Buat transaksi penjualan secara manual.</p>
        </div>
        <a href="{{ route('sales.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-100 text-slate-700 rounded-md hover:bg-slate-200 transition">Kembali</a>
    </div>

    @if($errors->any())
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border p-6">
        <form action="{{ route('sales.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm text-slate-600">Nomor (opsional)</label>
                <input type="text" name="nomor" class="mt-1 block w-full rounded border-gray-200" placeholder="Biarkan kosong untuk auto-generate">
            </div>

            <div>
                <label class="block text-sm text-slate-600">Pelanggan (opsional)</label>
                <select name="customer_id" class="mt-1 block w-full rounded border-gray-200">
                    <option value="">Umum</option>
                    @foreach(App\Models\Customer::limit(50)->get() as $c)
                        <option value="{{ $c->id }}">{{ $c->name }} — {{ $c->phone ?? $c->email }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm text-slate-600">Catatan</label>
                <textarea name="notes" class="mt-1 block w-full rounded border-gray-200" rows="4"></textarea>
            </div>

            <div class="flex items-center space-x-3">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700">Buat Penjualan</button>
                <a href="{{ route('cart.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-100 text-slate-700 rounded-md hover:bg-slate-200">Buka Keranjang</a>
            </div>
        </form>

        @if(session('cart') && count(session('cart')) > 0)
            <div class="mt-6 border-t pt-6">
                <h3 class="text-lg font-semibold">Buat dari Keranjang</h3>
                <p class="text-sm text-slate-500 mb-4">Keranjang Anda berisi {{ count(session('cart')) }} item. Anda dapat membuat penjualan langsung dari keranjang.</p>

                <div class="overflow-x-auto mb-4">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-2 text-left">Produk</th>
                                <th class="px-4 py-2 text-right">Qty</th>
                                <th class="px-4 py-2 text-right">Harga</th>
                                <th class="px-4 py-2 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @php $subtotal = 0; @endphp
                            @foreach(session('cart') as $item)
                                @php $p = \App\Models\Barang::find($item['barang_id']); $unit = $item['unit_price'] ?? ($p->harga ?? 0); $total = $unit * $item['qty']; $subtotal += $total; @endphp
                                <tr>
                                    <td class="px-4 py-2">{{ $p?->nama_barang ?? '—' }}</td>
                                    <td class="px-4 py-2 text-right">{{ $item['qty'] }}</td>
                                    <td class="px-4 py-2 text-right">Rp {{ number_format($unit,2,',','.') }}</td>
                                    <td class="px-4 py-2 text-right">Rp {{ number_format($total,2,',','.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="px-4 py-2 text-right font-semibold">Subtotal</td>
                                <td class="px-4 py-2 text-right font-semibold">Rp {{ number_format($subtotal,2,',','.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <form action="{{ route('checkout.process') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm text-slate-600">Pilih Pelanggan (opsional)</label>
                        <select name="customer_id" class="mt-1 block w-full rounded border-gray-200">
                            <option value="">Umum</option>
                            @foreach(App\Models\Customer::limit(50)->get() as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm text-slate-600">Catatan</label>
                        <textarea name="notes" class="mt-1 block w-full rounded border-gray-200" rows="2"></textarea>
                    </div>

                    <div class="flex items-center space-x-3">
                        <button class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700">Buat Penjualan dari Keranjang</button>
                        <a href="{{ route('cart.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-100 text-slate-700 rounded-md hover:bg-slate-200">Edit Keranjang</a>
                    </div>
                </form>
            </div>
        @endif
    </div>
</div>
@endsection
