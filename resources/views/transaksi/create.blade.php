<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Transaksi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <main class="mx-auto max-w-2xl rounded-lg bg-white p-6 shadow-md">
        <h1 class="mb-6 text-2xl font-bold text-gray-800">Tambah Transaksi</h1>
        @include('transaksi._form', [
            'transaksi' => null,
            'users' => $users,
            'formAction' => route('transaksi.store'),
            'submitLabel' => 'Simpan Transaksi',
        ])
    </main>
</body>
</html>