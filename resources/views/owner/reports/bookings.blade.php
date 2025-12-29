{{-- resources/views/owner/reports/bookings.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Laporan Booking</h2>
                <p class="text-sm text-gray-500 mt-1">Periode {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y') }} - {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') }}</p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <form method="GET" class="flex flex-wrap items-center gap-2">
                    <div class="flex items-center gap-2">
                        <input type="date" name="start_date" value="{{ $startDate }}" 
                               class="border rounded-lg px-3 py-2 text-sm w-40">
                        <span class="text-gray-500">s/d</span>
                        <input type="date" name="end_date" value="{{ $endDate }}" 
                               class="border rounded-lg px-3 py-2 text-sm w-40">
                    </div>
                    <button type="submit" 
                            class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 text-sm">
                        Terapkan
                    </button>
                </form>
                <a href="?export=excel&start_date={{ $startDate }}&end_date={{ $endDate }}" 
                   class="border border-green-600 text-green-600 px-4 py-2 rounded-lg hover:bg-green-50 text-sm">
                    <i class="fas fa-file-excel mr-2"></i>Export Excel
                </a>
            </div>
        </div>
    </x-slot>

<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- BOOKING STATS -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            @php
                $totalBookings = $bookings->count();
                $completedBookings = $bookings->where('status', 'completed')->count();
                $pendingBookings = $bookings->where('status', 'pending')->count();
                $conversionRate = $totalBookings > 0
                    ? round(($completedBookings / $totalBookings) * 100, 1)
                    : 0;
            @endphp

            <!-- TOTAL -->
            <div class="bg-white rounded-xl p-5 shadow-sm border">
                <p class="text-sm text-gray-500 mb-1">Total Booking</p>
                <p class="text-3xl font-bold text-gray-900">
                    {{ $totalBookings }}
                </p>
            </div>

            <!-- SELESAI -->
            <div class="bg-white rounded-xl p-5 shadow-sm border">
                <p class="text-sm text-gray-500 mb-1">Selesai</p>
                <p class="text-3xl font-bold text-green-600">
                    {{ $completedBookings }}
                </p>
                <p class="text-xs text-gray-400 mt-1">
                    {{ $conversionRate }}% conversion
                </p>
            </div>

            <!-- PENDING -->
            <div class="bg-white rounded-xl p-5 shadow-sm border">
                <p class="text-sm text-gray-500 mb-1">Pending</p>
                <p class="text-3xl font-bold text-amber-600">
                    {{ $pendingBookings }}
                </p>
                <p class="text-xs text-gray-400 mt-1">
                    Belum DP
                </p>
            </div>

            <!-- CANCEL / OTHER -->
            <div class="bg-white rounded-xl p-5 shadow-sm border">
                <p class="text-sm text-gray-500 mb-1">Dibatalkan</p>
                <p class="text-3xl font-bold text-red-600">
                    {{ $bookings->where('status', 'cancelled')->count() }}
                </p>
                <p class="text-xs text-gray-400 mt-1">
                    Tidak dilanjutkan
                </p>
            </div>
        </div>

    </div>
</div>


            <!-- BOOKINGS TABLE -->
            <div class="bg-white rounded-lg shadow-sm border mb-6">
                <div class="px-6 py-4 border-b">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="font-bold text-lg text-gray-900">Daftar Booking</h3>
                            <p class="text-sm text-gray-500">Total {{ $bookings->count() }} booking</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <select class="border rounded-lg px-3 py-2 text-sm" onchange="window.location.href = this.value">
                                <option value="{{ route('owner.reports.bookings', ['start_date' => $startDate, 'end_date' => $endDate]) }}" 
                                        {{ !request('status') ? 'selected' : '' }}>Semua Status</option>
                                <option value="{{ route('owner.reports.bookings', ['start_date' => $startDate, 'end_date' => $endDate, 'status' => 'pending']) }}"
                                        {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="{{ route('owner.reports.bookings', ['start_date' => $startDate, 'end_date' => $endDate, 'status' => 'confirmed']) }}"
                                        {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="{{ route('owner.reports.bookings', ['start_date' => $startDate, 'end_date' => $endDate, 'status' => 'completed']) }}"
                                        {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                @if($bookings->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Klien</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paket</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Event</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($bookings as $booking)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-indigo-600">{{ $booking->booking_code }}</div>
                                    <div class="text-xs text-gray-500">{{ $booking->created_at->format('d/m/Y') }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $booking->user->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $booking->user->phone ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $booking->package->name }}</div>
                                    <div class="text-xs text-gray-500">Rp {{ number_format($booking->package->price, 0, ',', '.') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $booking->event_date->format('d/m/Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $booking->event_time ?? '09:00' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-amber-100 text-amber-800',
                                            'confirmed' => 'bg-blue-100 text-blue-800',
                                            'in_progress' => 'bg-indigo-100 text-indigo-800',
                                            'pending_lunas' => 'bg-orange-100 text-orange-800',
                                            'completed' => 'bg-green-100 text-green-800',
                                            'cancelled' => 'bg-red-100 text-red-800',
                                        ];
                                    @endphp
                                    <span class="px-2 py-1 rounded-full text-xs font-medium {{ $statusColors[$booking->status] ?? 'bg-gray-100' }}">
                                        {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                                    </span>
                                    @if($booking->status == 'pending')
                                        <div class="text-xs text-gray-500 mt-1">Menunggu DP</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</div>
                                    <div class="text-xs text-gray-500">
                                        DP: Rp {{ number_format($booking->dp_amount, 0, ',', '.') }}
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="px-6 py-4 border-t">
                    <div class="flex justify-between items-center">
                        <div class="text-sm text-gray-500">
                            Menampilkan {{ $bookings->count() }} booking
                        </div>
                        @if($bookings->hasPages())
                        <div>
                            {{ $bookings->links() }}
                        </div>
                        @endif
                    </div>
                </div>
                @else
                <div class="px-6 py-12 text-center">
                    <i class="fas fa-calendar-times text-4xl text-gray-300 mb-3"></i>
                    <p class="text-gray-500">Tidak ada booking pada periode ini</p>
                    <p class="text-sm text-gray-400 mt-1">Coba ubah rentang tanggal atau status filter</p>
                </div>
                @endif
            </div>

            <!-- STATUS DISTRIBUTION -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h3 class="font-bold text-lg text-gray-900 mb-4">Distribusi Status Booking</h3>
                <div class="grid grid-cols-2 md:grid-cols-6 gap-4">
                    @php
                        $statusCounts = $bookings->groupBy('status')->map->count();
                        $total = $bookings->count();
                    @endphp
                    
                    @foreach(['pending', 'confirmed', 'in_progress', 'pending_lunas', 'completed', 'cancelled'] as $status)
                    @php
                        $count = $statusCounts[$status] ?? 0;
                        $percentage = $total > 0 ? round(($count / $total) * 100) : 0;
                        $colors = [
                            'pending' => 'bg-amber-500',
                            'confirmed' => 'bg-blue-500',
                            'in_progress' => 'bg-indigo-500',
                            'pending_lunas' => 'bg-orange-500',
                            'completed' => 'bg-green-500',
                            'cancelled' => 'bg-red-500',
                        ];
                    @endphp
                    <div class="text-center">
                        <div class="text-2xl font-bold text-gray-900">{{ $count }}</div>
                        <div class="h-2 w-full bg-gray-200 rounded-full mt-2 overflow-hidden">
                            <div class="h-full {{ $colors[$status] ?? 'bg-gray-400' }}" 
                                 style="width: {{ $percentage }}%"></div>
                        </div>
                        @php
    $statusLabels = [
        'pending'        => 'Menunggu Konfirmasi DP',
        'confirmed'      => 'Dikonfirmasi',
        'in_progress'    => 'Sedang Berjalan',
        'pending_lunas'  => 'Menunggu Pelunasan',
        'completed'      => 'Selesai',
        'cancelled'      => 'Dibatalkan',
    ];
@endphp

<div class="text-xs text-gray-500 mt-2">
    {{ $statusLabels[$status] ?? $status }}
</div>

                        <div class="text-xs font-medium">{{ $percentage }}%</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>