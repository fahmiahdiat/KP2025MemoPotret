{{-- resources/views/owner/reports/packages.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Laporan Paket</h2>
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
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- PACKAGE OVERVIEW -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-lg p-5 shadow-sm border">
                    <p class="text-sm text-gray-500 mb-1">Total Paket</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalPackages }}</p>
                    <p class="text-xs text-gray-400">{{ $activePackages }} aktif</p>
                </div>
                
                <div class="bg-white rounded-lg p-5 shadow-sm border">
                    <p class="text-sm text-gray-500 mb-1">Terjual</p>
                    <p class="text-2xl font-bold text-green-600">{{ $soldPackages }}</p>
                    <p class="text-xs text-gray-400">{{ $totalSales }} booking</p>
                </div>
                
                <div class="bg-white rounded-lg p-5 shadow-sm border">
                    <p class="text-sm text-gray-500 mb-1">Total Revenue</p>
                    <p class="text-2xl font-bold text-indigo-600">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-400">Dari semua paket</p>
                </div>
                
                <div class="bg-white rounded-lg p-5 shadow-sm border">
                    <p class="text-sm text-gray-500 mb-1">Avg/Booking</p>
                    <p class="text-2xl font-bold text-blue-600">Rp {{ number_format($avgBookingValue, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-400">Rata-rata nilai</p>
                </div>
            </div>

            <!-- PACKAGE PERFORMANCE TABLE -->
            <div class="bg-white rounded-lg shadow-sm border mb-6">
                <div class="px-6 py-4 border-b">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="font-bold text-lg text-gray-900">Performance Paket</h3>
                            <p class="text-sm text-gray-500">Berdasarkan jumlah booking dan revenue</p>
                        </div>
                        <div class="text-sm text-gray-500">
                            Urutkan berdasarkan: 
                            <select class="border rounded px-2 py-1 ml-2" onchange="window.location.href = this.value">
                                <option value="{{ route('owner.reports.packages', ['start_date' => $startDate, 'end_date' => $endDate, 'sort' => 'revenue']) }}"
                                        {{ request('sort') == 'revenue' ? 'selected' : '' }}>Revenue Tertinggi</option>
                                <option value="{{ route('owner.reports.packages', ['start_date' => $startDate, 'end_date' => $endDate, 'sort' => 'bookings']) }}"
                                        {{ request('sort') == 'bookings' ? 'selected' : '' }}>Terbanyak Terjual</option>
                                <option value="{{ route('owner.reports.packages', ['start_date' => $startDate, 'end_date' => $endDate, 'sort' => 'name']) }}"
                                        {{ request('sort') == 'name' ? 'selected' : '' }}>Nama A-Z</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                @if($packagePerformance->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paket</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Terjual</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Revenue</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Avg/Booking</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                {{-- <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th> --}}
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($packagePerformance as $package)
                            @php
                                $packageRevenue = $package->bookings_sum_total_amount ?? 0;
                                $bookingCount = $package->bookings_count ?? 0;
                                $avgValue = $bookingCount > 0 ? $packageRevenue / $bookingCount : 0;
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        @if($package->thumbnail)
                                        <img src="{{ asset('storage/' . $package->thumbnail) }}" 
                                             class="w-10 h-10 rounded-lg object-cover mr-3" 
                                             alt="{{ $package->name }}">
                                        @else
                                        <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center mr-3">
                                            <i class="fas fa-box text-indigo-600"></i>
                                        </div>
                                        @endif
                                        <div>
                                            <div class="font-medium text-gray-900">{{ $package->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $package->duration_hours }} jam</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">Rp {{ number_format($package->price, 0, ',', '.') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-center">
                                        <div class="text-lg font-bold text-gray-900">{{ $bookingCount }}</div>
                                        <div class="text-xs text-gray-500">booking</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-green-600">Rp {{ number_format($packageRevenue, 0, ',', '.') }}</div>
                                    @if($bookingCount > 0)
                                    <div class="text-xs text-gray-500">{{ round(($bookingCount / $totalSales) * 100) }}% dari total</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">Rp {{ number_format($avgValue, 0, ',', '.') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($package->is_active)
                                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Aktif
                                    </span>
                                    @else
                                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Nonaktif
                                    </span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="px-6 py-12 text-center">
                    <i class="fas fa-box-open text-4xl text-gray-300 mb-3"></i>
                    <p class="text-gray-500">Tidak ada data paket</p>
                </div>
                @endif
            </div>

            <!-- TOP PERFORMING PACKAGES -->
            @if($topPackages->count() > 0)
            <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
                <h3 class="font-bold text-lg text-gray-900 mb-4">Top 3 Paket Terlaris</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($topPackages->take(3) as $index => $package)
                    <div class="border rounded-lg p-5 hover:bg-gray-50">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 {{ $index == 0 ? 'bg-yellow-100' : ($index == 1 ? 'bg-gray-100' : 'bg-orange-100') }} rounded-lg flex items-center justify-center mr-4">
                                @if($index == 0)
                                <i class="fas fa-trophy text-yellow-600 text-xl"></i>
                                @elseif($index == 1)
                                <i class="fas fa-medal text-gray-600 text-xl"></i>
                                @else
                                <i class="fas fa-award text-orange-600 text-xl"></i>
                                @endif
                            </div>
                            <div>
                                <h4 class="font-bold text-lg text-gray-900">{{ $package->name }}</h4>
                                <p class="text-sm text-gray-500">Rp {{ number_format($package->price, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Terjual:</span>
                                <span class="font-bold">{{ $package->bookings_count }} booking</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Total Revenue:</span>
                                <span class="font-bold text-green-600">Rp {{ number_format($package->bookings_sum_total_amount, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Market Share:</span>
                                <span class="font-bold">{{ round(($package->bookings_count / $totalSales) * 100) }}%</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>