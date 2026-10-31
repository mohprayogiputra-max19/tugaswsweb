<?php

namespace Tests\Feature;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PointOfSaleTest extends TestCase
{
    use RefreshDatabase;

    public function test_cashier_can_add_products_and_complete_a_sale(): void
    {
        $cashier = User::factory()->create(['role' => 'kasir']);
        $laptopId = $this->createProduct('Laptop', '12500000.00', 3);
        $mouseId = $this->createProduct('Mouse', '350000.00', 5);
        $keyboardId = $this->createProduct('Keyboard', '750000.00', 4);

        $this->actingAs($cashier)
            ->get(route('kasir.pos'))
            ->assertOk()
            ->assertSeeText('Laptop')
            ->assertSeeText('Pilih Produk');

        $this->post(route('kasir.cart.add'), [
            'product_id' => $laptopId,
            'quantity' => 1,
        ])->assertRedirect(route('kasir.pos'));

        $this->post(route('kasir.cart.add'), [
            'product_id' => $mouseId,
            'quantity' => 2,
        ])->assertRedirect(route('kasir.pos'));

        $this->post(route('kasir.cart.add'), [
            'product_id' => $keyboardId,
            'quantity' => 1,
        ])->assertRedirect(route('kasir.pos'));

        $response = $this->post(route('kasir.checkout'), [
            'paid_amount' => '16000000.00',
        ]);

        $sale = Transaction::with('items')->whereNotNull('no_nota')->firstOrFail();
        $response->assertRedirect(route('kasir.pos.receipt', $sale));

        $this->assertSame('pemasukan', $sale->jenis);
        $this->assertEquals('13950000.00', $sale->subtotal);
        $this->assertEquals('1534500.00', $sale->pajak);
        $this->assertEquals('15484500.00', $sale->nominal);
        $this->assertEquals('16000000.00', $sale->paid_amount);
        $this->assertEquals('515500.00', $sale->change_amount);
        $this->assertMatchesRegularExpression('/^INV-\d{8}-\d{6}$/', $sale->no_nota);
        $this->assertCount(3, $sale->items);
        $this->assertSame(2, $sale->items->firstWhere('product_id', $mouseId)->qty);
        $this->assertSame(2, (int) DB::table('produk')->where('id', $laptopId)->value('stok'));
        $this->assertSame(3, (int) DB::table('produk')->where('id', $mouseId)->value('stok'));
        $this->assertNull(session('pos_cart'));

        $this->get(route('kasir.pos.receipt', $sale))
            ->assertOk()
            ->assertSeeText($sale->no_nota)
            ->assertSeeText('Transaksi Berhasil')
            ->assertSeeText('Laptop')
            ->assertSeeText('Kembalian');

        $this->delete(route('transaksi.destroy', $sale->id))->assertForbidden();
        $this->assertDatabaseHas('transactions', ['id' => $sale->id, 'deleted_at' => null]);

        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin)
            ->delete(route('users.destroy', $cashier->id))
            ->assertRedirect(route('users.index'))
            ->assertSessionHasErrors('user');
        $this->assertDatabaseHas('users', ['id' => $cashier->id]);
        $this->assertDatabaseHas('transactions', ['id' => $sale->id, 'deleted_at' => null]);
    }

    public function test_checkout_refuses_insufficient_stock_without_partial_changes(): void
    {
        $cashier = User::factory()->create(['role' => 'kasir']);
        $productId = $this->createProduct('Keyboard', '750000.00', 2);

        $this->actingAs($cashier)->post(route('kasir.cart.add'), [
            'product_id' => $productId,
            'quantity' => 2,
        ]);

        DB::table('produk')->where('id', $productId)->update(['stok' => 1]);

        $this->from(route('kasir.pos'))
            ->post(route('kasir.checkout'), ['paid_amount' => '2000000'])
            ->assertRedirect(route('kasir.pos'))
            ->assertSessionHasErrors('cart');

        $this->assertSame(1, (int) DB::table('produk')->where('id', $productId)->value('stok'));
        $this->assertDatabaseCount('transactions', 0);
        $this->assertSame(2, session('pos_cart')[$productId]);
    }

    public function test_checkout_rejects_payment_below_total_and_keeps_cart(): void
    {
        $cashier = User::factory()->create(['role' => 'kasir']);
        $productId = $this->createProduct('Mouse', '100000.00', 2);

        $this->actingAs($cashier)->post(route('kasir.cart.add'), [
            'product_id' => $productId,
            'quantity' => 1,
        ]);

        $this->from(route('kasir.pos'))
            ->post(route('kasir.checkout'), ['paid_amount' => '100000'])
            ->assertRedirect(route('kasir.pos'))
            ->assertSessionHasErrors('paid_amount');

        $this->assertDatabaseCount('transactions', 0);
        $this->assertSame(2, (int) DB::table('produk')->where('id', $productId)->value('stok'));
        $this->assertSame(1, session('pos_cart')[$productId]);
    }

    public function test_cashier_can_cancel_cart_without_creating_a_sale(): void
    {
        $cashier = User::factory()->create(['role' => 'kasir']);
        $productId = $this->createProduct('Mouse', '100000.00', 2);

        $this->actingAs($cashier)->post(route('kasir.cart.add'), [
            'product_id' => $productId,
            'quantity' => 1,
        ]);

        $this->delete(route('kasir.cart.clear'))
            ->assertRedirect(route('kasir.pos'));

        $this->assertNull(session('pos_cart'));
        $this->assertDatabaseCount('transactions', 0);
        $this->assertSame(2, (int) DB::table('produk')->where('id', $productId)->value('stok'));
    }

    public function test_cashier_can_change_quantity_and_remove_a_cart_item(): void
    {
        $cashier = User::factory()->create(['role' => 'kasir']);
        $productId = $this->createProduct('Mouse', '100000.00', 4);

        $this->actingAs($cashier)->post(route('kasir.cart.add'), [
            'product_id' => $productId,
            'quantity' => 1,
        ]);

        $this->patch(route('kasir.cart.update', $productId), ['quantity' => 3])
            ->assertRedirect(route('kasir.pos'));
        $this->assertSame(3, session('pos_cart')[$productId]);

        $this->delete(route('kasir.cart.remove', $productId))
            ->assertRedirect(route('kasir.pos'));
        $this->assertSame([], session('pos_cart'));
    }

    public function test_cashier_cannot_add_more_units_than_stock(): void
    {
        $cashier = User::factory()->create(['role' => 'kasir']);
        $productId = $this->createProduct('Mouse', '100000.00', 1);

        $this->actingAs($cashier)->post(route('kasir.cart.add'), [
            'product_id' => $productId,
            'quantity' => 2,
        ])->assertSessionHasErrors('quantity');

        $this->assertNull(session('pos_cart'));
    }

    private function createProduct(string $name, string $price, int $stock): int
    {
        return DB::table('produk')->insertGetId([
            'nama_produk' => $name,
            'kategori' => 'Elektronik',
            'harga' => $price,
            'stok' => $stock,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
