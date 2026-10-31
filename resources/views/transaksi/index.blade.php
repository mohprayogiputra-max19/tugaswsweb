<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f8fafc">
    <title>Transaksi | {{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 antialiased">
    <header class="border-b border-slate-200 bg-white">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 font-semibold tracking-tight text-slate-900">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-600 text-lg text-white">P</span>
                <span>{{ config('app.name', 'Toko') }}<span class="block text-xs font-normal text-slate-500">Manajemen transaksi</span></span>
            </a>
            <a href="{{ route('dashboard') }}" class="rounded-lg px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900">&larr; Dashboard</a>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <section class="mb-8 overflow-hidden rounded-3xl bg-gradient-to-br from-indigo-700 via-indigo-600 to-violet-600 px-6 py-8 text-white shadow-lg shadow-indigo-200 sm:px-10 sm:py-10">
            <p class="mb-3 text-sm font-semibold uppercase tracking-[0.18em] text-indigo-100">Keuangan</p>
            <div class="flex flex-col justify-between gap-5 sm:flex-row sm:items-end">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight sm:text-4xl">Daftar Transaksi</h1>
                    <p class="mt-3 max-w-xl text-sm leading-6 text-indigo-100 sm:text-base">Pantau pemasukan dan pengeluaran dengan mudah.</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="rounded-2xl border border-white/20 bg-white/10 px-5 py-3 backdrop-blur">
                        <p class="text-2xl font-bold">{{ $transaksi->count() }}</p>
                        <p class="text-xs text-indigo-100">transaksi tercatat</p>
                    </div>
                    <a href="{{ route('transaksi.create') }}" class="rounded-xl bg-white px-4 py-3 text-sm font-bold text-indigo-700 shadow-sm transition hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-indigo-600">
                        + Tambah Transaksi
                    </a>
                </div>
            </div>
        </section>

        @if (session('success'))
            <div role="status" class="mb-6 flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                <span class="font-bold" aria-hidden="true">✓</span>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if ($transaksi->isNotEmpty())
            <section aria-label="Ringkasan transaksi" class="mb-8 grid gap-4 sm:grid-cols-3">
                <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-slate-500">Total pemasukan</p>
                    <p class="mt-2 text-2xl font-bold tracking-tight text-emerald-700">Rp {{ number_format((float) $transaksi->where('jenis', 'pemasukan')->sum('nominal'), 0, ',', '.') }}</p>
                </article>
                <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-slate-500">Total pengeluaran</p>
                    <p class="mt-2 text-2xl font-bold tracking-tight text-rose-700">Rp {{ number_format((float) $transaksi->where('jenis', 'pengeluaran')->sum('nominal'), 0, ',', '.') }}</p>
                </article>
                <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-slate-500">Jumlah transaksi</p>
                    <p class="mt-2 text-2xl font-bold tracking-tight text-indigo-700">{{ $transaksi->count() }} <span class="text-base font-medium text-slate-500">transaksi</span></p>
                </article>
            </section>
        @endif

        <section aria-label="Riwayat transaksi">
            <div class="mb-5">
                <h2 class="text-xl font-bold tracking-tight text-slate-900">Riwayat transaksi</h2>
                <p class="mt-1 text-sm text-slate-500">Daftar transaksi terbaru beserta pengguna dan keterangannya.</p>
            </div>

            @if ($transaksi->isNotEmpty())
                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[900px] text-left">
                            <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wider text-slate-500">
                                <tr>
                                    <th scope="col" class="px-5 py-4">ID</th>
                                    <th scope="col" class="px-5 py-4">Pengguna</th>
                                    <th scope="col" class="px-5 py-4">Jenis</th>
                                    <th scope="col" class="px-5 py-4">Nominal</th>
                                    <th scope="col" class="px-5 py-4">Keterangan</th>
                                    <th scope="col" class="px-5 py-4">Tanggal</th>
                                    <th scope="col" class="px-5 py-4 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($transaksi as $item)
                                    <tr class="transition hover:bg-slate-50">
                                        <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-500">#{{ $item->id }}</td>
                                        <td class="whitespace-nowrap px-5 py-4 font-medium text-slate-800">{{ $item->user?->name ?? '-' }}</td>
                                        <td class="whitespace-nowrap px-5 py-4">
                                            <span class="rounded-full {{ $item->jenis === 'pemasukan' ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }} px-3 py-1 text-xs font-semibold">{{ ucfirst($item->jenis) }}</span>
                                        </td>
                                        <td class="whitespace-nowrap px-5 py-4 font-semibold {{ $item->jenis === 'pemasukan' ? 'text-emerald-700' : 'text-rose-700' }}">Rp {{ number_format((float) $item->nominal, 0, ',', '.') }}</td>
                                        <td class="max-w-xs truncate px-5 py-4 text-sm text-slate-600" title="{{ $item->keterangan }}">{{ $item->keterangan ?: '-' }}</td>
                                        <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-500">{{ $item->created_at?->format('d-m-Y H:i') ?? '-' }}</td>
                                        <td class="whitespace-nowrap px-5 py-4 text-right">
                                            @if ($item->no_nota)
                                                <a href="{{ route('kasir.pos.receipt', $item->id) }}" class="rounded-lg px-3 py-2 text-sm font-semibold text-indigo-700 transition hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-indigo-500">Lihat nota</a>
                                            @else
                                                <a href="{{ route('transaksi.edit', $item->id) }}" class="rounded-lg px-3 py-2 text-sm font-semibold text-indigo-700 transition hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-indigo-500">Edit</a>
                                                <form action="{{ route('transaksi.destroy', $item->id) }}" method="POST" class="ml-1 inline" onsubmit="return confirm('Hapus transaksi ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="rounded-lg px-3 py-2 text-sm font-semibold text-rose-600 transition hover:bg-rose-50 focus:outline-none focus:ring-2 focus:ring-rose-500">Hapus</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-2xl" aria-hidden="true">🧾</div>
                    <h3 class="mt-4 text-lg font-bold text-slate-900">Belum ada transaksi</h3>
                    <p class="mx-auto mt-2 max-w-sm text-sm leading-6 text-slate-500">Transaksi yang dibuat akan muncul di halaman ini.</p>
                    <a href="{{ route('transaksi.create') }}" class="mt-5 inline-flex rounded-xl bg-indigo-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Tambah Transaksi</a>
                </div>
            @endif
        </section>
    </main>
</body>
</html>
