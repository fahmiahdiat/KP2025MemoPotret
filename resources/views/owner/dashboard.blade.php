<x-app-layout>
    <x-slot name="header">
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Dashboard Owner</h1>
                    <p class="text-sm text-gray-500 mt-1">
                        <i class="fas fa-calendar-alt mr-1"></i>
                        {{ now()->translatedFormat('l, d F Y') }}
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('owner.reports.financial') }}"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-indigo-600 rounded-lg hover:bg-indigo-700">
                        <i class="fas fa-chart-bar mr-2"></i>
                        Laporan Detail
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- STATISTIK UTAMA -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <!-- UANG MASUK BULAN INI -->
                <div class="bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-xl p-5">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm text-green-800 mb-1">Uang Masuk (Bulan Ini)</p>
                            <p class="text-2xl font-bold text-green-900">
                                Rp {{ number_format($totalCashIn, 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="p-3 bg-green-200 rounded-lg">
                            <i class="fas fa-money-bill-wave text-green-700"></i>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-green-300/50">
                        <div class="text-xs text-green-800 flex justify-between">
                            <span>DP: <span class="font-semibold">Rp {{ number_format($dpCashIn, 0, ',', '.') }}</span></span>
                            <span>Lunas: <span class="font-semibold">Rp {{ number_format($remainingCashIn, 0, ',', '.') }}</span></span>
                        </div>
                    </div>
                </div>

                <!-- BOOKING BARU -->
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-xl p-5">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm text-blue-800 mb-1">Booking Baru</p>
                            <p class="text-2xl font-bold text-blue-900">
                                {{ $newBookingsThisMonth }} <span class="text-sm font-normal">bulan ini</span>
                            </p>
                        </div>
                        <div class="p-3 bg-blue-200 rounded-lg">
                            <i class="fas fa-calendar-plus text-blue-700"></i>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-blue-300/50">
                        <div class="text-xs text-blue-800">
                            <span class="font-semibold">{{ $newBookingsThisWeek }} minggu ini</span> • 
                            Nilai: <span class="font-semibold">Rp {{ number_format($bookingValueThisMonth, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <!-- PIUTANG -->
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 border border-purple-200 rounded-xl p-5">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm text-purple-800 mb-1">Piutang</p>
                            <p class="text-2xl font-bold text-purple-900">
                                Rp {{ number_format($outstandingAmount, 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="p-3 bg-purple-200 rounded-lg">
                            <i class="fas fa-clock text-purple-700"></i>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-purple-300/50">
                        <div class="text-xs text-purple-800">
                            <span class="font-semibold">{{ $outstandingCount }} booking</span> belum lunas
                        </div>
                    </div>
                </div>

                <!-- PERLU TINDAKAN -->
                <div class="bg-gradient-to-br from-amber-50 to-amber-100 border border-amber-200 rounded-xl p-5">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm text-amber-800 mb-1">Perlu Tindakan</p>
                            <p class="text-2xl font-bold text-amber-900">{{ $requiresAction }}</p>
                        </div>
                        <div class="p-3 bg-amber-200 rounded-lg">
                            <i class="fas fa-exclamation-circle text-amber-700"></i>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-amber-300/50">
                        <div class="text-xs text-amber-800 flex justify-between">
                            <span>Verifikasi DP: <span class="font-semibold">{{ $pendingVerification }}</span></span>
                            <span>Verifikasi Lunas: <span class="font-semibold">{{ $pendingPayments }}</span></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- GRAFIK & STATISTIK -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- GRAFIK PENDAPATAN -->
                <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Pendapatan 6 Bulan Terakhir</h3>
                            <p class="text-sm text-gray-500">Uang masuk setelah diverifikasi</p>
                        </div>
                    </div>
                    <div class="h-64">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>

                <!-- STATISTIK PERTUMBUHAN KLIEN -->
                <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Pertumbuhan Klien</h3>
                            <p class="text-sm text-gray-500">Statistik klien baru</p>
                        </div>
                        <div class="text-sm font-medium {{ $clientGrowth['month_over_month'] >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $clientGrowth['month_over_month'] >= 0 ? '+' : '' }}{{ $clientGrowth['month_over_month'] }}%
                        </div>
                    </div>
                    
                    <!-- Ringkasan Growth -->
                    <div class="grid grid-cols-3 gap-4 mb-6">
                        <div class="text-center p-3 bg-blue-50 rounded-lg">
                            <div class="text-lg font-bold text-blue-700">{{ $newClientsThisMonth }}</div>
                            <div class="text-xs text-blue-600">Klien Baru<br>Bulan Ini</div>
                        </div>
                        <div class="text-center p-3 bg-green-50 rounded-lg">
                            <div class="text-lg font-bold text-green-700">{{ $clientGrowth['month_over_month'] }}%</div>
                            <div class="text-xs text-green-600">Growth<br>Bulan ke Bulan</div>
                        </div>
                        <div class="text-center p-3 bg-purple-50 rounded-lg">
                            <div class="text-lg font-bold text-purple-700">{{ $clientGrowth['total_growth_6m'] }}%</div>
                            <div class="text-xs text-purple-600">Total Growth<br>6 Bulan</div>
                        </div>
                    </div>
                    
                    <!-- Chart Client Growth -->
                    <div class="h-48">
                        <canvas id="clientGrowthChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- ACARA MENDATANG & PAKET TERPOPULER -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- ACARA MENDATANG -->
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Acara Mendatang</h3>
                                <p class="text-sm text-gray-500">{{ $upcomingEvents->count() }} acara berikutnya</p>
                            </div>
                            <a href="{{ route('admin.calendar') }}" class="text-sm text-indigo-600 hover:text-indigo-500">
                                Lihat kalender
                            </a>
                        </div>
                    </div>
                    <div class="divide-y divide-gray-200">
                        @forelse($upcomingEvents as $event)
                        <div class="px-6 py-4 hover:bg-gray-50">
                            <div class="flex items-start">
                                <div class="mr-4">
                                    <div class="text-lg font-bold text-gray-900">{{ $event->event_date->format('d') }}</div>
                                    <div class="text-xs text-gray-500 uppercase">{{ $event->event_date->format('M') }}</div>
                                </div>
                                <div class="flex-1">
                                    <div class="flex justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $event->package->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $event->user->name }}</p>
                                        </div>
                                        <span class="text-xs font-medium px-2 py-1 rounded-full 
                                            {{ $event->status == 'confirmed' ? 'bg-blue-100 text-blue-800' :
                                              ($event->status == 'in_progress' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800') }}">
                                            {{ $event->status }}
                                        </span>
                                    </div>
                                    <div class="mt-2 text-xs text-gray-500">
                                        <i class="fas fa-clock mr-1"></i> {{ date('H:i', strtotime($event->event_time)) }} WIB •
                                        <i class="fas fa-map-marker-alt ml-2 mr-1"></i> {{ Str::limit($event->event_location, 20) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="px-6 py-8 text-center">
                            <i class="fas fa-calendar-check text-gray-300 text-3xl mb-3"></i>
                            <p class="text-sm text-gray-500">Tidak ada acara mendatang</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- PAKET TERPOPULER -->
                <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Paket Terpopuler</h3>
                        <a href="{{ route('admin.packages.index') }}" class="text-sm text-indigo-600 hover:text-indigo-500">
                            Lihat semua
                        </a>
                    </div>
                    <div class="space-y-4">
                        @forelse($topPackages as $package)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-camera text-indigo-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $package->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $package->bookings_count }} booking</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-gray-900">Rp {{ number_format($package->price, 0, ',', '.') }}</p>
                                <p class="text-xs text-green-600 font-medium">
                                    Total: Rp {{ number_format($package->bookings_sum_total_amount, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-8">
                            <i class="fas fa-box-open text-gray-300 text-3xl mb-3"></i>
                            <p class="text-sm text-gray-500">Belum ada data paket</p>
                        </div>
                        @endforelse
                    </div>
                    
                    <!-- Ringkasan Paket -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h4 class="text-sm font-semibold text-gray-900 mb-3">Ringkasan Performa Paket</h4>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="bg-blue-50 p-3 rounded-lg">
                                <div class="text-xs text-blue-600 font-medium mb-1">Total Paket</div>
                                <div class="text-lg font-bold text-blue-700">{{ $topPackages->count() }}</div>
                            </div>
                            <div class="bg-green-50 p-3 rounded-lg">
                                <div class="text-xs text-green-600 font-medium mb-1">Rata-rata Harga</div>
                                <div class="text-lg font-bold text-green-700">
                                    Rp {{ number_format($topPackages->avg('price') ?? 0, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- BOOKING TERBARU -->
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">Booking Terbaru</h2>
                            <p class="text-sm text-gray-500">{{ $recentBookings->count() }} booking terakhir</p>
                        </div>
                        <a href="{{ route('admin.bookings.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                            Lihat semua →
                        </a>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Klien</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Paket</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Acara</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pembayaran</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($recentBookings as $booking)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $booking->booking_code }}</div>
                                    <div class="text-xs text-gray-500">{{ $booking->created_at->format('d/m H:i') }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $booking->user->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $booking->user->email }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $booking->package->name }}</div>
                                    <div class="text-xs text-gray-500">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $booking->event_date->format('d M Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ date('H:i', strtotime($booking->event_time)) }} WIB</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 inline-flex text-xs font-semibold rounded-full 
                                        {{ $booking->status == 'pending' ? 'bg-amber-100 text-amber-800' :
                                          ($booking->status == 'confirmed' ? 'bg-blue-100 text-blue-800' :
                                          ($booking->status == 'in_progress' ? 'bg-purple-100 text-purple-800' :
                                          ($booking->status == 'results_uploaded' ? 'bg-indigo-100 text-indigo-800' :
                                          ($booking->status == 'pending_lunas' ? 'bg-yellow-100 text-yellow-800' :
                                          ($booking->status == 'completed' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'))))) }}">
                                        {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($booking->remaining_amount > 0)
                                        <div class="text-sm text-red-600 font-medium">
                                            Rp {{ number_format($booking->remaining_amount, 0, ',', '.') }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ $booking->remaining_payment_proof ? 'Bukti diupload' : 'Belum upload' }}
                                        </div>
                                    @else
                                        <span class="text-sm text-green-600 font-medium">LUNAS</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                    Tidak ada booking
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Revenue Chart
            const revenueCtx = document.getElementById('revenueChart').getContext('2d');
            new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: @json($revenueChart['labels']),
                    datasets: [{
                        label: 'Pendapatan',
                        data: @json($revenueChart['data']),
                        borderColor: '#10B981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Rp ' + context.raw.toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    if (value >= 1000000) {
                                        return 'Rp ' + (value / 1000000).toFixed(1) + 'Jt';
                                    }
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        }
                    }
                }
            });

            // Client Growth Chart
            const clientGrowthCtx = document.getElementById('clientGrowthChart').getContext('2d');
            new Chart(clientGrowthCtx, {
                type: 'bar',
                data: {
                    labels: @json($clientGrowth['chart_labels']),
                    datasets: [{
                        label: 'Klien Baru',
                        data: @json($clientGrowth['chart_data']),
                        backgroundColor: '#3B82F6',
                        borderColor: '#2563EB',
                        borderWidth: 1,
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
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
        });
    </script>
    @endpush
</x-app-layout>