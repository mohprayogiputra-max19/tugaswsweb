<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Transaksi | {{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 antialiased">
    <header class="border-b border-slate-200 bg-white">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 font-semibold tracking-tight text-slate-900">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-600 text-lg text-white">P</span>
                <span>{{ config('app.name', 'Toko') }}<span class="block text-xs font-normal text-slate-500">Manajemen transaksi</span></span>
            </a>
            <a href="{{ route('transaksi.index') }}" class="rounded-lg px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900">&larr; Daftar transaksi</a>
        </div>
    </header>

    <main class="mx-auto max-w-3xl px-4 py-8 sm:px-6 sm:py-12">
        <div class="mb-6">
            <p class="text-sm font-semibold uppercase tracking-[0.16em] text-indigo-600">Perbarui pencatatan</p>
            <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-900">Edit Transaksi #{{ $transaksi->id }}</h1>
            <p class="mt-2 text-sm text-slate-500">Ubah informasi transaksi lalu simpan perubahan.</p>
        </div>

        <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            @include('transaksi._form', [
                'transaksi' => $transaksi,
                'users' => $users,
                'formAction' => route('transaksi.update', $transaksi->id),
                'submitLabel' => 'Simpan Perubahan',
            ])
        </section>
    </main>
</body>
</html>
