<?php

namespace Tests\Feature;

use Database\Seeders\ProdukSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ProdukSearchApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_search_returns_matching_products(): void
    {
        DB::table('produk')->insert([
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
                'nama_produk' => 'Laptop Bag',
                'kategori' => 'Aksesoris',
                'harga' => 500000,
                'stok' => 7,
            ],
            [
                'nama_produk' => 'Mouse',
                'kategori' => 'Elektronik',
                'harga' => 250000,
                'stok' => 10,
            ],
        ]);

        $this->getJson('/api/cari-produk')
            ->assertOk()
            ->assertJsonCount(2)
            ->assertJsonPath('0.nama_produk', 'Laptop Gaming')
            ->assertJsonPath('1.nama_produk', 'Laptop Kantor');
    }

    public function test_product_seeder_populates_search_results_without_duplicates(): void
    {
        $this->seed(ProdukSeeder::class);
        $this->seed(ProdukSeeder::class);

        $this->getJson('/api/cari-produk')
            ->assertOk()
            ->assertJsonCount(5);
    }
}
