<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Admin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-4">
                    <p>Selamat datang, {{ auth()->user()->name }}.</p>
                    <p>Role: <strong>Admin</strong></p>
                    <nav class="flex flex-wrap gap-4">
                        <a class="text-blue-700 underline" href="{{ route('products.index') }}">Produk</a>
                        <a class="text-blue-700 underline" href="{{ route('users.index') }}">Pengguna</a>
                        <a class="text-blue-700 underline" href="{{ route('transaksi.index') }}">Transaksi</a>
                        <a class="text-blue-700 underline" href="{{ route('reports.index') }}">Laporan</a>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
