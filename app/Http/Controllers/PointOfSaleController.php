<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PointOfSaleController extends Controller
{
    private const TAX_PERCENT = 11;

    public function index(Request $request): View
    {
        $products = DB::table('produk')
            ->select('id', 'nama_produk', 'kategori', 'harga', 'stok')
            ->orderBy('nama_produk')
            ->get();

        [$cartItems, $subtotalCents] = $this->cartContents($request);
        $taxCents = $this->calculateTax($subtotalCents);

        return view('kasir.pos', [
            'products' => $products,
            'cartItems' => $cartItems,
            'subtotalCents' => $subtotalCents,
            'taxCents' => $taxCents,
            'totalCents' => $subtotalCents + $taxCents,
            'formatRupiah' => fn (int $cents): string => $this->formatRupiah($cents),
        ]);
    }

    public function addToCart(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:produk,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:10000'],
        ]);

        $product = DB::table('produk')->where('id', $validated['product_id'])->first();
        $cart = $request->session()->get('pos_cart', []);
        $currentQuantity = (int) ($cart[$product->id] ?? 0);
        $newQuantity = $currentQuantity + (int) $validated['quantity'];

        if ($newQuantity > $product->stok || $newQuantity > 10000) {
            throw ValidationException::withMessages([
                'quantity' => "Jumlah {$product->nama_produk} melebihi stok yang dapat dijual.",
            ]);
        }

        $cart[$product->id] = $newQuantity;
        $request->session()->put('pos_cart', $cart);

        return redirect()->route('kasir.pos')->with('success', 'Produk berhasil ditambahkan ke keranjang.');
    }

    public function updateCart(Request $request, int $productId): RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:0', 'max:10000'],
        ]);

        $product = DB::table('produk')->where('id', $productId)->first();
        abort_if($product === null, 404);

        $quantity = (int) $validated['quantity'];

        if ($quantity > $product->stok) {
            throw ValidationException::withMessages([
                'quantity' => "Stok {$product->nama_produk} hanya {$product->stok} unit.",
            ]);
        }

        $cart = $request->session()->get('pos_cart', []);

        if ($quantity === 0) {
            unset($cart[$productId]);
        } else {
            $cart[$productId] = $quantity;
        }

        $request->session()->put('pos_cart', $cart);

        return redirect()->route('kasir.pos')->with('success', 'Keranjang berhasil diperbarui.');
    }

    public function removeFromCart(Request $request, int $productId): RedirectResponse
    {
        $cart = $request->session()->get('pos_cart', []);
        unset($cart[$productId]);
        $request->session()->put('pos_cart', $cart);

        return redirect()->route('kasir.pos')->with('success', 'Produk dihapus dari keranjang.');
    }

    public function clearCart(Request $request): RedirectResponse
    {
        $request->session()->forget('pos_cart');

        return redirect()->route('kasir.pos')->with('success', 'Transaksi dibatalkan dan keranjang dikosongkan.');
    }

    public function checkout(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'paid_amount' => ['required', 'numeric', 'decimal:0,2', 'min:0.01', 'max:9999999999.99'],
        ]);

        $cart = $request->session()->get('pos_cart', []);
        $quantities = collect($cart)
            ->map(fn ($quantity) => (int) $quantity)
            ->filter(fn (int $quantity) => $quantity > 0);

        if ($quantities->isEmpty()) {
            throw ValidationException::withMessages([
                'cart' => 'Keranjang kosong. Tambahkan produk sebelum memproses pembayaran.',
            ]);
        }

        $paidAmountCents = (int) round((float) $validated['paid_amount'] * 100);

        $sale = DB::transaction(function () use ($quantities, $paidAmountCents): Transaction {
            $products = DB::table('produk')
                ->whereIn('id', $quantities->keys())
                ->orderBy('id')
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            if ($products->count() !== $quantities->count()) {
                throw ValidationException::withMessages([
                    'cart' => 'Ada produk di keranjang yang sudah tidak tersedia. Periksa kembali keranjang.',
                ]);
            }

            $subtotalCents = 0;
            $items = [];

            foreach ($quantities as $productId => $quantity) {
                $product = $products->get($productId);

                if ($quantity > $product->stok) {
                    throw ValidationException::withMessages([
                        'cart' => "Stok {$product->nama_produk} tidak mencukupi. Sisa stok {$product->stok} unit.",
                    ]);
                }

                $unitPriceCents = $this->toCents($product->harga);
                if ($unitPriceCents <= 0) {
                    throw ValidationException::withMessages([
                        'cart' => "Harga {$product->nama_produk} harus lebih dari nol.",
                    ]);
                }

                $lineSubtotalCents = $unitPriceCents * $quantity;
                $subtotalCents += $lineSubtotalCents;
                if ($subtotalCents > 999999999999) {
                    throw ValidationException::withMessages([
                        'cart' => 'Subtotal transaksi melebihi batas nilai yang dapat disimpan.',
                    ]);
                }

                $items[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'unit_price_cents' => $unitPriceCents,
                    'subtotal_cents' => $lineSubtotalCents,
                ];
            }

            $taxCents = $this->calculateTax($subtotalCents);
            $totalCents = $subtotalCents + $taxCents;

            if ($totalCents > 999999999999) {
                throw ValidationException::withMessages([
                    'cart' => 'Total transaksi melebihi batas nilai yang dapat disimpan.',
                ]);
            }

            if ($paidAmountCents < $totalCents) {
                throw ValidationException::withMessages([
                    'paid_amount' => 'Jumlah uang yang diterima kurang dari total pembayaran.',
                ]);
            }

            $transaction = Transaction::create([
                'user_id' => auth()->id(),
                'jenis' => 'pemasukan',
                'nominal' => $this->fromCents($totalCents),
                'subtotal' => $this->fromCents($subtotalCents),
                'pajak' => $this->fromCents($taxCents),
                'payment_method' => 'tunai',
                'paid_amount' => $this->fromCents($paidAmountCents),
                'change_amount' => $this->fromCents($paidAmountCents - $totalCents),
                'keterangan' => 'Penjualan POS',
            ]);

            $invoice = sprintf('INV-%s-%06d', now()->format('Ymd'), $transaction->id);
            $transaction->update([
                'no_nota' => $invoice,
                'keterangan' => "Penjualan POS {$invoice}",
            ]);

            foreach ($items as $item) {
                $product = $item['product'];
                $transaction->items()->create([
                    'product_id' => $product->id,
                    'nama_produk' => $product->nama_produk,
                    'kategori' => $product->kategori,
                    'qty' => $item['quantity'],
                    'harga_satuan' => $this->fromCents($item['unit_price_cents']),
                    'subtotal' => $this->fromCents($item['subtotal_cents']),
                ]);

                DB::table('produk')
                    ->where('id', $product->id)
                    ->decrement('stok', $item['quantity']);
            }

            return $transaction;
        });

        $request->session()->forget('pos_cart');

        return redirect()->route('kasir.pos.receipt', $sale);
    }

    public function receipt(Transaction $transaction): View
    {
        abort_if($transaction->no_nota === null, 404);

        $transaction->load(['items', 'user']);

        return view('kasir.receipt', [
            'transaction' => $transaction,
            'formatRupiah' => fn ($amount): string => $this->formatRupiah($this->toCents($amount)),
        ]);
    }

    /**
     * @return array{0: Collection<int, array{product: object, quantity: int, unit_price_cents: int, subtotal_cents: int}>, 1: int}
     */
    private function cartContents(Request $request): array
    {
        $cart = $request->session()->get('pos_cart', []);
        $quantities = collect($cart)
            ->map(fn ($quantity) => (int) $quantity)
            ->filter(fn (int $quantity) => $quantity > 0);

        $products = $quantities->isEmpty()
            ? collect()
            : DB::table('produk')->whereIn('id', $quantities->keys())->get()->keyBy('id');

        $items = collect();
        $subtotalCents = 0;

        foreach ($quantities as $productId => $quantity) {
            $product = $products->get($productId);

            if ($product === null) {
                continue;
            }

            $unitPriceCents = $this->toCents($product->harga);
            $lineSubtotalCents = $unitPriceCents * $quantity;
            $subtotalCents += $lineSubtotalCents;
            $items->push([
                'product' => $product,
                'quantity' => $quantity,
                'unit_price_cents' => $unitPriceCents,
                'subtotal_cents' => $lineSubtotalCents,
            ]);
        }

        return [$items, $subtotalCents];
    }

    private function calculateTax(int $subtotalCents): int
    {
        $wholeHundreds = intdiv($subtotalCents, 100);
        $remainingCents = $subtotalCents % 100;

        return ($wholeHundreds * self::TAX_PERCENT)
            + intdiv(($remainingCents * self::TAX_PERCENT) + 50, 100);
    }

    private function toCents(string|int|float $amount): int
    {
        $amount = (string) $amount;
        [$whole, $fraction] = array_pad(explode('.', $amount, 2), 2, '');

        return ((int) $whole * 100)
            + (int) str_pad(substr($fraction, 0, 2), 2, '0', STR_PAD_RIGHT);
    }

    private function fromCents(int $cents): string
    {
        return intdiv($cents, 100).'.'.str_pad((string) ($cents % 100), 2, '0', STR_PAD_LEFT);
    }

    private function formatRupiah(int $cents): string
    {
        $amount = 'Rp '.number_format(intdiv($cents, 100), 0, ',', '.');
        $fraction = $cents % 100;

        return $fraction === 0
            ? $amount
            : $amount.','.str_pad((string) $fraction, 2, '0', STR_PAD_LEFT);
    }
}
