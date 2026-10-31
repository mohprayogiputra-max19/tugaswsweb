<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f8fafc">
    <title>Pengguna | {{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 antialiased">
    <header class="border-b border-slate-200 bg-white">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 font-semibold tracking-tight text-slate-900">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-600 text-lg text-white">P</span>
                <span>{{ config('app.name', 'Toko') }}<span class="block text-xs font-normal text-slate-500">Manajemen pengguna</span></span>
            </a>
            <a href="{{ route('admin.dashboard') }}" class="rounded-lg px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900">
                &larr; Dashboard Admin
            </a>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <section class="mb-8 overflow-hidden rounded-3xl bg-gradient-to-br from-indigo-700 via-indigo-600 to-violet-600 px-6 py-8 text-white shadow-lg shadow-indigo-200 sm:px-10 sm:py-10">
            <p class="mb-3 text-sm font-semibold uppercase tracking-[0.18em] text-indigo-100">Administrasi</p>
            <div class="flex flex-col justify-between gap-5 sm:flex-row sm:items-end">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight sm:text-4xl">Pengguna sistem</h1>
                    <p class="mt-3 max-w-xl text-sm leading-6 text-indigo-100 sm:text-base">Kelola akun, informasi kontak, dan hak akses pengguna.</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="rounded-2xl border border-white/20 bg-white/10 px-5 py-3 backdrop-blur">
                        <p class="text-2xl font-bold">{{ $users->count() }}</p>
                        <p class="text-xs text-indigo-100">pengguna terdaftar</p>
                    </div>
                    <a href="{{ route('users.create') }}" class="rounded-xl bg-white px-4 py-3 text-sm font-bold text-indigo-700 shadow-sm transition hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-indigo-600">
                        + Tambah Pengguna
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
        @if (isset($errors) && $errors->any())
            <div role="alert" class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <section aria-label="Daftar pengguna">
            <div class="mb-5">
                <h2 class="text-xl font-bold tracking-tight text-slate-900">Daftar Pengguna Sistem</h2>
                <p class="mt-1 text-sm text-slate-500">Informasi akun dan peran pengguna yang terdaftar.</p>
            </div>

            @if ($users->isNotEmpty())
                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[760px] text-left">
                            <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wider text-slate-500">
                                <tr>
                                    <th scope="col" class="px-5 py-4">ID</th>
                                    <th scope="col" class="px-5 py-4">Pengguna</th>
                                    <th scope="col" class="px-5 py-4">No. Telepon</th>
                                    <th scope="col" class="px-5 py-4">Role</th>
                                    <th scope="col" class="px-5 py-4 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($users as $user)
                                    <tr class="transition hover:bg-slate-50">
                                        <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-500">#{{ $user->id }}</td>
                                        <td class="px-5 py-4">
                                            <div class="flex items-center gap-3">
                                                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-indigo-100 font-bold text-indigo-700">{{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}</span>
                                                <div class="min-w-0">
                                                    <p class="truncate font-semibold text-slate-900">{{ $user->name }}</p>
                                                    <p class="truncate text-sm text-slate-500">{{ $user->email }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600">{{ $user->phone ?? '-' }}</td>
                                        <td class="whitespace-nowrap px-5 py-4">
                                            <span class="rounded-full {{ $user->role === 'admin' ? 'bg-violet-50 text-violet-700' : 'bg-indigo-50 text-indigo-700' }} px-3 py-1 text-xs font-semibold">{{ ucfirst($user->role) }}</span>
                                        </td>
                                        <td class="whitespace-nowrap px-5 py-4 text-right">
                                            <a href="{{ route('users.edit', ['user' => $user->id]) }}" class="rounded-lg px-3 py-2 text-sm font-semibold text-indigo-700 transition hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-indigo-500">Edit</a>
                                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="ml-1 inline" onsubmit="return confirm('Hapus pengguna ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="rounded-lg px-3 py-2 text-sm font-semibold text-rose-600 transition hover:bg-rose-50 focus:outline-none focus:ring-2 focus:ring-rose-500">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-2xl" aria-hidden="true">👥</div>
                    <h3 class="mt-4 text-lg font-bold text-slate-900">Belum ada pengguna</h3>
                    <p class="mx-auto mt-2 max-w-sm text-sm leading-6 text-slate-500">Tambahkan pengguna agar mereka dapat mengakses sistem sesuai perannya.</p>
                    <a href="{{ route('users.create') }}" class="mt-5 inline-flex rounded-xl bg-indigo-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Tambah Pengguna</a>
                </div>
            @endif
        </section>
    </main>
</body>
</html>
