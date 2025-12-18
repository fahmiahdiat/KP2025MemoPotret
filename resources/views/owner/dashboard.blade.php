<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 leading-tight">Owner Dashboard</h2>
                <p class="text-sm text-gray-500 mt-1">Analisis & Monitoring Bisnis</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('owner.reports.index') }}" 
                   class="inline-flex items-center px-4 py-2.5 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-50 transition shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Laporan
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Pendapatan Bulan Ini -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-100 to-green-50 flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Pendapatan Bulan Ini</p>
                            <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-500">{{ now()->translatedFormat('F Y') }}</span>
                            @php
                                $lastMonth = \Carbon\Carbon::now()->subMonth();
                                $lastMonthRevenue = \App\Models\Booking::where('status', '!=', 'cancelled')
                                    ->whereMonth('created_at', $lastMonth->month)
                                    ->whereYear('created_at', $lastMonth->year)
                                    ->sum('total_amount') ?? 0;
                                
                                $revenueChange = $lastMonthRevenue > 0 ? (($monthlyRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100 : ($monthlyRevenue > 0 ? 100 : 0);
                            @endphp
                            @if($revenueChange > 0)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                                    </svg>
                                    +{{ number_format($revenueChange, 1) }}%
                                </span>
                            @elseif($revenueChange < 0)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                    </svg>
                                    {{ number_format($revenueChange, 1) }}%
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    ±0%
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Total Booking -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-100 to-blue-50 flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Total Booking</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $totalBookings }}</p>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <div class="text-xs text-gray-500">
                            <span class="text-green-600 font-medium">{{ $completedBookings }}</span> selesai • 
                            <span class="text-amber-600 font-medium">{{ $pendingBookings }}</span> pending
                        </div>
                    </div>
                </div>

                <!-- Menunggu -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-100 to-amber-50 flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Menunggu Verifikasi</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $pendingBookings }}</p>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-500">Butuh tindakan</span>
                            @if($pendingBookings > 0)
                                <a href="{{ route('admin.bookings.index') }}?status=pending" 
                                   class="text-xs font-medium text-indigo-600 hover:text-indigo-800">
                                    Lihat →
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Client Aktif -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-100 to-purple-50 flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Client Aktif</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $activeClients }}</p>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        @php
                            $newClientsThisMonth = \App\Models\User::where('role', 'client')
                                ->whereMonth('created_at', now()->month)
                                ->whereYear('created_at', now()->year)
                                ->count();
                        @endphp
                        <div class="text-xs text-gray-500">
                            <span class="text-green-600 font-medium">+{{ $newClientsThisMonth }}</span> bulan ini
                        </div>
                    </div>
                </div>
            </div>

            <!-- Two Columns -->
            <div class="grid lg:grid-cols-2 gap-8 mb-8">
                <!-- Paket Terpopuler -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Paket Terpopuler</h3>
                            <p class="text-sm text-gray-500 mt-1">{{ now()->translatedFormat('F Y') }}</p>
                        </div>
                        <a href="{{ route('admin.packages.index') }}" 
                           class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                            Kelola Paket →
                        </a>
                    </div>

                    @if($packageStats->count() > 0)
                        <div class="space-y-5">
                            @foreach($packageStats as $package)
                                <div>
                                    <div class="flex justify-between items-center mb-2">
                                        <div>
                                            <span class="font-medium text-gray-900">{{ $package->name }}</span>
                                            <span class="ml-2 text-xs text-gray-500">
                                                Rp {{ number_format($package->price, 0, ',', '.') }}
                                            </span>
                                        </div>
                                        <span class="text-sm font-semibold text-gray-700">
                                            {{ $package->bookings_count }} booking
                                        </span>
                                    </div>
                                    <div class="w-full bg-gray-100 rounded-full h-2">
                                        @php
                                            $maxBookings = $packageStats->max('bookings_count');
                                            $percentage = $maxBookings > 0 ? ($package->bookings_count / $maxBookings) * 100 : 0;
                                        @endphp
                                        <div class="bg-gradient-to-r from-indigo-500 to-purple-500 h-2 rounded-full transition-all duration-500" 
                                             style="width: {{ $percentage }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                            </div>
                            <h4 class="text-gray-900 font-medium">Belum ada data paket</h4>
                            <p class="text-sm text-gray-500 mt-1">Tambah paket untuk memulai</p>
                        </div>
                    @endif
                </div>

                <!-- Booking Terbaru -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Booking Terbaru</h3>
                            <p class="text-sm text-gray-500 mt-1">10 booking terakhir</p>
                        </div>
                        <a href="{{ route('admin.bookings.index') }}" 
                           class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                            Lihat Semua →
                        </a>
                    </div>

                    @if($recentBookings->count() > 0)
                        <div class="space-y-3">
                            @foreach($recentBookings as $booking)
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition duration-150">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-tr from-indigo-400 to-purple-400 flex items-center justify-center text-white font-bold text-sm mr-3">
                                            {{ substr($booking->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900">{{ $booking->user->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $booking->package->name }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-bold text-gray-900">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</p>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $booking->status == 'pending' ? 'bg-amber-100 text-amber-800 border border-amber-200' :
                                            ($booking->status == 'completed' ? 'bg-green-100 text-green-800 border border-green-200' :
                                            ($booking->status == 'confirmed' ? 'bg-blue-100 text-blue-800 border border-blue-200' : 
                                            'bg-gray-100 text-gray-800 border border-gray-200')) }}">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <h4 class="text-gray-900 font-medium">Belum ada booking</h4>
                            <p class="text-sm text-gray-500 mt-1">Belum ada booking terbaru</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid lg:grid-cols-2 gap-8">
                <!-- Pertumbuhan Client -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Pertumbuhan Client</h3>
                            <p class="text-sm text-gray-500 mt-1">Total client per bulan</p>
                        </div>
                        <div class="flex items-center text-xs text-gray-500">
                            <span class="w-2 h-2 rounded-full bg-purple-500 mr-2"></span>
                            Total Client
                        </div>
                    </div>
                    <div class="h-72">
                        <canvas id="userGrowthChart"></canvas>
                    </div>
                </div>

                <!-- Pendapatan 6 Bulan -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Pendapatan 6 Bulan Terakhir</h3>
                            <p class="text-sm text-gray-500 mt-1">Trend pendapatan bulanan</p>
                        </div>
                        <div class="flex items-center text-xs text-gray-500">
                            <span class="w-2 h-2 rounded-full bg-green-500 mr-2"></span>
                            Pendapatan
                        </div>
                    </div>
                    <div class="h-72">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="mt-8 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Statistik Cepat</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @php
                        // Calculate quick stats
                        $avgMonthlyRevenue = $totalBookings > 0 ? $monthlyRevenue / max(1, ($totalBookings / 6)) : 0;
                        $avgBookingValue = $totalBookings > 0 ? $monthlyRevenue / $totalBookings : 0;
                        $completionRate = $totalBookings > 0 ? ($completedBookings / $totalBookings) * 100 : 0;
                    @endphp
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <div class="text-2xl font-bold text-gray-900">
                            @if($avgMonthlyRevenue >= 1000000)
                                Rp {{ number_format($avgMonthlyRevenue/1000000, 1, ',', '.') }}JT
                            @else
                                Rp {{ number_format($avgMonthlyRevenue, 0, ',', '.') }}
                            @endif
                        </div>
                        <div class="text-xs text-gray-500 mt-1">Rata-rata/bulan</div>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <div class="text-2xl font-bold text-gray-900">
                            Rp {{ number_format($avgBookingValue, 0, ',', '.') }}
                        </div>
                        <div class="text-xs text-gray-500 mt-1">Rata-rata/booking</div>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <div class="text-2xl font-bold text-gray-900">
                            {{ number_format($completionRate, 1) }}%
                        </div>
                        <div class="text-xs text-gray-500 mt-1">Tingkat penyelesaian</div>
                    </div>
                    <div class="text-center p-4 bg-gray-50 rounded-xl">
                        <div class="text-2xl font-bold text-gray-900">
                            {{ $packageStats->count() }}
                        </div>
                        <div class="text-xs text-gray-500 mt-1">Total paket aktif</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // User Growth Chart
            @php
                // Generate user growth data for chart
                $userGrowthMonths = [];
                $userGrowthCounts = [];
                
                for ($i = 5; $i >= 0; $i--) {
                    $date = \Carbon\Carbon::now()->subMonths($i);
                    $userCount = \App\Models\User::where('role', 'client')
                        ->whereMonth('created_at', '<=', $date->month)
                        ->whereYear('created_at', '<=', $date->year)
                        ->count();
                    
                    $userGrowthMonths[] = $date->translatedFormat('M');
                    $userGrowthCounts[] = $userCount;
                }
            @endphp

            const userCtx = document.getElementById('userGrowthChart').getContext('2d');
            new Chart(userCtx, {
                type: 'line',
                data: {
                    labels: @json($userGrowthMonths),
                    datasets: [{
                        label: 'Total Client',
                        data: @json($userGrowthCounts),
                        borderColor: '#8b5cf6',
                        backgroundColor: 'rgba(139, 92, 246, 0.05)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#8b5cf6',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleFont: { size: 12 },
                            bodyFont: { size: 13 },
                            padding: 12,
                            cornerRadius: 6
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                drawBorder: false
                            },
                            ticks: {
                                stepSize: 1,
                                font: { size: 11 },
                                color: '#6b7280'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: { size: 11 },
                                color: '#6b7280'
                            }
                        }
                    }
                }
            });

            // Revenue Chart (Last 6 Months)
            const revenueCtx = document.getElementById('revenueChart').getContext('2d');
            new Chart(revenueCtx, {
                type: 'bar',
                data: {
                    labels: @json($chartMonths),
                    datasets: [{
                        label: 'Pendapatan',
                        data: @json($chartRevenues),
                        backgroundColor: 'rgba(16, 185, 129, 0.2)',
                        borderColor: '#10b981',
                        borderWidth: 2,
                        borderRadius: 6,
                        borderSkipped: false,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleFont: { size: 12 },
                            bodyFont: { size: 13 },
                            padding: 12,
                            cornerRadius: 6,
                            callbacks: {
                                label: function (context) {
                                    return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                drawBorder: false
                            },
                            ticks: {
                                callback: function (value) {
                                    if (value >= 1000000) {
                                        return 'Rp' + (value / 1000000).toFixed(1) + 'JT';
                                    } else if (value >= 1000) {
                                        return 'Rp' + (value / 1000).toFixed(0) + 'RB';
                                    }
                                    return 'Rp' + value.toLocaleString('id-ID');
                                },
                                font: { size: 11 },
                                color: '#6b7280'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: { size: 11 },
                                color: '#6b7280'
                            }
                        }
                    }
                }
            });
        </script>
    @endpush
</x-app-layout>