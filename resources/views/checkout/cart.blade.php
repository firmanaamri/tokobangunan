@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">Keranjang</h1>
            <p class="text-sm text-slate-400">Cek daftar item sebelum checkout.</p>
        </div>
        <a href="{{ route('barang') }}" class="inline-flex items-center px-4 py-2 bg-slate-100 text-slate-700 rounded-md hover:bg-slate-200 transition">Tambah Barang</a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border p-6">
        @if($errors->any())
            <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(empty($cart))
            <div class="text-center text-slate-500">Keranjang kosong.</div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-semibold text-slate-600">Barang</th>
                            <th class="px-4 py-2 text-right text-xs font-semibold text-slate-600">Qty</th>
                            <th class="px-4 py-2 text-right text-xs font-semibold text-slate-600">Harga</th>
                            <th class="px-4 py-2 text-right text-xs font-semibold text-slate-600">Total</th>
                            <th class="px-4 py-2 text-center text-xs font-semibold text-slate-600">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @php $subtotal = 0; @endphp
                        @foreach($cart as $i => $item)
                            @php
                                $barang = \App\Models\Barang::find($item['barang_id']);
                                $unit = $item['unit_price'] ?? ($barang->harga ?? 0);
                                $total = $unit * $item['qty'];
                                $subtotal += $total;
                            @endphp
                            <tr>
                                <td class="px-4 py-3 text-sm">{{ $barang?->nama_barang ?? '—' }}</td>
                                <td class="px-4 py-3 text-sm text-right">
                                    <form method="POST" action="{{ route('cart.update') }}" class="flex items-center justify-end space-x-2">
                                        @csrf
                                        <input type="hidden" name="index" value="{{ $i }}">
                                        <input name="qty" type="number" min="1" value="{{ $item['qty'] }}" class="w-20 rounded border-gray-200 text-right">
                                        <span class="text-xs text-slate-500">{{ $item['satuan'] ?? $barang?->satuan ?? 'pcs' }}</span>
                                        <button class="px-2 py-1 bg-slate-100 text-slate-700 rounded">Update</button>
                                    </form>
                                </td>
                                <td class="px-4 py-3 text-sm text-right">
                                    <form method="POST" action="{{ route('cart.update') }}" class="flex items-center justify-end space-x-2">
                                        @csrf
                                        <input type="hidden" name="index" value="{{ $i }}">
                                        <input name="unit_price" type="number" step="0.01" min="0" value="{{ $unit }}" class="w-28 rounded border-gray-200 text-right">
                                        <button class="px-2 py-1 bg-slate-100 text-slate-700 rounded">Update</button>
                                    </form>
                                </td>
                                <td class="px-4 py-3 text-sm text-right">Rp {{ number_format($total,2,',','.') }}</td>
                                <td class="px-4 py-3 text-sm text-center">
                                    <form method="POST" action="{{ route('cart.remove') }}">
                                        @csrf
                                        <input type="hidden" name="index" value="{{ $i }}">
                                        <button class="text-red-600">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4 flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-600">Subtotal</p>
                    <p class="text-lg font-semibold">Rp {{ number_format($subtotal,2,',','.') }}</p>
                </div>

                <form method="POST" action="{{ route('checkout.process') }}" class="space-y-2">
                    @csrf
                    <div>
                        <label class="block text-sm text-slate-600">Pelanggan (opsional)</label>
                        <select name="customer_id" class="mt-1 block rounded border-gray-200 w-72">
                            <option value="">Umum</option>
                            @foreach(\App\Models\Customer::limit(20)->get() as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm text-slate-600">Catatan</label>
                        <textarea name="notes" class="mt-1 block w-full rounded border-gray-200"></textarea>
                    </div>
                    <div>
                        <button class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700">Checkout</button>
                    </div>
                </form>
            </div>
        @endif
    </div>
</div>
@endsection
