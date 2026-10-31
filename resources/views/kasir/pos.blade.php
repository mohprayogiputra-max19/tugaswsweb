<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f8fafc">
    <title>Kasir POS | {{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 antialiased">
    <header class="border-b border-slate-200 bg-white">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 font-semibold tracking-tight text-slate-900">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-600 text-lg text-white">P</span>
                <span>{{ config('app.name', 'Toko') }}<span class="block text-xs font-normal text-slate-500">Point of Sale</span></span>
            </a>
            <div class="text-right">
                <p class="text-sm font-semibold text-slate-800">{{ auth()->user()->name }}</p>
                <p class="text-xs text-slate-500">{{ now()->format('d M Y, H:i') }}</p>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        @if (session('success'))
            <div role="status" class="mb-6 flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                <span class="font-bold" aria-hidden="true">✓</span>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if ($errors->any())
            <div role="alert" class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
                <p class="font-semibold">Transaksi belum dapat diproses:</p>
                <ul class="mt-2 list-inside list-disc space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mb-6">
            <p class="text-sm font-semibold uppercase tracking-[0.16em] text-indigo-600">Kasir</p>
            <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-900">Point of Sale</h1>
            <p class="mt-2 text-sm text-slate-500">Pilih produk, atur jumlah, lalu catat pembayaran tunai untuk menyelesaikan penjualan.</p>
        </div>

        <div class="grid items-start gap-6 lg:grid-cols-[minmax(0,1fr)_minmax(360px,0.8fr)]">
            <section aria-label="Pilih produk">
                <div class="mb-4 flex items-end justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-bold tracking-tight text-slate-900">Pilih Produk</h2>
                        <p class="mt-1 text-sm text-slate-500">{{ $products->count() }} produk terdaftar</p>
                    </div>
                    <a href="{{ route('transaksi.index') }}" class="text-sm font-semibold text-indigo-700 hover:text-indigo-900">Riwayat transaksi &rarr;</a>
                </div>

                @if ($products->isNotEmpty())
                    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                        @foreach ($products as $product)
                            <article class="flex flex-col rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-indigo-200 hover:shadow-md">
                                <span class="w-fit rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-700">{{ $product->kategori }}</span>
                                <h3 class="mt-4 font-bold text-slate-900">{{ $product->nama_produk }}</h3>
                                <p class="mt-2 text-lg font-extrabold text-indigo-700">{{ $formatRupiah((int) round((float) $product->harga * 100)) }}</p>
                                <p class="mt-1 text-xs {{ $product->stok > 0 ? 'text-slate-500' : 'font-semibold text-rose-600' }}">
                                    {{ $product->stok > 0 ? "Stok: {$product->stok}" : 'Stok habis' }}
                                </p>
                                <form action="{{ route('kasir.cart.add') }}" method="POST" class="mt-5">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" @disabled($product->stok < 1) class="w-full rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:bg-slate-300">
                                        {{ $product->stok > 0 ? 'Tambah ke keranjang' : 'Stok habis' }}
                                    </button>
                                </form>
                            </article>
                        @endforeach
                    </div>
                @else
                    <div class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-14 text-center">
                        <h3 class="text-lg font-bold text-slate-900">Belum ada produk</h3>
                        <p class="mt-2 text-sm text-slate-500">Tambahkan produk dan stok terlebih dahulu sebelum melakukan penjualan.</p>
                    </div>
                @endif
            </section>

            <aside class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6 lg:sticky lg:top-6">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-bold tracking-tight text-slate-900">Keranjang Belanja</h2>
                        <p class="mt-1 text-sm text-slate-500">{{ $cartItems->sum('quantity') }} barang</p>
                    </div>
                    @if ($cartItems->isNotEmpty())
                        <form action="{{ route('kasir.cart.clear') }}" method="POST" onsubmit="return confirm('Batalkan transaksi dan kosongkan keranjang?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="rounded-lg px-3 py-2 text-xs font-semibold text-rose-600 transition hover:bg-rose-50 focus:outline-none focus:ring-2 focus:ring-rose-500">Batalkan</button>
                        </form>
                    @endif
                </div>

                @if ($cartItems->isNotEmpty())
                    <div class="mt-5 divide-y divide-slate-100">
                        @foreach ($cartItems as $item)
                            <div class="py-4 first:pt-0">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0">
                                        <h3 class="truncate text-sm font-semibold text-slate-900">{{ $item['product']->nama_produk }}</h3>
                                        <p class="mt-1 text-xs text-slate-500">{{ $formatRupiah($item['unit_price_cents']) }} / unit</p>
                                    </div>
                                    <p class="whitespace-nowrap text-sm font-bold text-slate-900">{{ $formatRupiah($item['subtotal_cents']) }}</p>
                                </div>
                                <div class="mt-3 flex items-center justify-between gap-3">
                                    <form action="{{ route('kasir.cart.update', $item['product']->id) }}" method="POST" class="flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <label for="quantity-{{ $item['product']->id }}" class="text-xs font-medium text-slate-500">Qty</label>
                                        <input id="quantity-{{ $item['product']->id }}" name="quantity" type="number" min="0" max="{{ max((int) $item['product']->stok, $item['quantity']) }}" value="{{ $item['quantity'] }}" required class="w-20 rounded-lg border-slate-300 px-2 py-1.5 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <button type="submit" class="rounded-lg border border-slate-200 px-2.5 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-50">Perbarui</button>
                                    </form>
                                    <form action="{{ route('kasir.cart.remove', $item['product']->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs font-semibold text-rose-600 hover:text-rose-800">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="my-6 rounded-2xl bg-slate-50 px-4 py-8 text-center">
                        <p class="font-semibold text-slate-700">Keranjang masih kosong</p>
                        <p class="mt-1 text-sm text-slate-500">Pilih produk untuk memulai transaksi.</p>
                    </div>
                @endif

                <div class="space-y-3 border-t border-slate-200 pt-5">
                    <div class="flex justify-between text-sm text-slate-600">
                        <span>Subtotal</span>
                        <span class="font-semibold text-slate-900">{{ $formatRupiah($subtotalCents) }}</span>
                    </div>
                    <div class="flex justify-between text-sm text-slate-600">
                        <span>PPN (11%)</span>
                        <span class="font-semibold text-slate-900">{{ $formatRupiah($taxCents) }}</span>
                    </div>
                    <div class="flex justify-between rounded-2xl bg-indigo-50 px-4 py-4">
                        <span class="font-bold text-slate-900">Total</span>
                        <span class="text-lg font-extrabold text-indigo-700">{{ $formatRupiah($totalCents) }}</span>
                    </div>
                </div>

                <form action="{{ route('kasir.checkout') }}" method="POST" class="mt-5 space-y-3">
                    @csrf
                    <div>
                        <label for="paid_amount" class="block text-sm font-semibold text-slate-700">Uang diterima (tunai)</label>
                        <div class="mt-2 flex overflow-hidden rounded-xl border border-slate-300 shadow-sm focus-within:border-indigo-500 focus-within:ring-1 focus-within:ring-indigo-500">
                            <span class="inline-flex items-center border-r border-slate-200 bg-slate-50 px-4 text-sm font-semibold text-slate-500">Rp</span>
                            <input id="paid_amount" name="paid_amount" type="number" min="{{ number_format($totalCents / 100, 2, '.', '') }}" step="0.01" value="{{ old('paid_amount', number_format($totalCents / 100, 2, '.', '')) }}" required @disabled($cartItems->isEmpty()) class="block w-full border-0 px-4 py-3 text-sm focus:ring-0 disabled:bg-slate-100">
                        </div>
                        @error('paid_amount') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                    </div>
                    <p class="text-xs leading-5 text-slate-500">Pembayaran tunai dicatat di sistem. Kembalian dihitung otomatis setelah transaksi berhasil.</p>
                    <button type="submit" @disabled($cartItems->isEmpty()) class="w-full rounded-xl bg-indigo-600 px-4 py-3.5 text-base font-bold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:bg-slate-300">
                        Bayar & Selesaikan Transaksi
                    </button>
                </form>
            </aside>
        </div>
    </main>
</body>
</html>
