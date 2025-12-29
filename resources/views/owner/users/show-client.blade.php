<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Detail Klien</h2>
                <p class="text-sm text-gray-500 mt-1">{{ $user->name }}</p>
            </div>
            <div>
                <a href="{{ route('owner.reports.clients') }}" 
                   class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali ke Daftar
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Client Info Card -->
            <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Klien</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Profile -->
                    <div class="flex items-start space-x-4">
                        <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center">
                            <span class="text-indigo-600 text-2xl font-bold">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </span>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900">{{ $user->name }}</h4>
                            <p class="text-sm text-gray-500">ID: {{ $user->id }}</p>
                            <p class="text-sm text-gray-500 mt-2">
                                <i class="fas fa-calendar-alt mr-1"></i>
                                Bergabung {{ $user->created_at->format('d F Y') }}
                            </p>
                        </div>
                    </div>

                    <!-- Contact Info -->
                    <div>
                        <h5 class="text-sm font-medium text-gray-700 mb-2">Kontak</h5>
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <i class="fas fa-envelope text-gray-400 w-5"></i>
                                <span class="ml-3 text-gray-900">{{ $user->email }}</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-phone text-gray-400 w-5"></i>
                                <span class="ml-3 text-gray-900">{{ $user->phone ?? '-' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Stats -->
                    <div>
                        <h5 class="text-sm font-medium text-gray-700 mb-2">Statistik</h5>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Total Booking</span>
                                <span class="font-bold text-gray-900">{{ $bookings->total() }}</span>
                            </div>
                            @php
                                $totalSpent = $user->bookings->sum('total_amount');
                            @endphp
                            <div class="flex justify-between">
                                <span class="text-gray-600">Total Belanja</span>
                                <span class="font-bold text-green-600">
                                    Rp {{ number_format($totalSpent, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking History -->
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Riwayat Booking</h3>
                </div>

                @if($bookings->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paket</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Event</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($bookings as $booking)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <code class="text-sm font-medium text-gray-900">{{ $booking->booking_code }}</code>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">{{ $booking->package->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $booking->event_date->format('d/m/Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ $booking->event_date->diffForHumans() }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-green-600">
                                            Rp {{ number_format($booking->total_amount, 0, ',', '.') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($booking->status == 'pending')
                                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-amber-100 text-amber-800">
                                                Pending
                                            </span>
                                        @elseif($booking->status == 'completed')
                                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                                Selesai
                                            </span>
                                        @elseif($booking->status == 'confirmed')
                                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                                Dikonfirmasi
                                            </span>
                                        @else
                                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">
                                                {{ $booking->status }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $bookings->links() }}
                    </div>
                @else
                    <div class="px-6 py-12 text-center">
                        <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-calendar-times text-gray-400 text-2xl"></i>
                        </div>
                        <h4 class="text-lg font-medium text-gray-900 mb-2">Belum ada booking</h4>
                        <p class="text-sm text-gray-500">Klien ini belum melakukan booking apapun</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>