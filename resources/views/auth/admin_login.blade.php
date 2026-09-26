<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Minimarket</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    
    <div class="w-full max-w-4xl p-8 bg-white rounded-xl shadow-lg text-center">
        <h2 class="mb-2 text-3xl font-bold text-gray-800">Panel Administrator Minimarket</h2>
        <p class="mb-8 text-gray-500">Kelola master data, produk, dan pantau penjualan.</p>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Modul 1: Kelola Kasir/Pengguna -->
            <a href="{{ route('users.index') }}" 
               class="p-6 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-600 hover:text-white transition duration-300 group">
                <h3 class="text-xl font-bold text-blue-700 group-hover:text-white">Manajemen Kasir</h3>
                <p class="mt-2 text-sm text-blue-600 group-hover:text-blue-100">Atur akses pengguna.</p>
            </a>

            <!-- Modul 2: Kelola Produk (Menggunakan ProdukController yang ada di web.php) -->
            <a href="/produk" 
               class="p-6 bg-orange-50 border border-orange-200 rounded-lg hover:bg-orange-600 hover:text-white transition duration-300 group">
                <h3 class="text-xl font-bold text-orange-700 group-hover:text-white">Katalog Produk</h3>
                <p class="mt-2 text-sm text-orange-600 group-hover:text-orange-100">Atur harga & stok barang.</p>
            </a>

            <!-- Modul 3: Laporan (Menggunakan LaporanPenjualanController) -->
            <a href="/laporan" 
               class="p-6 bg-green-50 border border-green-200 rounded-lg hover:bg-green-600 hover:text-white transition duration-300 group">
                <h3 class="text-xl font-bold text-green-700 group-hover:text-white">Laporan Penjualan</h3>
                <p class="mt-2 text-sm text-green-600 group-hover:text-green-100">Cek rekap pendapatan.</p>
            </a>
        </div>

        <div class="mt-8">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="px-6 py-2 text-red-600 bg-red-100 rounded-lg hover:bg-red-200 transition font-semibold">Logout Sistem</button>
            </form>
        </div>
    </div>

</body>
</html>