<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 leading-tight">Kelola Booking</h2>
                <p class="text-sm text-gray-500 mt-1">Pantau dan kelola semua pesanan masuk</p>
            </div>
         
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Summary Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-lg bg-amber-100 flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Pending DP</p>
                            <p class="text-lg font-bold text-gray-900">{{ \App\Models\Booking::where('status', 'pending')->count() }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Confirmed</p>
                            <p class="text-lg font-bold text-gray-900">{{ \App\Models\Booking::where('status', 'confirmed')->count() }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-lg bg-cyan-100 flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">In Progress</p>
                            <p class="text-lg font-bold text-gray-900">{{ \App\Models\Booking::where('status', 'in_progress')->count() }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl p-4 border border-gray-100 shadow-sm">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-lg bg-red-100 flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Full Today</p>
                            <p class="text-lg font-bold text-gray-900">
                                @php
                                    $today = \Carbon\Carbon::today()->format('Y-m-d');
                                    $fullDates = \App\Models\Booking::where('event_date', $today)
                                        ->where('status', '!=', 'cancelled')
                                        ->where(function($q) {
                                            $q->whereNotNull('payment_proof')
                                              ->orWhereNotNull('dp_verified_at');
                                        })
                                        ->count() >= 5 ? 1 : 0;
                                @endphp
                                {{ $fullDates }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search Form -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
                <form method="GET" class="flex flex-col md:flex-row gap-4">
                    <div class="relative flex-1">
                        <input
                            type="text"
                            name="q"
                            value="{{ request('q') }}"
                            placeholder="Cari booking code atau nama paket..."
                            class="w-full bg-gray-50 border border-gray-200 py-3 pl-11 pr-4 rounded-xl text-sm
                                   focus:outline-none focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition"
                        >
                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </div>
                    <button
                        type="submit"
                        class="px-6 py-3 bg-indigo-600 text-white text-sm font-medium rounded-xl
                               hover:bg-indigo-700 transition shadow-sm"
                    >
                        Cari
                    </button>
                </form>
            </div>
            
            <!-- Filter Section -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8">
                <div class="flex items-center gap-2 mb-4 text-gray-800 font-semibold">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    Filter Pencarian
                </div>
                <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                    <!-- Status Filter -->
                    <div class="relative">
                        <select name="status" class="w-full appearance-none bg-gray-50 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded-xl leading-tight focus:outline-none focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>⏳ Menunggu DP</option>
                            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>✅ Confirmed</option>
                            <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>📸 Sedang Proses</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>🎉 Selesai</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>❌ Dibatalkan</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                        </div>
                    </div>

                    <!-- Date Range Filters -->
                    <div>
                        <input type="date" name="start_date" value="{{ request('start_date') }}" 
                               class="w-full bg-gray-50 border border-gray-200 text-gray-700 py-3 px-4 rounded-xl focus:outline-none focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition"
                               placeholder="Dari Tanggal">
                    </div>

                    <div>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" 
                               class="w-full bg-gray-50 border border-gray-200 text-gray-700 py-3 px-4 rounded-xl focus:outline-none focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition"
                               placeholder="Sampai Tanggal">
                    </div>

                    <!-- Slot Status Filter -->
                    <div class="relative">
                        <select name="slot_status" class="w-full appearance-none bg-gray-50 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded-xl leading-tight focus:outline-none focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition">
                            <option value="">Semua Slot</option>
                            <option value="available" {{ request('slot_status') == 'available' ? 'selected' : '' }}>📅 Ada Slot Tersedia</option>
                            <option value="almost_full" {{ request('slot_status') == 'almost_full' ? 'selected' : '' }}>⚠️ Hampir Penuh (≤1 slot)</option>
                            <option value="full" {{ request('slot_status') == 'full' ? 'selected' : '' }}>❌ Tanggal Penuh</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                        </div>
                    </div>

                    <!-- Sort Filter -->
                    <div class="relative">
                        <select name="sort" class="w-full appearance-none bg-gray-50 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded-xl leading-tight focus:outline-none focus:bg-white focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 transition">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>▼ Terbaru Dibuat</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>▲ Terlama Dibuat</option>
                            <option value="event_date_asc" {{ request('sort') == 'event_date_asc' ? 'selected' : '' }}>📅 Tanggal Acara (dekat)</option>
                            <option value="event_date_desc" {{ request('sort') == 'event_date_desc' ? 'selected' : '' }}>📅 Tanggal Acara (jauh)</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>💰 Harga Tertinggi</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>💰 Harga Terendah</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                        </div>
                    </div>

                    <!-- Apply Button -->
                    <div>
                        <button type="submit" class="w-full bg-gray-900 text-white font-medium py-3 px-4 rounded-xl hover:bg-gray-800 transition shadow-md flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            Terapkan Filter
                        </button>
                    </div>
                </form>
            </div>

            <!-- Bookings Table -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">ID & Paket</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Client Info</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Jadwal Acara & Slot</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Total Biaya</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Status</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @php
                                $currentDate = null;
                                $dateSlotCounts = [];
                            @endphp
                            
                            @forelse($bookings as $booking)
                                <!-- Hitung slot untuk tanggal ini (cached) -->
                                @php
                                    if (!isset($dateSlotCounts[$booking->event_date->format('Y-m-d')])) {
                                        $dateSlotCounts[$booking->event_date->format('Y-m-d')] = \App\Models\Booking::where('event_date', $booking->event_date)
                                            ->where('status', '!=', 'cancelled')
                                            ->where(function($q) {
                                                $q->whereNotNull('payment_proof')
                                                  ->orWhereNotNull('dp_verified_at');
                                            })
                                            ->count();
                                    }
                                    $slotsUsed = $dateSlotCounts[$booking->event_date->format('Y-m-d')];
                                    $slotsLeft = 5 - $slotsUsed;
                                @endphp

                                <!-- Group Header per Tanggal -->
                                @if($currentDate != $booking->event_date->format('Y-m-d'))
                                    @php $currentDate = $booking->event_date->format('Y-m-d'); @endphp
                                    <tr class="bg-gray-50 border-t border-gray-200">
                                        <td colspan="6" class="px-6 py-3">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center">
                                                    <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                    <span class="font-semibold text-gray-700">
                                                        {{ $booking->event_date->format('d F Y') }}
                                                    </span>
                                                    <span class="ml-2 text-sm text-gray-500">
                                                        ({{ $slotsUsed }}/5 booking)
                                                    </span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    @if($slotsLeft <= 0)
                                                        <span class="px-3 py-1 text-xs bg-red-100 text-red-800 rounded-full font-medium">
                                                            <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                            </svg>
                                                            PENUH
                                                        </span>
                                                    @elseif($slotsLeft <= 1)
                                                        <span class="px-3 py-1 text-xs bg-amber-100 text-amber-800 rounded-full font-medium">
                                                            <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.342 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                                            </svg>
                                                            {{ $slotsLeft }} SLOT LAGI
                                                        </span>
                                                    @else
                                                        <span class="px-3 py-1 text-xs bg-green-100 text-green-800 rounded-full font-medium">
                                                            <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                            </svg>
                                                            {{ $slotsLeft }} SLOT TERSEDIA
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endif

                                <!-- Booking Row -->
                                <tr class="hover:bg-gray-50/60 transition duration-150">
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span class="font-mono text-xs font-bold text-indigo-600 bg-indigo-50 px-2 py-1 rounded w-fit mb-1">
                                                #{{ $booking->booking_code }}
                                            </span>
                                            <span class="font-medium text-gray-900">{{ $booking->package->name }}</span>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="h-8 w-8 rounded-full bg-gradient-to-tr from-indigo-400 to-purple-400 flex items-center justify-center text-white font-bold text-xs mr-3">
                                                {{ substr($booking->user->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="text-sm font-semibold text-gray-900">{{ $booking->user->name }}</div>
                                                <div class="text-xs text-gray-500 flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                                    {{ $booking->user->phone ?? '-' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <div class="flex items-center gap-2 mb-1">
                                                <span class="text-sm font-medium text-gray-900">{{ $booking->event_date->format('d M Y') }}</span>
                                                <span class="text-xs {{ $slotsLeft <= 0 ? 'bg-red-100 text-red-800' : ($slotsLeft <= 1 ? 'bg-amber-100 text-amber-800' : 'bg-green-100 text-green-800') }} px-2 py-0.5 rounded-full">
                                                    {{ $slotsLeft }}/5
                                                </span>
                                            </div>
                                            <span class="text-xs text-gray-500">{{ date('H:i', strtotime($booking->event_time)) }} WIB</span>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-900">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</div>
                                        @if($booking->remaining_amount > 0)
                                            <span class="text-[10px] text-red-500 font-medium">Kurang: Rp {{ number_format($booking->remaining_amount, 0, ',', '.') }}</span>
                                        @else
                                            <span class="text-[10px] text-green-600 font-medium">Lunas</span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        @php
                                            $statusClass = match($booking->status) {
                                                'pending' => 'bg-amber-100 text-amber-800 border-amber-200',
                                                'pending_lunas' => 'bg-emerald-100 text-emerald-800 border-emerald-200', // Warna baru
                                                'confirmed' => 'bg-blue-100 text-blue-800 border-blue-200',
                                                'in_progress' => 'bg-cyan-100 text-cyan-800 border-cyan-200',
                                                'results_uploaded' => 'bg-purple-100 text-purple-800 border-purple-200',
                                                'completed' => 'bg-green-100 text-green-800 border-green-200',
                                                'cancelled' => 'bg-red-100 text-red-800 border-red-200',
                                                default => 'bg-gray-100 text-gray-800 border-gray-200',
                                            };
                                            
                                            $statusLabel = match($booking->status) {
                                                'pending' => '⏳ Pending',
                                                'pending_lunas' => '💰 Menunggu Pelunasan', // Label baru
                                                'confirmed' => '✅ Confirmed',
                                                'in_progress' => '📸 On Process',
                                                'results_uploaded' => '📤 Uploaded',
                                                'completed' => '🎉 Selesai',
                                                'cancelled' => '❌ Batal',
                                                default => $booking->status,
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border {{ $statusClass }}">
                                            {{ $statusLabel }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            @if($booking->status == 'pending')
                                                <button onclick="verifyPayment({{ $booking->id }})" 
                                                        class="p-2 rounded-lg text-green-600 hover:bg-green-50 hover:text-green-700 transition" 
                                                        title="Verifikasi Cepat">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                </button>
                                            @endif
                                            
                                            <a href="{{ route('admin.bookings.show', $booking) }}" 
                                               class="p-2 rounded-lg text-gray-400 hover:bg-gray-100 hover:text-indigo-600 transition"
                                               title="Lihat Detail">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            </div>
                                            <h3 class="text-lg font-medium text-gray-900">Belum ada booking</h3>
                                            <p class="text-gray-500 mt-1">Coba ubah filter atau buat booking baru.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($bookings->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                        {{ $bookings->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
    function verifyPayment(bookingId) {
        if(confirm('Apakah Anda yakin ingin memverifikasi pembayaran DP ini secara instan?')) {
            const btn = event.currentTarget;
            btn.innerHTML = '<svg class="animate-spin w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
            btn.disabled = true;

            fetch(`/admin/bookings/${bookingId}/verify-payment`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => {
                if (response.ok) {
                    window.location.reload();
                } else {
                    alert('Gagal memverifikasi. Silakan coba lagi.');
                    btn.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
                    btn.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan sistem.');
                btn.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
                btn.disabled = false;
            });
        }
    }
    </script>
</x-app-layout>