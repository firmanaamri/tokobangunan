<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Barang;
use Illuminate\Support\Facades\Schema; // Tambahkan ini untuk pengecekan kolom

class SearchProduct extends Component
{
    public $search = '';

    public function render()
    {
        $products = [];

        if (strlen($this->search) >= 1) {
            // Mulai Query
            $query = Barang::query();

            // Grouping Query agar logika OR tidak bocor
            $query->where(function($q) {
                $q->where('nama_barang', 'like', '%' . $this->search . '%')
                  ->orWhere('sku', 'like', '%' . $this->search . '%');
                
                // Cek dulu apakah kolom 'satuan' ada di database sebelum mencarinya
                // Ini mencegah error "Column not found"
                if (Schema::hasColumn('barang', 'satuan')) {
                    $q->orWhere('satuan', 'like', '%' . $this->search . '%');
                }
            });

            $products = $query->latest()->take(12)->get();
        } else {
            // Tampilkan barang terbaru jika tidak mencari
            $products = Barang::latest()->take(8)->get();
        }

        return view('livewire.search-product', [
            'products' => $products
        ]);
    }
}