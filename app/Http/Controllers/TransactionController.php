<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        // Menggunakan QUERY SCOPE (pemasukan) yang kita buat di Model
        // Mengambil semua pemasukan bulan ini
        $pemasukan = Transaction::pemasukan()->get();
        
        return view('transaksi.index', compact('pemasukan'));
    }

    public function destroy($id)
    {
        // Fitur hapus data. Karena ada SoftDeletes di Model, data TIDAK akan hilang dari database.
        $transaksi = Transaction::findOrFail($id);
        $transaksi->delete();
        
        return redirect()->back()->with('success', 'Transaksi berhasil dihapus ke tong sampah.');
    }
    // RELATIONSHIP: Relasi ke tabel Transaction (Acara 19)
    // Penjelasan: "Satu User memiliki banyak (hasMany) Transaksi"
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}