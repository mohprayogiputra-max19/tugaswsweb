<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pengguna</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-6 text-gray-800">Tambah Pengguna</h1>

        @if ($errors->any())
            <div class="mb-4 rounded bg-red-100 px-4 py-3 text-red-800">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('users.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="name" class="block font-medium text-gray-700">Nama</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required class="mt-1 w-full rounded border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
            </div>

            <div>
                <label for="email" class="block font-medium text-gray-700">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required class="mt-1 w-full rounded border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
            </div>

            <div>
                <label for="phone" class="block font-medium text-gray-700">No. Telepon</label>
                <input id="phone" name="phone" type="text" value="{{ old('phone') }}" class="mt-1 w-full rounded border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
            </div>

            <div>
                <label for="role" class="block font-medium text-gray-700">Role</label>
                <select id="role" name="role" required class="mt-1 w-full rounded border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
                    <option value="user" @selected(old('role', 'user') === 'user')>User</option>
                    <option value="admin" @selected(old('role') === 'admin')>Admin</option>
                </select>
            </div>

            <div>
                <label for="password" class="block font-medium text-gray-700">Password</label>
                <input id="password" name="password" type="password" required class="mt-1 w-full rounded border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
            </div>

            <div>
                <label for="password_confirmation" class="block font-medium text-gray-700">Konfirmasi Password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required class="mt-1 w-full rounded border border-gray-300 px-3 py-2 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">Simpan</button>
                <a href="{{ route('users.index') }}" class="rounded bg-gray-200 px-4 py-2 text-gray-700 hover:bg-gray-300">Batal</a>
            </div>
        </form>
    </div>
</body>
</html>