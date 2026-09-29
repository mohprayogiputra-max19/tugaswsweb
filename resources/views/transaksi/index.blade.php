<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Transaksi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <main class="mx-auto max-w-5xl rounded-lg bg-white p-6 shadow-md">
        <div class="mb-6">
            <a href="{{ url('/') }}" class="text-sm font-semibold text-gray-600 hover:text-blue-600">&larr; Kembali</a>
        </div>

        <div class="mb-4 flex items-center justify-between gap-4">
            <h1 class="text-2xl font-bold text-gray-800">Daftar Transaksi</h1>
            <a href="{{ route('transaksi.create') }}" class="rounded bg-blue-600 px-4 py-2 font-semibold text-white hover:bg-blue-700">Tambah Transaksi</a>
        </div>

        @if (session('success'))
            <div class="mb-4 rounded border border-green-200 bg-green-100 px-4 py-3 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left">
                <thead>
                    <tr class="bg-blue-600 text-white">
                        <th class="border p-3">ID</th>
                        <th class="border p-3">Pengguna</th>
                        <th class="border p-3">Jenis</th>
                        <th class="border p-3">Nominal</th>
                        <th class="border p-3">Keterangan</th>
                        <th class="border p-3">Tanggal</th>
                        <th class="border p-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transaksi as $item)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="border p-3">{{ $item->id }}</td>
                            <td class="border p-3">{{ $item->user?->name ?? '-' }}</td>
                            <td class="border p-3">{{ ucfirst($item->jenis) }}</td>
                            <td class="border p-3">Rp {{ number_format($item->nominal, 0, ',', '.') }}</td>
                            <td class="border p-3">{{ $item->keterangan ?: '-' }}</td>
                            <td class="border p-3">{{ $item->created_at?->format('d-m-Y H:i') ?? '-' }}</td>
                            <td class="whitespace-nowrap border p-3">
                                <a href="{{ route('transaksi.edit', $item->id) }}" class="mr-3 text-blue-600 hover:underline">Edit</a>
                                <form action="{{ route('transaksi.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus transaksi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="border p-6 text-center text-gray-500">Belum ada data transaksi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
