<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-900 leading-tight">
            Dashboard Client
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-10 bg-white rounded-3xl p-8 shadow-sm border border-gray-100 flex flex-col md:flex-row justify-between items-center gap-6">
                <div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Selamat Datang, {{ auth()->user()->name }}! 👋</h3>
                    <p class="text-gray-500">Kelola semua jadwal pemotretan dan momen spesial Anda di sini.</p>
                </div>
                <div class="flex gap-4">
                    <a href="{{ route('packages') }}" class="inline-flex items-center px-6 py-3 bg-gray-900 text-white font-semibold rounded-xl hover:bg-gray-800 transition shadow-lg hover:shadow-xl hover:-translate-y-1">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        Booking Baru
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        </div>
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total</span>
                    </div>
                    <div class="text-3xl font-bold text-gray-900 mb-1">{{ $stats['total'] }}</div>
                    <div class="text-sm text-gray-500">Semua Pesanan</div>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center text-amber-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Pending</span>
                    </div>
                    <div class="text-3xl font-bold text-gray-900 mb-1">{{ $stats['pending'] }}</div>
                    <div class="text-sm text-gray-500">Menunggu Konfirmasi</div>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Confirmed</span>
                    </div>
                    <div class="text-3xl font-bold text-gray-900 mb-1">{{ $stats['confirmed'] }}</div>
                    <div class="text-sm text-gray-500">Jadwal Terkonfirmasi</div>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center text-green-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Selesai</span>
                    </div>
                    <div class="text-3xl font-bold text-gray-900 mb-1">{{ $stats['completed'] }}</div>
                    <div class="text-sm text-gray-500">Sesi Berakhir</div>
                </div>
            </div>

            <div class="grid lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                            <h3 class="font-bold text-lg text-gray-900">Riwayat Booking</h3>
                            <a href="#" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">Lihat Semua</a>
                        </div>
                        
                        @if($bookings->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Kode & Paket</th>
                                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal</th>
                                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach($bookings as $booking)
                                        <tr class="hover:bg-gray-50/50 transition">
                                            <td class="px-6 py-4">
                                                <div class="flex flex-col">
                                                    <span class="font-bold text-gray-900">#{{ $booking->booking_code }}</span>
                                                    <span class="text-sm text-gray-500">{{ $booking->package->name }}</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                    {{ $booking->event_date->format('d M Y') }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                @if($booking->status == 'pending')
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-2"></span>
                                                        Menunggu DP
                                                    </span>
                                                @elseif($booking->status == 'confirmed')
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500 mr-2"></span>
                                                        Confirmed
                                                    </span>
                                                @elseif($booking->status == 'in_progress')
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-purple-500 mr-2"></span>
                                                        Sedang Proses
                                                    </span>
                                                @elseif($booking->status == 'completed')
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-2"></span>
                                                        Selesai
                                                    </span>
                                                @elseif($booking->status == 'cancelled')
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-2"></span>
                                                        Dibatalkan
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <a href="{{ route('client.bookings.show', $booking) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-white border border-gray-200 text-gray-400 hover:text-indigo-600 hover:border-indigo-200 hover:bg-indigo-50 transition">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="p-6 border-t border-gray-100">
                                {{ $bookings->links() }}
                            </div>
                        @else
                            <div class="text-center py-16 px-6">
                                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <h4 class="text-lg font-bold text-gray-900 mb-2">Belum ada booking</h4>
                                <p class="text-gray-500 mb-6 max-w-sm mx-auto">Anda belum melakukan pemesanan paket apapun. Mulai booking sekarang untuk mengabadikan momen Anda.</p>
                                <a href="{{ route('packages') }}" class="inline-flex px-6 py-3 bg-indigo-600 text-white font-medium rounded-xl hover:bg-indigo-700 transition shadow">
                                    Buat Booking Pertama
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="lg:col-span-1">
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 sticky top-8">
                        <div class="text-center mb-6">
                            <div class="w-24 h-24 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-3xl font-bold text-white mx-auto mb-4 border-4 border-white shadow-lg">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">{{ auth()->user()->name }}</h3>
                            <span class="inline-block px-3 py-1 bg-gray-100 rounded-full text-xs font-medium text-gray-500 mt-2">Client Account</span>
                        </div>

                        <div class="space-y-4 mb-8">
                            <div class="flex items-center gap-4 p-3 rounded-xl bg-gray-50 border border-gray-100">
                                <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-gray-400 shadow-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                </div>
                                <div class="overflow-hidden">
                                    <p class="text-xs text-gray-400 uppercase font-bold tracking-wider">Email</p>
                                    <p class="text-sm font-medium text-gray-900 truncate" title="{{ auth()->user()->email }}">{{ auth()->user()->email }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-4 p-3 rounded-xl bg-gray-50 border border-gray-100">
                                <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-gray-400 shadow-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400 uppercase font-bold tracking-wider">Telepon</p>
                                    <p class="text-sm font-medium text-gray-900">{{ auth()->user()->phone ?? '-' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="border-t border-gray-100 pt-6">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-3 border border-red-100 text-red-600 font-medium rounded-xl hover:bg-red-50 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>