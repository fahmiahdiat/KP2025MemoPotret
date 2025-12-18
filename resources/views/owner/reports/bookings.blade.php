{{-- resources/views/owner/reports/bookings.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 leading-tight">Laporan Booking</h2>
                <p class="text-sm text-gray-500 mt-1">Analisis status dan konversi booking</p>
            </div>
            <div class="flex items-center gap-4">
                <form method="GET" class="flex items-center gap-2">
                    <input type="date" name="start_date" value="{{ $startDate }}" 
                           class="border rounded-lg px-3 py-2 text-sm">
                    <span class="text-gray-500">s/d</span>
                    <input type="date" name="end_date" value="{{ $endDate }}" 
                           class="border rounded-lg px-3 py-2 text-sm">
                    <button type="submit" 
                            class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 text-sm">
                        Filter
                    </button>
                    <a href="{{ route('owner.reports.index') }}" 
                       class="border border-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50 text-sm">
                        Kembali
                    </a>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Conversion Stats -->
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
                @php
                    $conversionRate = $conversionData['total_bookings'] > 0 
                        ? round(($conversionData['completed_bookings'] / $conversionData['total_bookings']) * 100, 1) 
                        : 0;
                @endphp
                
                <div class="bg-white rounded-xl p-4 shadow border border-gray-100">
                    <p class="text-sm text-gray-500 mb-1">Total Booking</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $conversionData['total_bookings'] }}</p>
                </div>
                
                <div class="bg-white rounded-xl p-4 shadow border border-gray-100">
                    <p class="text-sm text-gray-500 mb-1">Pending</p>
                    <p class="text-2xl font-bold text-amber-600">{{ $conversionData['pending_bookings'] }}</p>
                </div>
                
                <div class="bg-white rounded-xl p-4 shadow border border-gray-100">
                    <p class="text-sm text-gray-500 mb-1">Confirmed</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $conversionData['confirmed_bookings'] }}</p>
                </div>
                
                <div class="bg-white rounded-xl p-4 shadow border border-gray-100">
                    <p class="text-sm text-gray-500 mb-1">Completed</p>
                    <p class="text-2xl font-bold text-green-600">{{ $conversionData['completed_bookings'] }}</p>
                </div>
                
                <div class="bg-white rounded-xl p-4 shadow border border-gray-100">
                    <p class="text-sm text-gray-500 mb-1">Conversion Rate</p>
                    <p class="text-2xl font-bold text-purple-600">{{ $conversionRate }}%</p>
                </div>
            </div>

            <!-- Booking Status Details -->
            <div class="bg-white rounded-xl shadow border border-gray-100 mb-8">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="font-bold text-lg text-gray-900">Detail Status Booking</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Nilai</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rata-rata</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($bookingStats as $stat)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-amber-100 text-amber-800',
                                            'confirmed' => 'bg-blue-100 text-blue-800',
                                            'completed' => 'bg-green-100 text-green-800',
                                            'cancelled' => 'bg-red-100 text-red-800',
                                        ];
                                        $colorClass = $statusColors[$stat->status] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-xs font-medium {{ $colorClass }}">
                                        {{ ucfirst($stat->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $stat->count }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp {{ number_format($stat->total_amount, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp {{ number_format($stat->avg_amount, 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                    <i class="fas fa-calendar-times text-4xl text-gray-300 mb-2"></i>
                                    <p class="text-sm">Tidak ada data booking</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Monthly Trend -->
            @if($monthlyTrend->count() > 0)
            <div class="bg-white rounded-xl shadow border border-gray-100 mb-8">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="font-bold text-lg text-gray-900">Trend Bulanan</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($monthlyTrend as $trend)
                        <div class="border-b border-gray-100 pb-4 last:border-0 last:pb-0">
                            <div class="flex justify-between items-center mb-2">
                                <p class="font-medium text-gray-900">{{ \Carbon\Carbon::createFromFormat('Y-m', $trend->month)->format('F Y') }}</p>
                                <p class="text-sm text-gray-500">{{ $trend->booking_count }} booking</p>
                            </div>
                            <div class="flex justify-between text-sm">
                                <div>
                                    <p class="text-gray-600">Total Pendapatan: <span class="font-medium text-green-600">Rp {{ number_format($trend->total_revenue, 0, ',', '.') }}</span></p>
                                </div>
                                <div>
                                    <p class="text-gray-600">Completed: <span class="font-medium text-green-600">{{ $trend->completed_count }}</span></p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>