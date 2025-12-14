<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-800">Kelola Booking</h2>
            <a href="{{ route('admin.bookings.create') }}" class="btn-primary">+ Tambah</a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filters -->
            <div class="card mb-6">
                <form method="GET" class="grid md:grid-cols-4 gap-4">
                    <div>
                        <select name="status" class="w-full border rounded px-3 py-2">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>
                    <div>
                        <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <button type="submit" class="btn-primary w-full">Filter</button>
                    </div>
                </form>
            </div>

            <!-- Table -->
            <div class="card">
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b">
                                <th class="py-3 text-left">Kode</th>
                                <th class="py-3 text-left">Client</th>
                                <th class="py-3 text-left">Paket</th>
                                <th class="py-3 text-left">Tanggal</th>
                                <th class="py-3 text-left">Total</th>
                                <th class="py-3 text-left">Status</th>
                                <th class="py-3 text-left">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bookings as $booking)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-3"><code>{{ $booking->booking_code }}</code></td>
                                <td class="py-3">
                                    <div>{{ $booking->user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $booking->user->phone }}</div>
                                </td>
                                <td class="py-3">{{ $booking->package->name }}</td>
                                <td class="py-3">{{ $booking->event_date->format('d/m/Y') }}</td>
                                <td class="py-3 font-bold">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</td>
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
                                    <a href="{{ route('admin.bookings.show', $booking) }}" class="text-indigo-600 mr-2">Detail</a>
                                    @if($booking->status == 'pending')
                                        <button onclick="verifyPayment({{ $booking->id }})" class="text-green-600">Verifikasi</button>
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
            </div>
        </div>
    </div>

    <script>
    function verifyPayment(bookingId) {
        if(confirm('Verifikasi pembayaran DP?')) {
            fetch(`/admin/bookings/${bookingId}/verify-payment`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }).then(() => location.reload());
        }
    }
    </script>
</x-app-layout>