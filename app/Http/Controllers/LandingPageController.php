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
    // Di dalam LandingController function index/landing
public function index(Request $request)
{
    $query = $request->input('q');

    $products = Barang::with('kategori') // Pastikan load relasi kategori
        ->when($query, function ($q) use ($query) {
            $q->where('nama_barang', 'like', '%' . $query . '%')
              ->orWhereHas('kategori', function ($subQ) use ($query) {
                  // Mencari berdasarkan nama kategori
                  $subQ->where('nama_kategori', 'like', '%' . $query . '%');
              });
        })
        ->paginate(12);

    $categories = Kategori::withCount('barang')->get(); // Sesuaikan nama model

    return view('landing', compact('products', 'categories', 'query'));
}
}
