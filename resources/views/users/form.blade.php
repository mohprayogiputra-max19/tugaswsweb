@if ($errors->any())
    <div class="mb-4 rounded bg-red-100 px-4 py-3 text-red-800">
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ $formAction }}" method="POST" class="space-y-4">
    @csrf
    @if ($formMethod !== 'POST')
        @method($formMethod)
    @endif

    <div>
        <label for="name" class="block font-medium text-gray-700">Nama</label>
        <input id="name" name="name" type="text" value="{{ old('name', $user->name ?? '') }}" required class="mt-1 w-full rounded border-gray-300">
    </div>

    <div>
        <label for="email" class="block font-medium text-gray-700">Email</label>
        <input id="email" name="email" type="email" value="{{ old('email', $user->email ?? '') }}" required class="mt-1 w-full rounded border-gray-300">
    </div>

    <div>
        <label for="phone" class="block font-medium text-gray-700">No. Telepon</label>
        <input id="phone" name="phone" type="text" value="{{ old('phone', $user->phone ?? '') }}" class="mt-1 w-full rounded border-gray-300">
    </div>

    <div>
        <label for="role" class="block font-medium text-gray-700">Role</label>
        <select id="role" name="role" required class="mt-1 w-full rounded border-gray-300">
            <option value="user" @selected(old('role', $user->role ?? 'user') === 'user')>User</option>
            <option value="admin" @selected(old('role', $user->role ?? '') === 'admin')>Admin</option>
        </select>
    </div>

    <div>
        <label for="password" class="block font-medium text-gray-700">Password {{ isset($user) ? '(kosongkan jika tidak diubah)' : '' }}</label>
        <input id="password" name="password" type="password" {{ isset($user) ? '' : 'required' }} class="mt-1 w-full rounded border-gray-300">
    </div>

    <div>
        <label for="password_confirmation" class="block font-medium text-gray-700">Konfirmasi Password</label>
        <input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 w-full rounded border-gray-300">
    </div>

    <div class="flex gap-3 pt-2">
        <button type="submit" class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">{{ $submitLabel }}</button>
        <a href="{{ route('users.index') }}" class="rounded bg-gray-200 px-4 py-2 text-gray-700 hover:bg-gray-300">Batal</a>
    </div>
</form>