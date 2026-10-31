<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class ProdukController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_produk' => ['required', 'string', 'max:255'],
            'kategori' => ['required', 'string', 'max:255'],
            'harga' => ['required', 'numeric', 'decimal:0,2', 'min:0.01', 'max:9999999999.99'],
            'stok' => ['required', 'integer', 'min:0', 'max:4294967295'],
        ]);

        DB::table('produk')->insert([
            ...$validated,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function index()
    {
        $daftarProduk = DB::table('produk')
            ->select('id', 'nama_produk', 'kategori', 'harga', 'stok')
            ->orderBy('nama_produk')
            ->get();

        return view('produk', [
            'kategori' => 'Semua',
            'daftarProduk' => $daftarProduk,
        ]);
    }

    public function show(string $id)
    {
        $produk = DB::table('produk')
            ->select('id', 'nama_produk', 'kategori', 'harga', 'stok')
            ->where('id', $id)
            ->first();

        abort_if($produk === null, 404);

        return view('detail', ['produk' => $produk]);
    }

    public function destroy(string $id): RedirectResponse
    {
        $deleted = DB::table('produk')->where('id', $id)->delete();
        abort_if($deleted === 0, 404);

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }

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