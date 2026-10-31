<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nota {{ $transaction->no_nota }} | {{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 antialiased">
    <main class="mx-auto max-w-2xl px-4 py-10 sm:px-6">
        <article class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
            <header class="bg-gradient-to-br from-indigo-700 via-indigo-600 to-violet-600 px-6 py-8 text-center text-white sm:px-10">
                <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-white/15 text-2xl" aria-hidden="true">✓</span>
                <h1 class="mt-4 text-2xl font-bold">Transaksi Berhasil</h1>
                <p class="mt-2 text-sm text-indigo-100">Pembayaran telah dicatat dan stok produk diperbarui.</p>
            </header>

            <div class="p-6 sm:p-10">
                <div class="flex flex-wrap justify-between gap-4 border-b border-dashed border-slate-200 pb-5 text-sm">
                    <div>
                        <p class="text-slate-500">Nomor nota</p>
                        <p class="mt-1 font-bold text-slate-900">{{ $transaction->no_nota }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-slate-500">Tanggal</p>
                        <p class="mt-1 font-semibold text-slate-900">{{ $transaction->created_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>

                <div class="mt-5 text-sm">
                    <p class="text-slate-500">Kasir</p>
                    <p class="mt-1 font-semibold text-slate-900">{{ $transaction->user?->name ?? '-' }}</p>
                </div>

                <div class="mt-6 overflow-x-auto">
                    <table class="w-full min-w-[420px] text-left text-sm">
                        <thead class="border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500">
                            <tr>
                                <th scope="col" class="py-3 pr-3">Produk</th>
                                <th scope="col" class="px-3 py-3 text-center">Qty</th>
                                <th scope="col" class="py-3 pl-3 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($transaction->items as $item)
                                <tr>
                                    <td class="py-3 pr-3">
                                        <p class="font-semibold text-slate-900">{{ $item->nama_produk }}</p>
                                        <p class="mt-1 text-xs text-slate-500">{{ $formatRupiah($item->harga_satuan) }} / unit</p>
                                    </td>
                                    <td class="px-3 py-3 text-center text-slate-600">{{ $item->qty }}</td>
                                    <td class="py-3 pl-3 text-right font-semibold text-slate-900">{{ $formatRupiah($item->subtotal) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-5 space-y-3 border-t border-slate-200 pt-5 text-sm">
                    <div class="flex justify-between text-slate-600">
                        <span>Subtotal</span>
                        <span>{{ $formatRupiah($transaction->subtotal) }}</span>
                    </div>
                    <div class="flex justify-between text-slate-600">
                        <span>PPN (11%)</span>
                        <span>{{ $formatRupiah($transaction->pajak) }}</span>
                    </div>
                    <div class="flex justify-between border-t border-slate-100 pt-4 text-base font-bold text-slate-900">
                        <span>Total</span>
                        <span>{{ $formatRupiah($transaction->nominal) }}</span>
                    </div>
                    <div class="flex justify-between text-slate-600">
                        <span>Tunai diterima</span>
                        <span>{{ $formatRupiah($transaction->paid_amount) }}</span>
                    </div>
                    <div class="flex justify-between font-semibold text-emerald-700">
                        <span>Kembalian</span>
                        <span>{{ $formatRupiah($transaction->change_amount) }}</span>
                    </div>
                </div>

                <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                    <a href="{{ route('kasir.pos') }}" class="inline-flex flex-1 justify-center rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Transaksi Baru</a>
                    <button type="button" onclick="window.print()" class="inline-flex flex-1 justify-center rounded-xl border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-indigo-500">Cetak Nota</button>
                </div>
            </div>
        </article>
    </main>
</body>
</html>
