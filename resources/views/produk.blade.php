<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f8fafc">
    <title>Katalog Produk | {{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 antialiased">
    <header class="border-b border-slate-200 bg-white">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 font-semibold tracking-tight text-slate-900">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-600 text-lg text-white">P</span>
                <span>{{ config('app.name', 'Toko') }}<span class="block text-xs font-normal text-slate-500">Manajemen produk</span></span>
            </a>
            <a href="{{ route('admin.dashboard') }}" class="rounded-lg px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900">
                Kembali ke dashboard
            </a>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <section class="mb-8 overflow-hidden rounded-3xl bg-gradient-to-br from-indigo-700 via-indigo-600 to-violet-600 px-6 py-8 text-white shadow-lg shadow-indigo-200 sm:px-10 sm:py-10">
            <p class="mb-3 text-sm font-semibold uppercase tracking-[0.18em] text-indigo-100">Katalog {{ $kategori }}</p>
            <div class="flex flex-col justify-between gap-5 sm:flex-row sm:items-end">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight sm:text-4xl">Kelola produk toko</h1>
                    <p class="mt-3 max-w-xl text-sm leading-6 text-indigo-100 sm:text-base">Tambahkan produk baru dan kelola katalog serta stok yang tersedia.</p>
                </div>
                <div class="w-fit rounded-2xl border border-white/20 bg-white/10 px-5 py-3 backdrop-blur">
                    <p class="text-2xl font-bold">{{ $daftarProduk->count() }}</p>
                    <p class="text-xs text-indigo-100">produk tersedia</p>
                </div>
            </div>
        </section>

        @if (session('success'))
            <div role="status" class="mb-6 flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                <span class="font-bold" aria-hidden="true">✓</span>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if (isset($errors) && $errors->any())
            <div role="alert" class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
                <p class="font-semibold">Produk belum dapat disimpan:</p>
                <ul class="mt-2 list-inside list-disc space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section aria-labelledby="add-product-heading" class="mb-10 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            <div class="mb-6">
                <p class="text-sm font-semibold uppercase tracking-[0.16em] text-indigo-600">Katalog</p>
                <h2 id="add-product-heading" class="mt-2 text-xl font-bold tracking-tight text-slate-900">Tambah produk baru</h2>
                <p class="mt-1 text-sm text-slate-500">Masukkan informasi produk dan stok awal.</p>
            </div>

            <form action="{{ route('products.store') }}" method="POST" class="grid gap-5 sm:grid-cols-2">
                @csrf
                <div>
                    <label for="nama_produk" class="block text-sm font-semibold text-slate-700">Nama produk</label>
                    <input id="nama_produk" name="nama_produk" type="text" value="{{ old('nama_produk') }}" maxlength="255" required class="mt-2 block w-full rounded-xl border-slate-300 px-4 py-3 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('nama_produk') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="kategori" class="block text-sm font-semibold text-slate-700">Kategori</label>
                    <input id="kategori" name="kategori" type="text" value="{{ old('kategori') }}" maxlength="255" required class="mt-2 block w-full rounded-xl border-slate-300 px-4 py-3 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('kategori') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="harga" class="block text-sm font-semibold text-slate-700">Harga</label>
                    <div class="mt-2 flex overflow-hidden rounded-xl border border-slate-300 shadow-sm focus-within:border-indigo-500 focus-within:ring-1 focus-within:ring-indigo-500">
                        <span class="inline-flex items-center border-r border-slate-200 bg-slate-50 px-4 text-sm font-semibold text-slate-500">Rp</span>
                        <input id="harga" name="harga" type="number" min="0.01" max="9999999999.99" step="0.01" value="{{ old('harga') }}" required class="block w-full border-0 px-4 py-3 text-sm focus:ring-0">
                    </div>
                    @error('harga') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="stok" class="block text-sm font-semibold text-slate-700">Stok awal</label>
                    <input id="stok" name="stok" type="number" min="0" max="4294967295" step="1" value="{{ old('stok', 0) }}" required class="mt-2 block w-full rounded-xl border-slate-300 px-4 py-3 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('stok') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                </div>
                <div class="sm:col-span-2 sm:flex sm:justify-end">
                    <button type="submit" class="inline-flex w-full justify-center rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto">Simpan Produk</button>
                </div>
            </form>
        </section>

        <section aria-label="Daftar produk">
            <div class="mb-5 flex items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl font-bold tracking-tight text-slate-900">Daftar produk</h2>
                    <p class="mt-1 text-sm text-slate-500">Lihat informasi produk atau hapus produk yang tidak lagi dijual.</p>
                </div>
            </div>

            @if ($daftarProduk->isNotEmpty())
                <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
                    @foreach ($daftarProduk as $item)
                        <article class="group flex flex-col rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-1 hover:border-indigo-200 hover:shadow-lg">
                            <div class="mb-5 flex items-start justify-between gap-3">
                                <span class="inline-flex rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-700">{{ $item->kategori }}</span>
                                <span class="text-xs font-medium text-slate-400">ID #{{ $item->id }}</span>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900">{{ $item->nama_produk }}</h3>
                            <p class="mt-2 text-sm text-slate-500">Harga produk</p>
                            <p class="mt-1 text-2xl font-extrabold tracking-tight text-indigo-700">Rp {{ number_format((float) $item->harga, 0, ',', '.') }}</p>

                            <div class="mt-5 flex items-center justify-between border-t border-slate-100 pt-4">
                                <span class="text-sm text-slate-500">Stok</span>
                                @if ($item->stok > 0)
                                    <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">{{ $item->stok }} tersedia</span>
                                @else
                                    <span class="rounded-full bg-rose-50 px-3 py-1 text-xs font-semibold text-rose-700">Stok habis</span>
                                @endif
                            </div>

                            <a href="{{ route('products.show', $item->id) }}" class="mt-5 inline-flex w-full items-center justify-center rounded-xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                Lihat detail
                                <span class="ml-2 transition group-hover:translate-x-1" aria-hidden="true">&rarr;</span>
                            </a>
                            <form action="{{ route('products.destroy', $item->id) }}" method="POST" class="mt-2" onsubmit="return confirm('Hapus produk ini? Rincian pada nota penjualan lama tetap tersimpan.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full rounded-xl border border-rose-200 px-4 py-2.5 text-sm font-semibold text-rose-600 transition hover:bg-rose-50 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2">Hapus produk</button>
                            </form>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-2xl" aria-hidden="true">📦</div>
                    <h3 class="mt-4 text-lg font-bold text-slate-900">Belum ada produk</h3>
                    <p class="mx-auto mt-2 max-w-sm text-sm leading-6 text-slate-500">Produk yang tersedia akan muncul di halaman ini setelah ditambahkan.</p>
                </div>
            @endif
        </section>
    </main>
</body>
</html>
