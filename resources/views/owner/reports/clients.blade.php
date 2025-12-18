{{-- resources/views/owner/reports/clients.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 leading-tight">Laporan Klien</h2>
                <p class="text-sm text-gray-500 mt-1">Analisis loyalitas dan spending klien</p>
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
            <!-- Client Analysis -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-white rounded-xl p-4 shadow border border-gray-100">
                    <p class="text-sm text-gray-500 mb-1">Total Klien</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $clientAnalysis['total_clients'] }}</p>
                </div>
                
                <div class="bg-white rounded-xl p-4 shadow border border-gray-100">
                    <p class="text-sm text-gray-500 mb-1">Klien dengan Booking</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $clientAnalysis['clients_with_bookings'] }}</p>
                </div>
                
                <div class="bg-white rounded-xl p-4 shadow border border-gray-100">
                    <p class="text-sm text-gray-500 mb-1">Klien Baru</p>
                    <p class="text-2xl font-bold text-green-600">{{ $clientAnalysis['new_clients'] }}</p>
                </div>
                
                <div class="bg-white rounded-xl p-4 shadow border border-gray-100">
                    <p class="text-sm text-gray-500 mb-1">Returning Klien</p>
                    <p class="text-2xl font-bold text-purple-600">{{ $clientAnalysis['returning_clients'] }}</p>
                </div>
            </div>

            <!-- Top Clients by Revenue -->
            <div class="bg-white rounded-xl shadow border border-gray-100 mb-8">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="font-bold text-lg text-gray-900">Klien dengan Spending Tertinggi</h3>
                    <p class="text-sm text-gray-500">Top 20 klien berdasarkan total belanja</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Klien</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Booking</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Belanja</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($topClientsByRevenue as $client)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center mr-3">
                                            <i class="fas fa-user text-indigo-600 text-sm"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $client->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $client->phone ?? '-' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $client->email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <span class="font-medium">{{ $client->bookings_count }}</span> booking
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-green-600">
                                    Rp {{ number_format($client->bookings_sum_total_amount, 0, ',', '.') }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                    <i class="fas fa-users-slash text-4xl text-gray-300 mb-2"></i>
                                    <p class="text-sm">Tidak ada data klien</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Loyal Clients -->
            @if($loyalClients->count() > 0)
            <div class="bg-white rounded-xl shadow border border-gray-100 mb-8">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="font-bold text-lg text-gray-900">Klien Loyal</h3>
                    <p class="text-sm text-gray-500">Klien dengan booking lebih dari 1 kali</p>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($loyalClients as $client)
                        <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50">
                            <div class="flex items-center mb-3">
                                <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-crown text-purple-600"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900">{{ $client->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $client->email }}</p>
                                </div>
                            </div>
                            <div class="flex justify-between text-sm">
                                <div>
                                    <p class="text-gray-600">Total Booking</p>
                                    <p class="font-bold text-gray-900">{{ $client->bookings_count }}x</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-gray-600">Total Belanja</p>
                                    <p class="font-bold text-green-600">Rp {{ number_format($client->bookings_sum_total_amount, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Client Locations -->
            @if($clientLocations->count() > 0)
            <div class="bg-white rounded-xl shadow border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="font-bold text-lg text-gray-900">Distribusi Lokasi Event</h3>
                    <p class="text-sm text-gray-500">Berdasarkan lokasi event booking</p>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($clientLocations as $location)
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-blue-100 rounded flex items-center justify-center mr-3">
                                    <i class="fas fa-map-marker-alt text-blue-600"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $location->event_location }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-gray-900">{{ $location->booking_count }} booking</p>
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