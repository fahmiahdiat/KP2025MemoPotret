<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 leading-tight">Admin Dashboard</h2>
                <p class="text-sm text-gray-500 mt-1">Ringkasan aktivitas dan performa bisnis Anda.</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.calendar') }}" 
                   class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 shadow-sm transition">
                    <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Kalender
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition group">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">Jadwal Hari Ini</p>
                            <h3 class="text-3xl font-bold text-gray-900">{{ $todayBookings->count() }}</h3>
                        </div>
                        <div class="p-2 bg-indigo-50 rounded-lg text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition group">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">Menunggu Konfirmasi</p>
                            <h3 class="text-3xl font-bold text-amber-600">{{ $pendingBookings }}</h3>
                        </div>
                        <div class="p-2 bg-amber-50 rounded-lg text-amber-600 group-hover:bg-amber-500 group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition group">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">Verifikasi Pembayaran</p>
                            <h3 class="text-3xl font-bold text-blue-600">{{ $pendingPayments }}</h3>
                        </div>
                        <div class="p-2 bg-blue-50 rounded-lg text-blue-600 group-hover:bg-blue-500 group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition group">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">Total Klien</p>
                            <h3 class="text-3xl font-bold text-gray-900">{{ \App\Models\User::where('role', 'client')->count() }}</h3>
                        </div>
                        <div class="p-2 bg-green-50 rounded-lg text-green-600 group-hover:bg-green-500 group-hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid lg:grid-cols-2 gap-8">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col h-full">
                    <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="font-bold text-lg text-gray-900">Jadwal Hari Ini</h3>
                        <span class="text-xs font-medium text-gray-500 bg-gray-100 px-2 py-1 rounded-md">{{ date('d M Y') }}</span>
                    </div>
                    
                    <div class="p-6 flex-1">
                        @if($todayBookings->count() > 0)
                            <div class="space-y-4">
                                @foreach($todayBookings as $booking)
                                <div class="flex items-start p-4 bg-gray-50 rounded-xl border border-gray-100 hover:border-indigo-200 transition-colors group">
                                    <div class="flex-shrink-0 w-16 text-center mr-4">
                                        <div class="text-sm font-bold text-indigo-600">{{ date('H:i', strtotime($booking->event_time)) }}</div>
                                        <div class="text-xs text-gray-400">WIB</div>
                                    </div>
                                    
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-bold text-gray-900 truncate">{{ $booking->user->name }}</p>
                                        <p class="text-xs text-gray-500 truncate mb-2">{{ $booking->package->name }}</p>
                                        <div class="flex items-center text-xs text-gray-400">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                            <span class="truncate">{{ $booking->event_location }}</span>
                                        </div>
                                    </div>

                                    <div class="flex gap-2 ml-4">
                                        <a href="https://wa.me/{{ $booking->user->phone }}" target="_blank" class="p-2 bg-green-100 text-green-600 rounded-lg hover:bg-green-200 transition" title="WhatsApp">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                        </a>
                                        <a href="{{ route('admin.bookings.show', $booking) }}" class="p-2 bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-100 transition" title="Detail">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        </a>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="h-full flex flex-col items-center justify-center text-center py-12">
                                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                <h4 class="font-medium text-gray-900">Tidak ada jadwal hari ini</h4>
                                <p class="text-sm text-gray-500 mt-1">Istirahat atau cek tanggal lain di kalender.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col h-full">
                    <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="font-bold text-lg text-gray-900">Booking Terbaru</h3>
                        <a href="{{ route('admin.bookings.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">Lihat Semua →</a>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50/50">
                                <tr>
                                    <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">Kode/Client</th>
                                    <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Status</th>
                                    <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($recentBookings as $booking)
                                <tr class="hover:bg-gray-50 transition duration-150">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-xs mr-3">
                                                {{ substr($booking->user->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="text-xs font-mono text-gray-500 mb-0.5">#{{ $booking->booking_code }}</div>
                                                <div class="font-medium text-sm text-gray-900">{{ $booking->user->name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @php
                                            $statusClasses = match($booking->status) {
                                                'pending' => 'bg-amber-100 text-amber-800',
                                                'confirmed' => 'bg-blue-100 text-blue-800',
                                                'completed' => 'bg-green-100 text-green-800',
                                                'cancelled' => 'bg-red-100 text-red-800',
                                                default => 'bg-gray-100 text-gray-800'
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClasses }}">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('admin.bookings.show', $booking) }}" class="text-gray-400 hover:text-indigo-600 transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-8 text-center text-gray-500 text-sm">Belum ada data terbaru</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>