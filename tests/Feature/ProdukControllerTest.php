<?php

namespace Tests\Feature;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ProdukControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_the_product_list(): void
    {
        DB::table('produk')->insert([
            'nama_produk' => 'Laptop Gaming',
            'kategori' => 'Elektronik',
            'harga' => 15000000,
            'stok' => 3,
        ]);

        $this->actingAs(User::factory()->make(['role' => 'admin']))
            ->get(route('products.index'))
            ->assertOk()
            ->assertSeeText('Laptop Gaming')
            ->assertSee(route('products.show', 1));
    }

    public function test_admin_can_view_a_product_detail(): void
    {
        DB::table('produk')->insert([
            'nama_produk' => 'Laptop Gaming',
            'kategori' => 'Elektronik',
            'harga' => 15000000,
            'stok' => 3,
        ]);

        $this->actingAs(User::factory()->make(['role' => 'admin']))
            ->get(route('products.show', 1))
            ->assertOk()
            ->assertSeeText('Laptop Gaming')
            ->assertSeeText('Elektronik')
            ->assertSeeText('3');
    }

    public function test_missing_product_detail_returns_not_found(): void
    {
        $this->actingAs(User::factory()->make(['role' => 'admin']))
            ->get(route('products.show', 999))
            ->assertNotFound();
    }

    public function test_admin_can_add_a_product_to_the_catalog(): void
    {
        $this->actingAs(User::factory()->make(['role' => 'admin']))
            ->post(route('products.store'), [
                'nama_produk' => 'Monitor 27 Inch',
                'kategori' => 'Elektronik',
                'harga' => '2750000.00',
                'stok' => 8,
            ])
            ->assertRedirect(route('products.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('produk', [
            'nama_produk' => 'Monitor 27 Inch',
            'kategori' => 'Elektronik',
            'harga' => '2750000.00',
            'stok' => 8,
        ]);
    }

    public function test_product_creation_rejects_invalid_price_and_stock(): void
    {
        $this->actingAs(User::factory()->make(['role' => 'admin']))
            ->from(route('products.index'))
            ->post(route('products.store'), [
                'nama_produk' => 'Produk Salah',
                'kategori' => 'Elektronik',
                'harga' => 0,
                'stok' => -1,
            ])
            ->assertRedirect(route('products.index'))
            ->assertSessionHasErrors(['harga', 'stok']);

        $this->assertDatabaseMissing('produk', ['nama_produk' => 'Produk Salah']);
    }

    public function test_admin_can_delete_a_product_without_deleting_old_sale_details(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $productId = DB::table('produk')->insertGetId([
            'nama_produk' => 'Headset',
            'kategori' => 'Audio',
            'harga' => '500000.00',
            'stok' => 4,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $transaction = Transaction::create([
            'user_id' => $admin->id,
            'jenis' => 'pemasukan',
            'nominal' => '555000.00',
            'no_nota' => 'INV-20261031-000001',
            'subtotal' => '500000.00',
            'pajak' => '55000.00',
            'payment_method' => 'tunai',
            'paid_amount' => '600000.00',
            'change_amount' => '45000.00',
            'keterangan' => 'Penjualan POS INV-20261031-000001',
        ]);
        $transaction->items()->create([
            'product_id' => $productId,
            'nama_produk' => 'Headset',
            'kategori' => 'Audio',
            'qty' => 1,
            'harga_satuan' => '500000.00',
            'subtotal' => '500000.00',
        ]);

        $this->actingAs($admin)
            ->delete(route('products.destroy', $productId))
            ->assertRedirect(route('products.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('produk', ['id' => $productId]);
        $this->assertDatabaseHas('transaction_items', [
            'transaction_id' => $transaction->id,
            'product_id' => null,
            'nama_produk' => 'Headset',
            'harga_satuan' => '500000.00',
        ]);
    }

    public function test_deleting_a_missing_product_returns_not_found(): void
    {
        $this->actingAs(User::factory()->make(['role' => 'admin']))
            ->delete(route('products.destroy', 999))
            ->assertNotFound();
    }
}
