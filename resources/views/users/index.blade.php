<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pengguna</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">

    <div class="max-w-5xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-2xl font-bold text-gray-800">Daftar Pengguna Sistem</h2>
            <a href="{{ route('users.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Tambah Pengguna</a>
        </div>

        @if (session('success'))
            <div class="mb-4 rounded bg-green-100 px-4 py-3 text-green-800">{{ session('success') }}</div>
        @endif

        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-blue-500 text-white">
                    <th class="p-3 border">ID</th>
                    <th class="p-3 border">Nama</th>
                    <th class="p-3 border">Email</th>
                    <th class="p-3 border">No. Telepon</th>
                    <th class="p-3 border">Role</th>
                    <th class="p-3 border">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <!-- Di sinilah keajaiban Blade brayy. Kita lakukan perulangan (looping) -->
                @foreach($users as $user)
                <tr class="border-b hover:bg-gray-50">
                    <!-- Blade mencetak data user pada kolom-kolom berikut -->
                    <td class="p-3 border text-center">{{ $user->id }}</td>
                    <td class="p-3 border">{{ $user->name }}</td>
                    <td class="p-3 border">{{ $user->email }}</td>
                    <td class="p-3 border">{{ $user->phone ?? '-' }}</td>
                    <td class="p-3 border">{{ ucfirst($user->role) }}</td>
                    <td class="p-3 border whitespace-nowrap">
                        <a href="{{ route('users.edit', $user->id) }}" class="text-blue-600 hover:underline">Edit</a>
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus pengguna ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="ml-3 text-red-600 hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</body>
</html>