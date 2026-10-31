@php
    $selectedUserId = old('user_id', $transaksi?->user_id);
    $selectedJenis = old('jenis', $transaksi?->jenis ?? 'pemasukan');
@endphp

@if ($errors->any())
    <div role="alert" class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
        <p class="font-semibold">Periksa kembali data yang dimasukkan:</p>
        <ul class="mt-2 list-inside list-disc space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ $formAction }}" method="POST" class="space-y-5">
    @csrf
    @if ($transaksi)
        @method('PUT')
    @endif

    <div>
        <label for="user_id" class="block text-sm font-semibold text-slate-700">Pengguna</label>
        <select id="user_id" name="user_id" required aria-describedby="user_id_help" class="mt-2 block w-full rounded-xl border-slate-300 px-4 py-3 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <option value="">Pilih pengguna</option>
            @foreach ($users as $user)
                <option value="{{ $user->id }}" @selected((string) $selectedUserId === (string) $user->id)>
                    {{ $user->name }} ({{ $user->email }})
                </option>
            @endforeach
        </select>
        @if ($users->isEmpty())
            <p id="user_id_help" class="mt-2 text-sm text-rose-600">Belum ada pengguna. Tambahkan pengguna terlebih dahulu.</p>
        @else
            <p id="user_id_help" class="mt-2 text-xs text-slate-500">Pilih pengguna yang terkait dengan transaksi ini.</p>
        @endif
        @error('user_id') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="jenis" class="block text-sm font-semibold text-slate-700">Jenis transaksi</label>
        <select id="jenis" name="jenis" required class="mt-2 block w-full rounded-xl border-slate-300 px-4 py-3 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <option value="pemasukan" @selected($selectedJenis === 'pemasukan')>Pemasukan</option>
            <option value="pengeluaran" @selected($selectedJenis === 'pengeluaran')>Pengeluaran</option>
        </select>
        @error('jenis') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="nominal" class="block text-sm font-semibold text-slate-700">Nominal</label>
        <div class="mt-2 flex overflow-hidden rounded-xl border border-slate-300 shadow-sm focus-within:border-indigo-500 focus-within:ring-1 focus-within:ring-indigo-500">
            <span class="inline-flex items-center border-r border-slate-200 bg-slate-50 px-4 text-sm font-semibold text-slate-500">Rp</span>
            <input id="nominal" name="nominal" type="number" min="0.01" step="0.01" value="{{ old('nominal', $transaksi?->nominal) }}" required class="block w-full border-0 px-4 py-3 text-sm focus:ring-0">
        </div>
        <p class="mt-2 text-xs text-slate-500">Masukkan nominal lebih dari nol.</p>
        @error('nominal') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="keterangan" class="block text-sm font-semibold text-slate-700">Keterangan <span class="font-normal text-slate-500">(opsional)</span></label>
        <textarea id="keterangan" name="keterangan" rows="4" maxlength="1000" placeholder="Tambahkan catatan transaksi..." class="mt-2 block w-full rounded-xl border-slate-300 px-4 py-3 text-sm shadow-sm placeholder:text-slate-400 focus:border-indigo-500 focus:ring-indigo-500">{{ old('keterangan', $transaksi?->keterangan) }}</textarea>
        @error('keterangan') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
    </div>

    <div class="flex flex-col-reverse gap-3 border-t border-slate-100 pt-6 sm:flex-row sm:justify-end">
        <a href="{{ route('transaksi.index') }}" class="inline-flex justify-center rounded-xl border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-indigo-500">Batal</a>
        <button type="submit" @disabled($users->isEmpty()) class="inline-flex justify-center rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">{{ $submitLabel }}</button>
    </div>
</form>