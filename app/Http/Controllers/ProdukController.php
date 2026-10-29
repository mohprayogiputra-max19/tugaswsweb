<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProdukController extends Controller
{
    public function cariProduk()
    {
        $keyword = 'Laptop';
        $kategori = 'Elektronik';
        $dataPerHalaman = 5;
        $offset = 0; // Halaman 1 tidak melompati data apa pun

        $produk = DB::table('produk')
            ->select('id', 'nama_produk', 'kategori', 'harga', 'stok')
            ->where('nama_produk', 'LIKE', '%' . $keyword . '%')
            ->where('kategori', $kategori)
            ->orderBy('harga', 'desc') // Mengurutkan dari harga termahal
            ->limit($dataPerHalaman)
            ->offset($offset)
            ->get();

        return response()->json($produk);
    }
}