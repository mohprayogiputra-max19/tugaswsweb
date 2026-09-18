<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LaporanPenjualanController extends Controller
{
    public function __invoke(Request $request)
    {
        // Data identitas transaksi
        $transaksi = [
            'no_nota' => 'INV-20260917-001',
            'kasir'   => 'Moh. Prayogi', 
            'tanggal' => date('d M Y H:i'),
        ];

        // Data keranjang belanja kasir
        $keranjang = [
            ['nama' => 'Laptop Axioo Pongo 725', 'harga' => 12500000, 'qty' => 1],
            ['nama' => 'Mouse Gaming Wireless', 'harga' => 350000, 'qty' => 2],
            ['nama' => 'Keyboard Mechanical', 'harga' => 750000, 'qty' => 1],
        ];

        // Logika perhitungan otomatis
        $subtotal = 0;
        foreach ($keranjang as $item) {
            $subtotal += ($item['harga'] * $item['qty']);
        }
        
        $pajak = $subtotal * 0.11; // Simulasi PPN 11%
        $total = $subtotal + $pajak;

        return view('laporan', compact('transaksi', 'keranjang', 'subtotal', 'pajak', 'total'));
    }
}