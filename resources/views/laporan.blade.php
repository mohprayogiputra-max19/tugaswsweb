<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Kasir (POS)</title>
    <!-- Menggunakan Tailwind CSS dari CDN untuk styling instan -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 h-screen font-sans overflow-hidden">
    
    <!-- Header / Navbar -->
    <header class="bg-blue-600 text-white p-4 shadow-md flex justify-between items-center">
        <h1 class="text-2xl font-bold">POS - Toko Elektronik</h1>
        <div class="text-sm text-right">
            Kasir: <span class="font-semibold">{{ $transaksi['kasir'] }}</span> <br>
            {{ $transaksi['tanggal'] }}
        </div>
    </header>

    <div class="flex h-[calc(100vh-72px)]">
        
        <!-- Area Kiri: Daftar Barang di Keranjang -->
        <div class="w-2/3 p-6 bg-white border-r overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-gray-800">Keranjang Belanja</h2>
                <span class="bg-blue-100 text-blue-800 font-semibold px-3 py-1 rounded-full text-sm border border-blue-200">
                    Nota: {{ $transaksi['no_nota'] }}
                </span>
            </div>

            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-100 text-gray-600 uppercase text-xs leading-normal">
                        <th class="py-3 px-4 rounded-tl-lg">Produk</th>
                        <th class="py-3 px-4 text-center">Qty</th>
                        <th class="py-3 px-4 text-right">Harga Satuan</th>
                        <th class="py-3 px-4 text-right rounded-tr-lg">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 text-sm">
                    @foreach ($keranjang as $item)
                    <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                        <td class="py-4 px-4 font-medium text-gray-800">{{ $item['nama'] }}</td>
                        <td class="py-4 px-4 text-center">
                            <span class="bg-gray-200 px-3 py-1 rounded-md">{{ $item['qty'] }}</span>
                        </td>
                        <td class="py-4 px-4 text-right">Rp {{ number_format($item['harga'], 0, ',', '.') }}</td>
                        <td class="py-4 px-4 text-right font-bold text-blue-600">
                            Rp {{ number_format($item['harga'] * $item['qty'], 0, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Area Kanan: Ringkasan & Tombol Aksi -->
        <div class="w-1/3 p-6 bg-gray-50 flex flex-col justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-800 mb-6">Ringkasan Pembayaran</h2>
                
                <div class="flex justify-between py-2 text-gray-600 text-lg">
                    <span>Subtotal</span>
                    <span class="font-medium">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between py-2 text-gray-600 text-lg border-b border-gray-300 mb-4 pb-4">
                    <span>Pajak (PPN 11%)</span>
                    <span class="font-medium">Rp {{ number_format($pajak, 0, ',', '.') }}</span>
                </div>
                
                <div class="flex justify-between py-3 text-3xl font-extrabold text-gray-900 bg-white rounded-lg px-4 border shadow-sm">
                    <span>Total</span>
                    <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="mt-8 space-y-4">
                <button class="w-full bg-blue-600 hover:bg-blue-700 text-white text-xl font-bold py-4 px-4 rounded-xl shadow-lg transition duration-200 flex items-center justify-center gap-2">
                    <span>💳</span> Bayar Sekarang
                </button>
                <button class="w-full bg-red-100 hover:bg-red-200 text-red-700 font-bold py-3 px-4 rounded-xl transition duration-200 flex items-center justify-center gap-2 border border-red-200">
                    <span>🗑️</span> Batalkan Transaksi
                </button>
            </div>
        </div>

    </div>
</body>
</html>