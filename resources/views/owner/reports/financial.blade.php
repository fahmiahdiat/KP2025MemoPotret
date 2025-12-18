{{-- resources/views/owner/reports/financial.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 leading-tight">Laporan Keuangan</h2>
                <p class="text-sm text-gray-500 mt-1">Analisis pendapatan, DP, dan sisa tagihan</p>
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
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                @php
                    $totalRevenue = $financialData->sum('total_revenue');
                    $totalDP = $financialData->sum('total_dp');
                    $totalRemaining = $financialData->sum('total_remaining');
                    $totalBookings = $financialData->sum('total_bookings');
                @endphp
                
                <div class="bg-white rounded-xl p-6 shadow border border-gray-100">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-50 rounded-lg mr-4">
                            <i class="fas fa-money-bill-wave text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Total Pendapatan</p>
                            <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-6 shadow border border-gray-100">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-50 rounded-lg mr-4">
                            <i class="fas fa-hand-holding-usd text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Total DP</p>
                            <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalDP, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-6 shadow border border-gray-100">
                    <div class="flex items-center">
                        <div class="p-3 bg-amber-50 rounded-lg mr-4">
                            <i class="fas fa-clock text-amber-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Sisa Tagihan</p>
                            <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($totalRemaining, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Financial Table -->
            <div class="bg-white rounded-xl shadow border border-gray-100 mb-8">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="font-bold text-lg text-gray-900">Detail Harian ({{ $startDate }} - {{ $endDate }})</h3>
                    <p class="text-sm text-gray-500">Total {{ $totalBookings }} booking</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Booking</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Pendapatan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">DP</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sisa Tagihan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($financialData as $data)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ \Carbon\Carbon::parse($data->date)->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $data->total_bookings }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 font-medium">Rp {{ number_format($data->total_revenue, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600">Rp {{ number_format($data->total_dp, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-amber-600">Rp {{ number_format($data->total_remaining, 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                    <i class="fas fa-chart-bar text-4xl text-gray-300 mb-2"></i>
                                    <p class="text-sm">Tidak ada data untuk periode ini</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Package Popularity -->
            @if($packagePopularity->count() > 0)
            <div class="bg-white rounded-xl shadow border border-gray-100 mb-8">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="font-bold text-lg text-gray-900">Popularitas Paket</h3>
                    <p class="text-sm text-gray-500">Berdasarkan jumlah booking</p>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($packagePopularity as $package)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-900">{{ $package->name }}</p>
                                <p class="text-sm text-gray-500">{{ $package->booking_count }} booking</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-gray-900">Rp {{ number_format($package->total_amount, 0, ',', '.') }}</p>
                                <p class="text-xs text-gray-500">Total nilai</p>
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