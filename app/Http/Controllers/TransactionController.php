<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TransactionController extends Controller
{
    public function index()
    {
        $transaksi = Transaction::with('user')->latest()->get();

        return view('transaksi.index', compact('transaksi'));
    }

    public function create()
    {
        $users = User::orderBy('name')->get(['id', 'name', 'email']);

        return view('transaksi.create', compact('users'));
    }

    public function store(Request $request)
    {
        Transaction::create($this->validatedTransactionData($request));

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil ditambahkan.');
    }

    public function edit(int $id)
    {
        $transaksi = Transaction::findOrFail($id);
        $users = User::orderBy('name')->get(['id', 'name', 'email']);

        return view('transaksi.edit', compact('transaksi', 'users'));
    }

    public function update(Request $request, int $id)
    {
        $transaksi = Transaction::findOrFail($id);
        $transaksi->update($this->validatedTransactionData($request));

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        // Fitur hapus data. Karena ada SoftDeletes di Model, data TIDAK akan hilang dari database.
        $transaksi = Transaction::findOrFail($id);
        $transaksi->delete();
        
        return redirect()->back()->with('success', 'Transaksi berhasil dihapus ke tong sampah.');
    }

    private function validatedTransactionData(Request $request): array
    {
        return $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'jenis' => ['required', Rule::in(['pemasukan', 'pengeluaran'])],
            'nominal' => ['required', 'numeric', 'min:0.01'],
            'keterangan' => ['nullable', 'string', 'max:1000'],
        ]);
    }

    // RELATIONSHIP: Relasi ke tabel Transaction (Acara 19)
    // Penjelasan: "Satu User memiliki banyak (hasMany) Transaksi"
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}