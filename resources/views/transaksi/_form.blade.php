@php
    $selectedUserId = old('user_id', $transaksi?->user_id);
    $selectedJenis = old('jenis', $transaksi?->jenis ?? 'pemasukan');
@endphp

@if ($errors->any())
    <div class="mb-4 rounded border border-red-200 bg-red-100 px-4 py-3 text-red-800">
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ $formAction }}" method="POST" class="space-y-4">
    @csrf
    @if ($transaksi)
        @method('PUT')
    @endif

    <div>
        <label for="user_id" class="block font-medium text-gray-700">Pengguna</label>
        <select id="user_id" name="user_id" required class="mt-1 w-full rounded border border-gray-300 px-3 py-2">
            <option value="">Pilih pengguna</option>
            @foreach ($users as $user)
                <option value="{{ $user->id }}" @selected((string) $selectedUserId === (string) $user->id)>
                    {{ $user->name }} ({{ $user->email }})
                </option>
            @endforeach
        </select>
        @if ($users->isEmpty())
            <p class="mt-1 text-sm text-red-600">Belum ada pengguna. Tambahkan pengguna terlebih dahulu.</p>
        @endif
    </div>

    <div>
        <label for="jenis" class="block font-medium text-gray-700">Jenis transaksi</label>
        <select id="jenis" name="jenis" required class="mt-1 w-full rounded border border-gray-300 px-3 py-2">
            <option value="pemasukan" @selected($selectedJenis === 'pemasukan')>Pemasukan</option>
            <option value="pengeluaran" @selected($selectedJenis === 'pengeluaran')>Pengeluaran</option>
        </select>
    </div>

    <div>
        <label for="nominal" class="block font-medium text-gray-700">Nominal</label>
        <input id="nominal" name="nominal" type="number" min="0.01" step="0.01" value="{{ old('nominal', $transaksi?->nominal) }}" required class="mt-1 w-full rounded border border-gray-300 px-3 py-2">
    </div>

    <div>
        <label for="keterangan" class="block font-medium text-gray-700">Keterangan</label>
        <textarea id="keterangan" name="keterangan" rows="3" maxlength="1000" class="mt-1 w-full rounded border border-gray-300 px-3 py-2">{{ old('keterangan', $transaksi?->keterangan) }}</textarea>
    </div>

    <div class="flex gap-3 pt-2">
        <button type="submit" @disabled($users->isEmpty()) class="rounded bg-blue-600 px-4 py-2 font-semibold text-white hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50">{{ $submitLabel }}</button>
        <a href="{{ route('transaksi.index') }}" class="rounded bg-gray-200 px-4 py-2 font-semibold text-gray-700 hover:bg-gray-300">Batal</a>
    </div>
</form>