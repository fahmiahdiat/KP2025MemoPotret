<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-800">Admin Dashboard</h2>
            <div class="space-x-2">
                <a href="{{ route('admin.calendar') }}" class="btn-secondary">Kalender</a>
                <a href="{{ route('admin.bookings.create') }}" class="btn-primary">+ Booking</a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="card">
                    <div class="text-sm text-gray-500">Hari Ini</div>
                    <div class="text-2xl font-bold">{{ $todayBookings->count() }}</div>
                </div>
                <div class="card">
                    <div class="text-sm text-gray-500">Pending</div>
                    <div class="text-2xl font-bold text-yellow-600">{{ $pendingBookings }}</div>
                </div>
                <div class="card">
                    <div class="text-sm text-gray-500">Pembayaran</div>
                    <div class="text-2xl font-bold text-blue-600">{{ $pendingPayments }}</div>
                </div>
                <div class="card">
                    <div class="text-sm text-gray-500">Total Client</div>
                    <div class="text-2xl font-bold text-green-600">{{ \App\Models\User::where('role', 'client')->count() }}</div>
                </div>
            </div>

            <div class="grid lg:grid-cols-2 gap-6">
                <!-- Today -->
                <div class="card">
                    <h3 class="font-bold mb-4">Jadwal Hari Ini</h3>
                    @if($todayBookings->count() > 0)
                        <div class="space-y-3">
                            @foreach($todayBookings as $booking)
                            <div class="border rounded p-3">
                                <div class="flex justify-between">
                                    <div>
                                        <p class="font-medium">{{ $booking->user->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $booking->event_location }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-medium">{{ date('H:i', strtotime($booking->event_time)) }}</p>
                                        <p class="text-sm text-gray-500">{{ $booking->package->name }}</p>
                                    </div>
                                </div>
                                <div class="mt-2 flex space-x-2">
                                    <a href="{{ route('admin.bookings.show', $booking) }}" class="text-sm text-indigo-600">Detail</a>
                                    <a href="https://wa.me/{{ $booking->user->phone }}" target="_blank" class="text-sm text-green-600">WA</a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">Tidak ada jadwal</p>
                    @endif
                </div>

                <!-- Recent -->
                <div class="card">
                    <h3 class="font-bold mb-4">Booking Terbaru</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="border-b">
                                    <th class="py-2 text-left">Kode</th>
                                    <th class="py-2 text-left">Client</th>
                                    <th class="py-2 text-left">Status</th>
                                    <th class="py-2 text-left"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentBookings as $booking)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="py-2"><code>{{ $booking->booking_code }}</code></td>
                                    <td class="py-2">{{ $booking->user->name }}</td>
                                    <td class="py-2">
                                        @if($booking->status == 'pending')
                                            <span class="badge-warning">Pending</span>
                                        @elseif($booking->status == 'confirmed')
                                            <span class="badge-primary">Confirmed</span>
                                        @endif
                                    </td>
                                    <td class="py-2">
                                        <a href="{{ route('admin.bookings.show', $booking) }}" class="text-indigo-600">Lihat</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('admin.bookings.index') }}" class="text-indigo-600">Semua booking →</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>