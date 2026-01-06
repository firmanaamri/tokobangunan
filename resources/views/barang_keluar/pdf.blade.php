<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Barang Keluar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            color: #333;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table thead {
            background-color: #f3f4f6;
        }
        table th {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
            font-weight: bold;
            color: #333;
        }
        table td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        table tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
            color: #666;
        }
        .summary {
            margin-top: 20px;
            padding: 10px;
            background-color: #fef2f2;
            border-left: 4px solid #ef4444;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .badge-red {
            background-color: #fee2e2;
            color: #991b1b;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN BARANG KELUAR</h1>
        <p>Toko Bangunan Jaya Prana</p>
        <p><strong>Periode:</strong> {{ $filterLabel ?? 'Semua Data' }}</p>
        <p>Tanggal Cetak: {{ now()->format('d F Y, H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 30%;">Nama Barang</th>
                <th style="width: 15%;">Kategori</th>
                <th style="width: 10%;">Jumlah</th>
                <th style="width: 15%;">Tanggal Keluar</th>
                <th style="width: 25%;">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @forelse($items as $index => $item)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $item->barang->nama_barang }}</strong><br>
                        <small style="color: #666;">SKU: {{ $item->barang->sku }}</small>
                    </td>
                    <td>{{ $item->barang->kategori?->nama_kategori ?? '-' }}</td>
                    <td style="text-align: center;">
                        <strong>{{ number_format($item->jumlah_barang_keluar) }}</strong>
                        <small>{{ $item->barang->satuan ?? 'pcs' }}</small>
                    </td>
                    <td>{{ $item->tanggal_keluar->format('d M Y') }}</td>
                    <td style="font-size: 10px;">{{ $item->keterangan ?? '-' }}</td>
                </tr>
                @php $total += $item->jumlah_barang_keluar; @endphp
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: #999;">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr style="background-color: #f3f4f6; font-weight: bold;">
                <td colspan="3" style="text-align: right;">TOTAL:</td>
                <td style="text-align: center;">{{ number_format($total) }}</td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
    </table>

    <div class="summary">
        <strong>Ringkasan:</strong><br>
        Total Transaksi: {{ $items->count() }} transaksi<br>
        Total Barang Keluar: {{ number_format($total) }} unit
    </div>

    <div class="footer">
        <p>Dicetak oleh sistem pada {{ now()->format('d F Y, H:i:s') }}</p>
    </div>
</body>
</html>
