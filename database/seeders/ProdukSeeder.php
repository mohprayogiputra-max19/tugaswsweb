<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProdukSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'nama_produk' => 'Laptop Gaming',
                'kategori' => 'Elektronik',
                'harga' => 15000000,
                'stok' => 3,
            ],
            [
                'nama_produk' => 'Laptop Kantor',
                'kategori' => 'Elektronik',
                'harga' => 10000000,
                'stok' => 5,
            ],
            [
                'nama_produk' => 'Laptop Ultrabook',
                'kategori' => 'Elektronik',
                'harga' => 12500000,
                'stok' => 4,
            ],
            [
                'nama_produk' => 'Laptop Pelajar',
                'kategori' => 'Elektronik',
                'harga' => 7000000,
                'stok' => 8,
            ],
            [
                'nama_produk' => 'Laptop 2-in-1',
                'kategori' => 'Elektronik',
                'harga' => 9000000,
                'stok' => 6,
            ],
        ];

        foreach ($products as $product) {
            $alreadyExists = DB::table('produk')
                ->where('nama_produk', $product['nama_produk'])
                ->where('kategori', $product['kategori'])
                ->exists();

            if ($alreadyExists) {
                continue;
            }

            DB::table('produk')->insert([
                ...$product,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
