<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Kasir</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    
    <div class="w-full max-w-md p-8 bg-white rounded-xl shadow-lg text-center">
        <h2 class="mb-2 text-3xl font-bold text-gray-800">Halo, Tim Kasir!</h2>
        <p class="mb-8 text-gray-500">Mulai shift Anda dan layani pelanggan hari ini.</p>
        
        <div class="space-y-4">
            <!-- Navigasi ke halaman mesin kasir (POS) -->
            <a href="/produk" 
               class="block w-full p-4 font-bold text-white bg-green-600 rounded-lg hover:bg-green-700 transition duration-200 shadow-md">
                Buka Mesin Kasir (Transaksi)
            </a>
        </div>

        <div class="mt-6 border-t pt-6">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="text-sm font-semibold text-red-500 hover:underline">Keluar (End Shift)</button>
            </form>
        </div>
    </div>

</body>
</html>