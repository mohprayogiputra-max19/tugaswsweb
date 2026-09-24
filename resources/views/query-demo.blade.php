<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Query Builder Lab</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">
    <header class="bg-slate-950 text-white">
        <div class="mx-auto max-w-7xl px-6 py-10">
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-cyan-300">Laravel Database Lab</p>
            <div class="mt-3 flex flex-col justify-between gap-6 md:flex-row md:items-end">
                <div>
                    <h1 class="text-4xl font-black tracking-tight md:text-5xl">Query Builder Dashboard</h1>
                    <p class="mt-3 max-w-2xl text-slate-300">Membuktikan cara Laravel mengambil, menggabungkan, menyaring, dan merangkum data dari database.</p>
                </div>
                <a href="{{ route('query.demo') }}" class="inline-flex w-fit items-center rounded-lg border border-cyan-300 px-4 py-2 text-sm font-bold text-cyan-200 transition hover:bg-cyan-300 hover:text-slate-950">Lihat JSON mentah</a>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-7xl space-y-8 px-6 py-8">
        <section class="grid gap-4 md:grid-cols-3">
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <p class="text-sm font-semibold text-slate-500">Total users</p>
                <p class="mt-2 text-4xl font-black text-cyan-700">{{ $aggregates['total_users'] }}</p>
                <p class="mt-2 text-sm text-slate-500">Agregat <code>count()</code></p>
            </div>
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <p class="text-sm font-semibold text-slate-500">Gaji tertinggi</p>
                <p class="mt-2 text-4xl font-black text-emerald-700">{{ $aggregates['max_salary'] ?? '-' }}</p>
                <p class="mt-2 text-sm text-slate-500">Agregat <code>max()</code> dari employees</p>
            </div>
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <p class="text-sm font-semibold text-slate-500">Gaji terendah</p>
                <p class="mt-2 text-4xl font-black text-amber-700">{{ $aggregates['min_salary'] ?? '-' }}</p>
                <p class="mt-2 text-sm text-slate-500">Agregat <code>min()</code> dari employees</p>
            </div>
        </section>

        <section class="grid gap-6 lg:grid-cols-2">
            <article class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-widest text-cyan-700">Pluck</p>
                        <h2 class="mt-1 text-xl font-bold">Nama user</h2>
                    </div>
                    <span class="rounded-full bg-cyan-50 px-3 py-1 text-xs font-bold text-cyan-700">{{ $names->count() }} data</span>
                </div>
                <div class="mt-5 grid max-h-48 grid-cols-2 gap-2 overflow-auto text-sm text-slate-600 sm:grid-cols-3">
                    @foreach ($names as $name)
                        <span class="rounded-lg bg-slate-50 px-3 py-2">{{ $name }}</span>
                    @endforeach
                </div>
            </article>

            <article class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <p class="text-xs font-bold uppercase tracking-widest text-violet-700">Raw SQL</p>
                <h2 class="mt-1 text-xl font-bold">Jumlah user per role</h2>
                <p class="mt-2 text-sm text-slate-500"><code>selectRaw()</code> menghitung data lalu <code>groupBy()</code> mengelompokkannya.</p>
                <div class="mt-5 space-y-2">
                    @foreach ($raw_sql as $row)
                        <div class="flex items-center justify-between rounded-lg bg-violet-50 px-4 py-3">
                            <span class="font-semibold text-violet-900">{{ $row->role }}</span>
                            <span class="font-black text-violet-700">{{ $row->total_users }}</span>
                        </div>
                    @endforeach
                </div>
            </article>
        </section>

        <section class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <div class="flex flex-col justify-between gap-2 sm:flex-row sm:items-end">
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-slate-500">Pengurutan, limit, offset</p>
                    <h2 class="mt-1 text-xl font-bold">Potongan data user</h2>
                </div>
                <p class="text-sm text-slate-500">Halaman 2 dimulai setelah 10 data pertama.</p>
            </div>
            <div class="mt-5 overflow-x-auto">
                <table class="w-full min-w-[640px] text-left text-sm">
                    <thead class="border-b border-slate-200 text-xs uppercase tracking-wide text-slate-500">
                        <tr><th class="px-4 py-3">ID</th><th class="px-4 py-3">Nama</th><th class="px-4 py-3">Email</th><th class="px-4 py-3">Role</th><th class="px-4 py-3">Bagian</th></tr>
                    </thead>
                    <tbody>
                        @foreach ($top_ten as $user)
                            <tr class="border-b border-slate-100"><td class="px-4 py-3">{{ $user->id }}</td><td class="px-4 py-3 font-semibold">{{ $user->name }}</td><td class="px-4 py-3 text-slate-500">{{ $user->email }}</td><td class="px-4 py-3">{{ $user->role }}</td><td class="px-4 py-3"><span class="rounded-full bg-cyan-50 px-2 py-1 text-xs font-bold text-cyan-700">10 pertama</span></td></tr>
                        @endforeach
                        @foreach ($offset_page_two as $user)
                            <tr class="border-b border-slate-100 bg-slate-50/60"><td class="px-4 py-3">{{ $user->id }}</td><td class="px-4 py-3 font-semibold">{{ $user->name }}</td><td class="px-4 py-3 text-slate-500">{{ $user->email }}</td><td class="px-4 py-3">{{ $user->role }}</td><td class="px-4 py-3"><span class="rounded-full bg-amber-50 px-2 py-1 text-xs font-bold text-amber-700">offset 10</span></td></tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        <section class="grid gap-6 lg:grid-cols-2">
            <article class="rounded-2xl bg-slate-950 p-6 text-white shadow-sm">
                <p class="text-xs font-bold uppercase tracking-widest text-cyan-300">Join</p>
                <h2 class="mt-1 text-xl font-bold">User yang memiliki order</h2>
                <p class="mt-2 text-sm text-slate-400">Inner join hanya menampilkan pasangan data yang cocok di kedua tabel.</p>
                <div class="mt-5 space-y-2">
                    @forelse ($join as $order)
                        <div class="flex justify-between rounded-lg bg-white/10 px-4 py-3"><span>{{ $order->name }}</span><span class="font-bold">{{ $order->total_price }}</span></div>
                    @empty
                        <p class="rounded-lg bg-white/10 px-4 py-3 text-sm text-slate-300">Belum ada order yang cocok.</p>
                    @endforelse
                </div>
            </article>

            <article class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <p class="text-xs font-bold uppercase tracking-widest text-emerald-700">Subquery</p>
                <h2 class="mt-1 text-xl font-bold">Jumlah order per user</h2>
                <p class="mt-2 text-sm text-slate-500">Query kecil di dalam query utama menghitung order untuk setiap user.</p>
                <div class="mt-5 grid max-h-56 grid-cols-2 gap-2 overflow-auto sm:grid-cols-3">
                    @foreach ($subquery as $row)
                        <div class="rounded-lg bg-emerald-50 px-3 py-2"><p class="truncate text-sm font-semibold text-emerald-950">{{ $row->name }}</p><p class="text-xs text-emerald-700">{{ $row->order_count }} order</p></div>
                    @endforeach
                </div>
            </article>
        </section>

        <footer class="border-t border-slate-200 py-4 text-sm text-slate-500">Halaman ini membaca data saja. Endpoint JSON tersedia di <code>/query-demo</code>.</footer>
    </main>
</body>
</html>
