<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 leading-tight">Dashboard Laporan</h2>
                <p class="text-sm text-gray-500 mt-1">Pilih jenis laporan yang ingin dilihat</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Report Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Financial Report Card -->
                <a href="{{ route('owner.reports.financial') }}" 
                   class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition group">
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="p-2 bg-green-50 rounded-lg text-green-600 mb-4 inline-block">
                                <i class="fas fa-money-bill-wave text-xl"></i>
                            </div>
                            <h3 class="font-bold text-lg text-gray-900 mb-2">Laporan Keuangan</h3>
                            <p class="text-sm text-gray-500">Pendapatan, DP, sisa tagihan</p>
                        </div>
                        <i class="fas fa-chevron-right text-gray-300 group-hover:text-indigo-600 transition-colors mt-1"></i>
                    </div>
                </a>

                <!-- Booking Report Card -->
                <a href="{{ route('owner.reports.bookings') }}" 
                   class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition group">
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="p-2 bg-blue-50 rounded-lg text-blue-600 mb-4 inline-block">
                                <i class="fas fa-calendar-check text-xl"></i>
                            </div>
                            <h3 class="font-bold text-lg text-gray-900 mb-2">Laporan Booking</h3>
                            <p class="text-sm text-gray-500">Statistik & conversion rate</p>
                        </div>
                        <i class="fas fa-chevron-right text-gray-300 group-hover:text-indigo-600 transition-colors mt-1"></i>
                    </div>
                </a>

                <!-- Package Report Card -->
                <a href="{{ route('owner.reports.packages') }}" 
                   class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition group">
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="p-2 bg-amber-50 rounded-lg text-amber-600 mb-4 inline-block">
                                <i class="fas fa-box text-xl"></i>
                            </div>
                            <h3 class="font-bold text-lg text-gray-900 mb-2">Laporan Paket</h3>
                            <p class="text-sm text-gray-500">Performance & trends</p>
                        </div>
                        <i class="fas fa-chevron-right text-gray-300 group-hover:text-indigo-600 transition-colors mt-1"></i>
                    </div>
                </a>

                <!-- Client Report Card -->
                <a href="{{ route('owner.reports.clients') }}" 
                   class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition group">
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="p-2 bg-purple-50 rounded-lg text-purple-600 mb-4 inline-block">
                                <i class="fas fa-users text-xl"></i>
                            </div>
                            <h3 class="font-bold text-lg text-gray-900 mb-2">Laporan Klien</h3>
                            <p class="text-sm text-gray-500">Loyalty & spending analysis</p>
                        </div>
                        <i class="fas fa-chevron-right text-gray-300 group-hover:text-indigo-600 transition-colors mt-1"></i>
                    </div>
                </a>
            </div>

            <!-- Quick Stats -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8">
                <h3 class="font-bold text-lg text-gray-900 mb-4">Statistik Cepat Bulan Ini</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <div class="text-2xl font-bold text-green-600">
                            Rp {{ number_format($stats['monthly_revenue'], 0, ',', '.') }}
                        </div>
                        <div class="text-sm text-gray-500 mt-1">Pendapatan</div>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <div class="text-2xl font-bold text-blue-600">
                            {{ $stats['monthly_bookings'] }}
                        </div>
                        <div class="text-sm text-gray-500 mt-1">Total Booking</div>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <div class="text-2xl font-bold text-amber-600">
                            {{ $stats['new_clients'] }}
                        </div>
                        <div class="text-sm text-gray-500 mt-1">Klien Baru</div>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <div class="text-2xl font-bold text-purple-600">
                            {{ $stats['sold_packages'] }}
                        </div>
                        <div class="text-sm text-gray-500 mt-1">Paket Terjual</div>
                    </div>
                </div>
            </div>

            <!-- Additional Reports Section (Optional) -->
            @if(isset($financialData) && $financialData->count() > 0)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8">
                <h3 class="font-bold text-lg text-gray-900 mb-4">Ringkasan Periode {{ $startDate }} - {{ $endDate }}</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Booking</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Pendapatan</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total DP</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sisa Tagihan</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($financialData as $data)
                            <tr>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ $data->date }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ $data->total_bookings }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-green-600 font-medium">Rp {{ number_format($data->total_revenue, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-blue-600">Rp {{ number_format($data->total_dp, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-amber-600">Rp {{ number_format($data->total_remaining, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>