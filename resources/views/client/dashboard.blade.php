<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Dashboard Client</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="card">
                    <div class="text-sm text-gray-500">Total Booking</div>
                    <div class="text-2xl font-bold">{{ $stats['total'] }}</div>
                </div>
                <div class="card">
                    <div class="text-sm text-gray-500">Menunggu</div>
                    <div class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</div>
                </div>
                <div class="card">
                    <div class="text-sm text-gray-500">Dikonfirmasi</div>
                    <div class="text-2xl font-bold text-blue-600">{{ $stats['confirmed'] }}</div>
                </div>
                <div class="card">
                    <div class="text-sm text-gray-500">Selesai</div>
                    <div class="text-2xl font-bold text-green-600">{{ $stats['completed'] }}</div>
                </div>
            </div>

            <!-- Actions -->
            <div class="grid md:grid-cols-2 gap-6 mb-8">
                <div class="card">
                    <h3 class="font-medium mb-4">Aksi Cepat</h3>
                    <a href="{{ route('client.bookings.create') }}" class="btn-primary mb-2">Booking Baru</a>
                    <a href="{{ route('packages') }}" class="btn-secondary">Lihat Paket</a>
                </div>
                <div class="card">
                    <h3 class="font-medium mb-4">Info Akun</h3>
                    <p><span class="text-gray-500">Nama:</span> {{ auth()->user()->name }}</p>
                    <p><span class="text-gray-500">Email:</span> {{ auth()->user()->email }}</p>
                    <p><span class="text-gray-500">Telepon:</span> {{ auth()->user()->phone }}</p>
                </div>
            </div>

            <!-- Bookings -->
            <div class="card">
                <h3 class="font-medium mb-4">Riwayat Booking</h3>
                @if($bookings->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="border-b">
                                    <th class="py-2 text-left">Kode</th>
                                    <th class="py-2 text-left">Paket</th>
                                    <th class="py-2 text-left">Tanggal</th>
                                    <th class="py-2 text-left">Status</th>
                                    <th class="py-2 text-left"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bookings as $booking)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="py-3"><code>{{ $booking->booking_code }}</code></td>
                                    <td class="py-3">{{ $booking->package->name }}</td>
                                    <td class="py-3">{{ $booking->event_date->format('d/m/Y') }}</td>
                                    <td class="py-3">
                                        @if($booking->status == 'pending')
                                            <span class="badge-warning">Pending</span>
                                        @elseif($booking->status == 'confirmed')
                                            <span class="badge-primary">Confirmed</span>
                                        @elseif($booking->status == 'completed')
                                            <span class="badge-success">Selesai</span>
                                        @endif
                                    </td>
                                    <td class="py-3">
                                        <a href="{{ route('client.bookings.show', $booking) }}" class="text-indigo-600 hover:text-indigo-900">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $bookings->links() }}
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        Belum ada booking
                        <a href="{{ route('client.bookings.create') }}" class="block mt-2 text-indigo-600">Buat booking pertama</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>