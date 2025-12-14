<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-800">Tambah Admin Baru</h2>
            <a href="{{ route('owner.users.index') }}" class="text-sm text-indigo-600">← Kembali</a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="card">
                <form method="POST" action="{{ route('owner.users.store-admin') }}">
                    @csrf

                    <div class="mb-4">
                        <label class="block font-medium mb-2">Nama Lengkap</label>
                        <input type="text" name="name" class="w-full border rounded px-3 py-2" required>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-2">Email</label>
                        <input type="email" name="email" class="w-full border rounded px-3 py-2" required>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-2">Telepon</label>
                        <input type="tel" name="phone" class="w-full border rounded px-3 py-2" required>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-2">Password</label>
                        <input type="password" name="password" class="w-full border rounded px-3 py-2" required>
                    </div>

                    <div class="mb-6">
                        <label class="block font-medium mb-2">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="w-full border rounded px-3 py-2" required>
                    </div>

                    <div class="text-right">
                        <button type="submit" class="btn-primary">Tambah Admin</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>