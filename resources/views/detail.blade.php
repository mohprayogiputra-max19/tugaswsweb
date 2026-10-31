<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f8fafc">
    <title>{{ $produk->nama_produk }} | {{ config('app.name', 'Laravel') }}</title>
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
                Dashboard
            </a>
        </div>
    </header>

    <main class="mx-auto max-w-5xl px-4 py-8 sm:px-6 sm:py-12 lg:px-8">
        <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 rounded-lg py-2 text-sm font-semibold text-indigo-700 transition hover:text-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
            <span aria-hidden="true">&larr;</span>
            Kembali ke katalog
        </a>

        <article class="mt-5 overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            <div class="bg-gradient-to-br from-indigo-700 via-indigo-600 to-violet-600 px-6 py-10 text-white sm:px-10 sm:py-14">
                <span class="inline-flex rounded-full border border-white/20 bg-white/10 px-3 py-1 text-xs font-semibold text-indigo-50">{{ $produk->kategori }}</span>
                <p class="mt-6 text-sm font-medium text-indigo-100">Detail produk #{{ $produk->id }}</p>
                <h1 class="mt-2 text-3xl font-bold tracking-tight sm:text-4xl">{{ $produk->nama_produk }}</h1>
                <p class="mt-4 text-sm text-indigo-100">Informasi lengkap mengenai harga dan ketersediaan produk.</p>
            </div>

            <div class="grid gap-8 p-6 sm:grid-cols-2 sm:p-10">
                <section>
                    <p class="text-sm font-medium text-slate-500">Harga</p>
                    <p class="mt-2 text-3xl font-extrabold tracking-tight text-indigo-700">Rp {{ number_format((float) $produk->harga, 0, ',', '.') }}</p>
                    <p class="mt-2 text-sm text-slate-500">Harga yang tertera dalam Rupiah.</p>
                </section>

                <section>
                    <p class="text-sm font-medium text-slate-500">Ketersediaan stok</p>
                    <div class="mt-3 flex items-center gap-3">
                        @if ($produk->stok > 0)
                            <span class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-100 text-emerald-700" aria-hidden="true">
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.704 5.29a1 1 0 0 1 .006 1.414l-7.5 7.563a1 1 0 0 1-1.42.003L3.29 9.77a1 1 0 0 1 1.42-1.408l3.79 3.819 6.79-6.846a1 1 0 0 1 1.414-.045Z" clip-rule="evenodd" />
                                </svg>
                            </span>
                            <div>
                                <p class="font-bold text-emerald-700">Tersedia</p>
                                <p class="text-sm text-slate-500">{{ $produk->stok }} unit siap dijual</p>
                            </div>
                        @else
                            <span class="flex h-10 w-10 items-center justify-center rounded-full bg-rose-100 text-xl font-bold text-rose-700" aria-hidden="true">&times;</span>
                            <div>
                                <p class="font-bold text-rose-700">Stok habis</p>
                                <p class="text-sm text-slate-500">Produk sedang tidak tersedia.</p>
                            </div>
                        @endif
                    </div>
                </section>

                <section class="border-t border-slate-100 pt-5 sm:col-span-2">
                    <p class="text-sm font-medium text-slate-500">Kategori</p>
                    <p class="mt-1 font-semibold text-slate-900">{{ $produk->kategori }}</p>
                </section>
            </div>
        </article>
    </main>
</body>
</html>
