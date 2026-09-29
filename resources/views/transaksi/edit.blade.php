<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Transaksi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <main class="mx-auto max-w-2xl rounded-lg bg-white p-6 shadow-md">
        <h1 class="mb-6 text-2xl font-bold text-gray-800">Edit Transaksi #{{ $transaksi->id }}</h1>
        @include('transaksi._form', [
            'transaksi' => $transaksi,
            'users' => $users,
            'formAction' => route('transaksi.update', $transaksi->id),
            'submitLabel' => 'Simpan Perubahan',
        ])
    </main>
</body>
</html>