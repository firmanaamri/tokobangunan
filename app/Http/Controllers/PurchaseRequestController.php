<?php

namespace App\Http\Controllers;

use App\Models\PurchaseRequest;
use App\Models\Barang;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Tambahkan ini agar Auth::id() dikenali

class PurchaseRequestController extends Controller
{
    /**
     * Display a listing of purchase requests
     */
   /**
     * Display a listing of purchase requests
     */
    public function index(Request $request) // 1. Tambahkan parameter Request
    {
        // 2. Inisialisasi Query Builder
        $query = PurchaseRequest::with(['user', 'barang', 'supplier', 'approver']);

        // 3. Logika Pencarian (Berdasarkan nomor_pr)
        if ($request->filled('search')) {
            $query->where('nomor_pr', 'like', '%' . $request->search . '%');
            // Opsional: Jika ingin cari nama barang juga, gunakan orWhereHas
            // $query->orWhereHas('barang', function($q) use ($request) {
            //     $q->where('nama_barang', 'like', '%' . $request->search . '%');
            // });
        }

        // 4. Logika Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 5. Eksekusi Query
        $purchaseRequests = $query->latest()->paginate(15);

        // 6. Penting: Tambahkan ini agar filter tidak hilang saat klik 'Next Page'
        $purchaseRequests->appends($request->all());
        
        return view('purchase-requests.index', compact('purchaseRequests'));
    }

    /**
     * Show the form for creating a new purchase request
     */
    public function create()
    {
        $barangs = Barang::with('kategori')->get();
        $suppliers = Supplier::where('status', 'aktif')->get();
        
        return view('purchase-requests.create', compact('barangs', 'suppliers'));
    }

    /**
     * Store a newly created purchase request in storage
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'jumlah_diminta' => 'required|integer|min:1',
            'satuan' => 'required|string',
            'supplier_id' => 'required|exists:suppliers,id',
            // Gunakan nullable agar form boleh kosong
            'payment_term' => 'nullable|integer|min:0', 
            'due_date' => 'nullable|date',
            'catatan_request' => 'nullable|string',
        ]);

        // Generate nomor PR
        $lastPR = PurchaseRequest::orderBy('id', 'desc')->first();
        // Menggunakan regex untuk mengambil angka saja agar lebih aman
        $number = 1;
        if ($lastPR) {
            // Ambil angka dari string, misal PR000015 -> 15
            if (preg_match('/\d+/', $lastPR->nomor_pr, $matches)) {
                $number = intval($matches[0]) + 1;
            }
        }
        $nomor_pr = 'PR' . str_pad($number, 6, '0', STR_PAD_LEFT);

        $purchaseRequest = PurchaseRequest::create([
            'nomor_pr' => $nomor_pr,
            'user_id' => Auth::id(), // Gunakan Auth Facade
            'barang_id' => $validated['barang_id'],
            'supplier_id' => $validated['supplier_id'],
            'jumlah_diminta' => $validated['jumlah_diminta'],
            'satuan' => $validated['satuan'],
            'payment_term' => $validated['payment_term'] ?? null,
            'due_date' => $validated['due_date'] ?? null,
            'catatan_request' => $validated['catatan_request'] ?? null,
            'status' => 'pending',
        ]);

        return redirect()->route('purchase-requests.show', $purchaseRequest)
            ->with('success', 'Purchase Request berhasil dibuat!');
    }

    /**
     * Display the specified purchase request
     */
    public function show(PurchaseRequest $purchaseRequest)
    {
        $purchaseRequest->load(['user', 'barang.kategori', 'supplier', 'approver']);
        
        return view('purchase-requests.show', compact('purchaseRequest'));
    }

    /**
     * Show the form for editing the specified purchase request
     */
    public function edit(PurchaseRequest $purchaseRequest)
    {
        // Only staff yang membuat bisa edit, dan hanya jika status pending
        if ($purchaseRequest->status !== 'pending' || $purchaseRequest->user_id !== Auth::id()) {
            return back()->with('error', 'Anda tidak bisa edit PR ini');
        }

        $barangs = Barang::with('kategori')->get();
        $suppliers = Supplier::where('status', 'aktif')->get();
        
        return view('purchase-requests.edit', compact('purchaseRequest', 'barangs', 'suppliers'));
    }

    /**
     * Update the specified purchase request in storage
     */
    public function update(Request $request, PurchaseRequest $purchaseRequest)
    {
        if ($purchaseRequest->status !== 'pending' || $purchaseRequest->user_id !== Auth::id()) {
            return back()->with('error', 'Anda tidak bisa edit PR ini');
        }

        $validated = $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'jumlah_diminta' => 'required|integer|min:1',
            'satuan' => 'required|string',
            'supplier_id' => 'required|exists:suppliers,id',
            'payment_term' => 'nullable|integer|min:0',
            'due_date' => 'nullable|date',
            'catatan_request' => 'nullable|string',
        ]);

        $purchaseRequest->update([
            'barang_id' => $validated['barang_id'],
            'supplier_id' => $validated['supplier_id'],
            'jumlah_diminta' => $validated['jumlah_diminta'],
            'satuan' => $validated['satuan'],
            'payment_term' => $validated['payment_term'] ?? null,
            'due_date' => $validated['due_date'] ?? null,
            'catatan_request' => $validated['catatan_request'] ?? null,
        ]);

        return redirect()->route('purchase-requests.show', $purchaseRequest)
            ->with('success', 'Purchase Request berhasil diupdate!');
    }

    /**
     * Remove the specified purchase request from storage
     */
    public function destroy(PurchaseRequest $purchaseRequest)
    {
        if ($purchaseRequest->status !== 'pending' || $purchaseRequest->user_id !== Auth::id()) {
            return back()->with('error', 'Anda tidak bisa hapus PR ini');
        }

        $purchaseRequest->delete();

        return redirect()->route('purchase-requests.index')
            ->with('success', 'Purchase Request berhasil dihapus!');
    }
}