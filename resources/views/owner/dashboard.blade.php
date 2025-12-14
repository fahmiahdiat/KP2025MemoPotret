<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-800">Owner Dashboard</h2>
            <div class="space-x-2">
                <a href="{{ route('owner.reports.index') }}" class="btn-secondary">Laporan</a>
                <a href="{{ route('owner.settings.index') }}" class="btn-primary">Pengaturan</a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="card">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 mr-4">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500">Pendapatan Bulan Ini</div>
                            <div class="text-xl font-bold text-gray-900">Rp
                                {{ number_format($monthlyRevenue, 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 mr-4">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500">Total Booking</div>
                            <div class="text-xl font-bold text-gray-900">{{ $totalBookings }}</div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-yellow-100 mr-4">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500">Menunggu</div>
                            <div class="text-xl font-bold text-gray-900">{{ $pendingBookings }}</div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-purple-100 mr-4">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500">Client Aktif</div>
                            <div class="text-xl font-bold text-gray-900">
                                {{ \App\Models\User::where('role', 'client')->where('is_active', true)->count() }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Two Columns -->
            <div class="grid lg:grid-cols-2 gap-6 mb-6">
                <!-- Package Performance -->
                <div class="card">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-bold text-gray-900">Paket Terpopuler</h3>
                        <span class="text-sm text-gray-500">{{ now()->translatedFormat('F Y') }}</span>
                    </div>

                    @if($packageStats->count() > 0)
                        <div class="space-y-4">
                            @foreach($packageStats as $package)
                                <div>
                                    <div class="flex justify-between text-sm mb-1">
                                        <span class="font-medium">{{ $package->name }}</span>
                                        <span class="text-gray-600">{{ $package->bookings_count }} booking</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        @php
                                            $maxBookings = $packageStats->max('bookings_count');
                                            $percentage = $maxBookings > 0 ? ($package->bookings_count / $maxBookings) * 100 : 0;
                                        @endphp
                                        <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        Rp {{ number_format($package->price, 0, ',', '.') }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-8">Belum ada data paket</p>
                    @endif
                </div>

                <!-- Recent Bookings -->
                <div class="card">
                    <h3 class="font-bold text-gray-900 mb-4">Booking Terbaru</h3>

                    @if($recentBookings->count() > 0)
                        <div class="space-y-3">
                            @foreach($recentBookings as $booking)
                                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                                <div class="flex items-center">
                                                    <div class="ml-3">
                                                        <p class="text-sm font-medium text-gray-900">{{ $booking->user->name }}</p>
                                                        <p class="text-xs text-gray-500">{{ $booking->package->name }}</p>
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    <p class="text-sm font-bold text-gray-900">Rp
                                                        {{ number_format($booking->total_amount, 0, ',', '.') }}</p>
                                                    <span
                                                        class="inline-flex items-center px-2 py-1 rounded text-xs font-medium
                                                            {{ $booking->status == 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                ($booking->status == 'completed' ? 'bg-green-100 text-green-800' :
                                    ($booking->status == 'confirmed' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100')) }}">
                                                        {{ ucfirst($booking->status) }}
                                                    </span>
                                                </div>
                                            </div>
                            @endforeach
                        </div>
                        <div class="mt-4 text-center">
                            <a href="{{ route('admin.bookings.index') }}"
                                class="text-sm text-indigo-600 hover:text-indigo-900">
                                Lihat semua booking →
                            </a>
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-8">Belum ada booking</p>
                    @endif
                </div>
            </div>

            <!-- Charts -->
            <div class="grid lg:grid-cols-2 gap-6">
                <!-- User Growth -->
                <div class="card">
                    <h3 class="font-bold text-gray-900 mb-4">Pertumbuhan Client</h3>
                    <div class="h-64">
                        <canvas id="userGrowthChart"></canvas>
                    </div>
                </div>

                <!-- Monthly Revenue -->
                <div class="card">
                    <h3 class="font-bold text-gray-900 mb-4">Pendapatan 6 Bulan Terakhir</h3>
                    <div class="h-64">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // User Growth Chart
            const userCtx = document.getElementById('userGrowthChart').getContext('2d');
            new Chart(userCtx, {
                type: 'line',
                data: {
                    labels: @json($userGrowth->pluck('month')),
                    datasets: [{
                        label: 'Client Baru',
                        data: @json($userGrowth->pluck('count')),
                        borderColor: '#8b5cf6',
                        backgroundColor: 'rgba(139, 92, 246, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });

            // Revenue Chart (Last 6 Months)
            const revenueCtx = document.getElementById('revenueChart').getContext('2d');

            // Generate last 6 months labels
            const months = [];
            for (let i = 5; i >= 0; i--) {
                const date = new Date();
                date.setMonth(date.getMonth() - i);
                months.push(date.toLocaleString('id-ID', { month: 'short' }));
            }

            // Sample revenue data (in real app, get from controller)
            const revenueData = months.map(() => Math.floor(Math.random() * 10000000) + 5000000);

            new Chart(revenueCtx, {
                type: 'bar',
                data: {
                    labels: months,
                    datasets: [{
                        label: 'Pendapatan',
                        data: revenueData,
                        backgroundColor: '#10b981',
                        borderColor: '#059669',
                        borderWidth: 1
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
                            ticks: {
                                callback: function (value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        }
                    }
                }
            });
        </script>
    @endpush
</x-app-layout>