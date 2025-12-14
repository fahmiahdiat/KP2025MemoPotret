<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-800">Kelola User</h2>
            <a href="{{ route('owner.users.create-admin') }}" class="btn-primary">+ Tambah Admin</a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Admins -->
            <div class="card mb-8">
                <h3 class="font-bold text-lg mb-4">Admin</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b">
                                <th class="py-3 text-left">Nama</th>
                                <th class="py-3 text-left">Email</th>
                                <th class="py-3 text-left">Telepon</th>
                                <th class="py-3 text-left">Status</th>
                                <th class="py-3 text-left">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($admins as $admin)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-3">{{ $admin->name }}</td>
                                <td class="py-3">{{ $admin->email }}</td>
                                <td class="py-3">{{ $admin->phone }}</td>
                                <td class="py-3">
                                    @if($admin->is_active ?? true)
                                        <span class="badge-success">Aktif</span>
                                    @else
                                        <span class="badge-warning">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="py-3">
                                    <form action="{{ route('owner.users.toggle-status', $admin) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-sm {{ $admin->is_active ?? true ? 'text-red-600' : 'text-green-600' }}">
                                            {{ $admin->is_active ?? true ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Clients -->
            <div class="card">
                <h3 class="font-bold text-lg mb-4">Client</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b">
                                <th class="py-3 text-left">Nama</th>
                                <th class="py-3 text-left">Email</th>
                                <th class="py-3 text-left">Telepon</th>
                                <th class="py-3 text-left">Booking</th>
                                <th class="py-3 text-left">Total</th>
                                <th class="py-3 text-left"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($clients as $client)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-3">{{ $client->name }}</td>
                                <td class="py-3">{{ $client->email }}</td>
                                <td class="py-3">{{ $client->phone }}</td>
                                <td class="py-3">{{ $client->bookings_count }}</td>
                                <td class="py-3 font-bold text-green-600">
                                    Rp {{ number_format($client->bookings_sum_total_amount, 0, ',', '.') }}
                                </td>
                                <td class="py-3">
                                    <a href="{{ route('owner.users.show-client', $client) }}" class="text-indigo-600 text-sm">Detail</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>