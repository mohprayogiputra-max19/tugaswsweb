<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function index()
    {
        // 1. Siapkan data kategori
        $kategori = 'Elektronik & Gadget';

        // 2. Siapkan data produk (biasanya ini diambil dari database, tapi kita pakai array dulu untuk contoh)
        $daftarProduk = [
            [
                'id' => 1,
                'nama' => 'Laptop Axioo Pongo 725',
                'harga' => 12500000,
            ],
            [
                'id' => 2,
                'nama' => 'Mouse Gaming Wireless',
                'harga' => 350000,
            ],
            [
                'id' => 3,
                'nama' => 'Keyboard Mechanical',
                'harga' => 750000,
            ],
        ];

        // 3. Kirim data ke file view (Blade)
        // 'produk' merujuk ke nama file produk.blade.php di dalam folder resources/views/
        // compact('kategori', 'daftarProduk') digunakan untuk mengirim variabel ke view
        return view('produk', compact('kategori', 'daftarProduk'));
    }

    // Fungsi show untuk rute Route::get('/produk/{id}') yang ada di web.php kamu
    public function show($id)
    {
        return "Ini adalah halaman detail untuk produk dengan ID: " . $id;
    }
}