{{-- resources/views/owner/reports/clients.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Laporan Klien</h2>
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
            <!-- CLIENT STATS -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-lg p-5 shadow-sm border">
                    <p class="text-sm text-gray-500 mb-1">Total Klien</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalClients }}</p>
                    <p class="text-xs text-gray-400">{{ $newClients }} baru</p>
                </div>
                
                <div class="bg-white rounded-lg p-5 shadow-sm border">
                    <p class="text-sm text-gray-500 mb-1">Klien Aktif</p>
                    <p class="text-2xl font-bold text-green-600">{{ $activeClients }}</p>
                    <p class="text-xs text-gray-400">{{ round(($activeClients / $totalClients) * 100) }}% dari total</p>
                </div>
                
                <div class="bg-white rounded-lg p-5 shadow-sm border">
                    <p class="text-sm text-gray-500 mb-1">Returning Klien</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $returningClients }}</p>
                    <p class="text-xs text-gray-400">{{ $returningClients > 0 ? round(($returningClients / $activeClients) * 100) : 0 }}% retention</p>
                </div>
                
                <div class="bg-white rounded-lg p-5 shadow-sm border">
                    <p class="text-sm text-gray-500 mb-1">Avg/Client</p>
                    <p class="text-2xl font-bold text-purple-600">Rp {{ number_format($avgClientValue, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-400">Nilai rata-rata</p>
                </div>
            </div>

            <!-- CLIENTS TABLE -->
            <div class="bg-white rounded-lg shadow-sm border mb-6">
                <div class="px-6 py-4 border-b">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="font-bold text-lg text-gray-900">Daftar Klien</h3>
                            <p class="text-sm text-gray-500">Total {{ $clients->count() }} klien</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="text" placeholder="Cari klien..." 
                                   class="border rounded-lg px-3 py-2 text-sm w-48"
                                   onkeyup="filterClients(this.value)">
                            <select class="border rounded-lg px-3 py-2 text-sm" onchange="window.location.href = this.value">
                                <option value="{{ route('owner.reports.clients', ['start_date' => $startDate, 'end_date' => $endDate]) }}"
                                        {{ !request('sort') ? 'selected' : '' }}>Revenue Tertinggi</option>
                                <option value="{{ route('owner.reports.clients', ['start_date' => $startDate, 'end_date' => $endDate, 'sort' => 'bookings']) }}"
                                        {{ request('sort') == 'bookings' ? 'selected' : '' }}>Paling Aktif</option>
                                <option value="{{ route('owner.reports.clients', ['start_date' => $startDate, 'end_date' => $endDate, 'sort' => 'new']) }}"
                                        {{ request('sort') == 'new' ? 'selected' : '' }}>Klien Baru</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                @if($clients->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Klien</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Join Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Booking</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Belanja</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Avg/Booking</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Booking</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($clients as $client)
                            @php
                                $bookingCount = $client->bookings_count ?? 0;
                                $totalSpent = $client->bookings_sum_total_amount ?? 0;
                                $avgBooking = $bookingCount > 0 ? $totalSpent / $bookingCount : 0;
                                $lastBooking = $client->bookings->first()->created_at ?? null;
                                $daysSinceLast = $lastBooking ? now()->diffInDays($lastBooking) : null;
                                $isActive = $daysSinceLast !== null && $daysSinceLast <= 90; // aktif dalam 90 hari
                            @endphp
                            <tr class="hover:bg-gray-50 client-row" data-name="{{ strtolower($client->name) }}" data-email="{{ strtolower($client->email) }}">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center mr-3">
                                            <div class="text-indigo-600 font-bold">{{ strtoupper(substr($client->name, 0, 1)) }}</div>
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900">{{ $client->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $client->email }}</div>
                                            <div class="text-xs text-gray-400">{{ $client->phone ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $client->created_at->format('d/m/Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $client->created_at->diffForHumans() }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-center">
                                        <div class="text-lg font-bold text-gray-900">{{ $bookingCount }}</div>
                                        <div class="text-xs text-gray-500">booking</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-green-600">Rp {{ number_format($totalSpent, 0, ',', '.') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">Rp {{ number_format($avgBooking, 0, ',', '.') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($lastBooking)
                                    <div class="text-sm text-gray-900">{{ $lastBooking->format('d/m/Y') }}</div>
                                    <div class="text-xs {{ $daysSinceLast <= 30 ? 'text-green-600' : ($daysSinceLast <= 90 ? 'text-amber-600' : 'text-gray-500') }}">
                                        {{ $daysSinceLast }} hari lalu
                                    </div>
                                    @else
                                    <div class="text-sm text-gray-400">Belum ada</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($bookingCount == 0)
                                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        Baru
                                    </span>
                                    @elseif($bookingCount > 1)
                                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        Loyal
                                    </span>
                                    @elseif($isActive)
                                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Aktif
                                    </span>
                                    @else
                                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        Inactive
                                    </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('owner.users.show-client', $client) }}"
                                           class="inline-flex items-center px-3 py-1.5 bg-indigo-50 text-indigo-700 rounded-lg hover:bg-indigo-100 text-sm"
                                           title="Lihat detail klien">
                                            <i class="fas fa-eye mr-1.5 text-xs"></i>
                                            Detail
                                        </a>
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
                            Menampilkan {{ $clients->count() }} klien
                        </div>
                        @if($clients->hasPages())
                        <div>
                            {{ $clients->links() }}
                        </div>
                        @endif
                    </div>
                </div>
                @else
                <div class="px-6 py-12 text-center">
                    <i class="fas fa-users-slash text-4xl text-gray-300 mb-3"></i>
                    <p class="text-gray-500">Tidak ada data klien</p>
                </div>
                @endif
            </div>

            <!-- TOP CLIENTS -->
            @if($topClients->count() > 0)
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="font-bold text-lg text-gray-900 mb-4">Top 5 Klien (by Revenue)</h3>
                    <div class="space-y-4">
                        @foreach($topClients->take(5) as $index => $client)
                        <div class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-10 h-10 {{ $index == 0 ? 'bg-yellow-100' : 'bg-indigo-100' }} rounded-full flex items-center justify-center mr-3">
                                    <div class="{{ $index == 0 ? 'text-yellow-600' : 'text-indigo-600' }} font-bold">
                                        {{ strtoupper(substr($client->name, 0, 1)) }}
                                    </div>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900">{{ $client->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $client->bookings_count }} booking</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="font-bold text-green-600">Rp {{ number_format($client->bookings_sum_total_amount, 0, ',', '.') }}</div>
                                <div class="text-xs text-gray-500">Total spent</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h3 class="font-bold text-lg text-gray-900 mb-4">Klien Loyal (2+ Booking)</h3>
                    <div class="space-y-4">
                        @foreach($loyalClients->take(5) as $client)
                        <div class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-crown text-purple-600"></i>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900">{{ $client->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $client->bookings_count }}x repeat</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="font-bold text-purple-600">{{ $client->bookings_count }} bookings</div>
                                <div class="text-xs text-gray-500">Loyal customer</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <script>
        function filterClients(searchTerm) {
            const rows = document.querySelectorAll('.client-row');
            searchTerm = searchTerm.toLowerCase();
            
            rows.forEach(row => {
                const name = row.getAttribute('data-name');
                const email = row.getAttribute('data-email');
                
                if (name.includes(searchTerm) || email.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
    </script>
</x-app-layout>