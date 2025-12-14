<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-800">Detail Client: {{ $user->name }}</h2>
            <a href="{{ route('owner.users.index') }}" class="text-sm text-indigo-600">← Kembali</a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Client Info -->
            <div class="card mb-6">
                <h3 class="font-bold text-lg mb-4">Informasi Client</h3>
                <div class="grid md:grid-cols-3 gap-4">
                    <div>
                        <p class="text-gray-500">Nama</p>
                        <p class="font-medium">{{ $user->name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Email</p>
                        <p class="font-medium">{{ $user->email }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Telepon</p>
                        <p class="font-medium">{{ $user->phone }}</p>
                    </div>
                </div>
                <div class="mt-4">
                    <p class="text-gray-500">Member sejak</p>
                    <p class="font-medium">{{ $user->created_at->format('d F Y') }}</p>
                </div>
            </div>

            <!-- Booking History -->
            <div class="card">
                <h3 class="font-bold text-lg mb-4">Riwayat Booking</h3>
                @if($bookings->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="border-b">
                                    <th class="py-3 text-left">Kode</th>
                                    <th class="py-3 text-left">Paket</th>
                                    <th class="py-3 text-left">Tanggal</th>
                                    <th class="py-3 text-left">Total</th>
                                    <th class="py-3 text-left">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bookings as $booking)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="py-3"><code>{{ $booking->booking_code }}</code></td>
                                    <td class="py-3">{{ $booking->package->name }}</td>
                                    <td class="py-3">{{ $booking->event_date->format('d/m/Y') }}</td>
                                    <td class="py-3 font-bold">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</td>
                                    <td class="py-3">
                                        @if($booking->status == 'pending')
                                            <span class="badge-warning">Pending</span>
                                        @elseif($booking->status == 'completed')
                                            <span class="badge-success">Selesai</span>
                                        @else
                                            <span class="badge-primary">{{ $booking->status }}</span>
                                        @endif
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
                    <p class="text-gray-500 text-center py-8">Belum ada booking</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>