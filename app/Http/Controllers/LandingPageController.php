<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    /**
     * Display the landing page
     */
    public function index(Request $request)
    {
        // Get only categories that have products in stock
        $categories = Kategori::withCount([
            'barang' => function ($query) {
                $query->where('stok_saat_ini', '>', 0);
            }
        ])
        ->having('barang_count', '>', 0)
        ->get();

        // Get featured products (in stock and newest)
        $featuredProducts = Barang::where('stok_saat_ini', '>', 0)
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        // Search functionality
        $query = $request->input('q');
        $products = [];
        
        if ($query) {
            $products = Barang::where('stok_saat_ini', '>', 0)
                ->where(function ($q) use ($query) {
                    $q->where('nama_barang', 'like', "%{$query}%")
                      ->orWhere('sku', 'like', "%{$query}%");
                })
                ->paginate(12);
        }

        return view('landing', compact('categories', 'featuredProducts', 'products', 'query'));
    }
}
