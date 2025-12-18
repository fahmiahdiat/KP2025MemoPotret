{{-- resources/views/owner/reports/packages.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 leading-tight">Laporan Paket</h2>
                <p class="text-sm text-gray-500 mt-1">Analisis performance paket</p>
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
            <!-- Package Comparison -->
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-8">
                <div class="bg-white rounded-xl p-4 shadow border border-gray-100">
                    <p class="text-sm text-gray-500 mb-1">Total Paket</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $packageComparison['total_packages'] }}</p>
                </div>
                
                <div class="bg-white rounded-xl p-4 shadow border border-gray-100">
                    <p class="text-sm text-gray-500 mb-1">Paket Aktif</p>
                    <p class="text-2xl font-bold text-green-600">{{ $packageComparison['active_packages'] }}</p>
                </div>
                
                <div class="bg-white rounded-xl p-4 shadow border border-gray-100">
                    <p class="text-sm text-gray-500 mb-1">Paket Terjual</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $packageComparison['packages_with_bookings'] }}</p>
                </div>
            </div>

            <!-- Package Performance -->
            <div class="bg-white rounded-xl shadow border border-gray-100 mb-8">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="font-bold text-lg text-gray-900">Performance Paket</h3>
                    <p class="text-sm text-gray-500">Berdasarkan total pendapatan</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Paket</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Booking</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Pendapatan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rata-rata/Harga</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($packagePerformance as $package)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-amber-100 rounded flex items-center justify-center mr-3">
                                            <i class="fas fa-box text-amber-600 text-sm"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $package->name }}</p>
                                            @if(!$package->is_active)
                                                <span class="text-xs text-red-600">Tidak aktif</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <span class="font-medium">{{ $package->bookings_count }}</span> booking
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-green-600">
                                    Rp {{ number_format($package->bookings_sum_total_amount, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    Rp {{ number_format($package->price, 0, ',', '.') }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                    <i class="fas fa-box-open text-4xl text-gray-300 mb-2"></i>
                                    <p class="text-sm">Tidak ada data paket</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Monthly Trends -->
            @if($monthlyPackageTrends->count() > 0)
            <div class="bg-white rounded-xl shadow border border-gray-100 mb-8">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="font-bold text-lg text-gray-900">Trend Bulanan Paket</h3>
                </div>
                <div class="p-6">
                    @php
                        $months = $monthlyPackageTrends->groupBy('month');
                    @endphp
                    
                    <div class="space-y-6">
                        @foreach($months as $month => $packages)
                        <div class="border-b border-gray-100 pb-6 last:border-0 last:pb-0">
                            <h4 class="font-bold text-gray-900 mb-3">{{ \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y') }}</h4>
                            <div class="space-y-3">
                                @foreach($packages->take(5) as $trend)
                                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                    <span class="font-medium text-gray-700">{{ $trend->name }}</span>
                                    <div class="text-right">
                                        <p class="text-sm font-medium text-gray-900">{{ $trend->booking_count }} booking</p>
                                        <p class="text-xs text-gray-500">Rp {{ number_format($trend->total_amount, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                                @endforeach
                                @if($packages->count() > 5)
                                <p class="text-sm text-gray-500 text-center">+ {{ $packages->count() - 5 }} paket lainnya</p>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Price Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white rounded-xl p-6 shadow border border-gray-100 text-center">
                    <p class="text-sm text-gray-500 mb-2">Harga Rata-rata</p>
                    <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($packageComparison['avg_price'], 0, ',', '.') }}</p>
                </div>
                
                <div class="bg-white rounded-xl p-6 shadow border border-gray-100 text-center">
                    <p class="text-sm text-gray-500 mb-2">Termahal</p>
                    <p class="text-2xl font-bold text-red-600">Rp {{ number_format($packageComparison['most_expensive'], 0, ',', '.') }}</p>
                </div>
                
                <div class="bg-white rounded-xl p-6 shadow border border-gray-100 text-center">
                    <p class="text-sm text-gray-500 mb-2">Termurah</p>
                    <p class="text-2xl font-bold text-green-600">Rp {{ number_format($packageComparison['cheapest'], 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>