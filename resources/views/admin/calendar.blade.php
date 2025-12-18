<x-app-layout>
    <style>
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 10px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #a1a1a1;
}
</style>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Jadwal Studio</h2>
                <p class="text-sm text-gray-500 mt-1">Kelola agenda pemotretan</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 shadow-sm transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="grid lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-white">
                            <div class="flex items-center gap-4">
                                <h3 class="text-lg font-bold text-gray-900 capitalize">
                                    {{ \Carbon\Carbon::parse($date)->translatedFormat('F Y') }}
                                </h3>
                                <div class="flex items-center bg-gray-100 rounded-lg p-1">
                                    <a href="?date={{ \Carbon\Carbon::parse($date)->subMonth()->format('Y-m-d') }}" class="p-1.5 hover:bg-white rounded-md text-gray-500 hover:text-gray-900 hover:shadow-sm transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                                    </a>
                                    <a href="?date={{ \Carbon\Carbon::parse($date)->addMonth()->format('Y-m-d') }}" class="p-1.5 hover:bg-white rounded-md text-gray-500 hover:text-gray-900 hover:shadow-sm transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                    </a>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                @if($isDateFull)
                                    <span class="px-3 py-1 text-xs bg-red-100 text-red-800 rounded-full font-medium">
                                        <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        PENUH
                                    </span>
                                @elseif($isAlmostFull)
                                    <span class="px-3 py-1 text-xs bg-amber-100 text-amber-800 rounded-full font-medium">
                                        <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.342 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                        </svg>
                                        {{ $slotsLeft }} SLOT LAGI
                                    </span>
                                @endif
                                <a href="?date={{ now()->format('Y-m-d') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                                    Hari Ini
                                </a>
                            </div>
                        </div>

                        <div class="p-4">
                            <div class="grid grid-cols-7 mb-2">
                                @foreach(['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'] as $day)
                                    <div class="text-center text-xs font-semibold text-gray-400 uppercase py-2">{{ $day }}</div>
                                @endforeach
                            </div>

                            <div class="grid grid-cols-7 gap-2">
                                @php
                                    $currentMonth = \Carbon\Carbon::parse($date);
                                    $firstDay = $currentMonth->copy()->startOfMonth()->startOfWeek();
                                    $lastDay = $currentMonth->copy()->endOfMonth()->endOfWeek();
                                    $dayIterator = $firstDay->copy();
                                @endphp

                                @while($dayIterator <= $lastDay)
                                    @php
                                        $dayStr = $dayIterator->format('Y-m-d');
                                        $isToday = $dayIterator->isToday();
                                        $isCurrentMonth = $dayIterator->month == $currentMonth->month;
                                        $isSelected = $dayStr == $date;
                                        
                                        // Cek event
                                        $eventData = $monthEvents[$dayStr] ?? null;
                                        $hasEvent = $eventData !== null;
                                        
                                        // Hitung slot untuk tanggal ini
                                        $daySlotCount = \App\Models\Booking::whereDate('event_date', $dayStr)
                                            ->where('status', '!=', 'cancelled')
                                            ->where(function($q) {
                                                $q->whereNotNull('payment_proof')
                                                  ->orWhereNotNull('dp_verified_at');
                                            })
                                            ->count();
                                        $daySlotsLeft = 5 - $daySlotCount;
                                        $dayIsFull = $daySlotsLeft <= 0;
                                        $dayIsAlmostFull = $daySlotsLeft <= 1 && $daySlotsLeft > 0;
                                        
                                        // Warna background indikator
                                        $indicatorColor = 'bg-gray-200';
                                        if ($hasEvent) {
                                            if ($eventData['has_pending']) $indicatorColor = 'bg-amber-400';
                                            if ($eventData['has_confirmed']) $indicatorColor = 'bg-indigo-500';
                                            if ($eventData['has_progress']) $indicatorColor = 'bg-cyan-400';
                                            if ($eventData['has_completed']) $indicatorColor = 'bg-green-500';
                                        }
                                        
                                        // Border berdasarkan slot
                                        $borderClass = 'border-gray-100';
                                        if ($dayIsFull) {
                                            $borderClass = 'border-red-300 ring-1 ring-red-300';
                                        } elseif ($dayIsAlmostFull) {
                                            $borderClass = 'border-amber-300 ring-1 ring-amber-300';
                                        } elseif ($isSelected) {
                                            $borderClass = 'border-indigo-500 ring-1 ring-indigo-500';
                                        }
                                    @endphp

                                    <a href="?date={{ $dayStr }}" 
                                       class="relative h-24 rounded-xl border transition-all duration-200 flex flex-col items-start justify-start p-2 group
                                       {{ $borderClass }}
                                       {{ $isSelected ? 'bg-indigo-50/50 z-10' : 'hover:border-indigo-200 hover:shadow-md bg-white' }}
                                       {{ !$isCurrentMonth ? 'opacity-40 bg-gray-50' : '' }}">
                                        
                                        <span class="text-sm font-medium w-7 h-7 flex items-center justify-center rounded-full mb-1
                                            {{ $isToday ? 'bg-indigo-600 text-white shadow-sm' : ($isSelected ? 'text-indigo-700' : 'text-gray-700') }}">
                                            {{ $dayIterator->day }}
                                        </span>

                                        @if($hasEvent)
                                            <div class="flex flex-col gap-1 w-full mt-auto">
                                                <div class="flex items-center justify-between">
                                                    <div class="flex items-center gap-1">
                                                        <span class="w-2 h-2 rounded-full {{ $indicatorColor }}"></span>
                                                        <span class="text-[10px] font-medium text-gray-600 truncate">
                                                            {{ $eventData['count'] }} sesi
                                                        </span>
                                                    </div>
                                                    @if($daySlotsLeft > 0)
                                                        <span class="text-[9px] font-medium text-green-600 bg-green-50 px-1.5 py-0.5 rounded">
                                                            {{ $daySlotsLeft }}
                                                        </span>
                                                    @else
                                                        <span class="text-[9px] font-medium text-red-600 bg-red-50 px-1.5 py-0.5 rounded">
                                                            Penuh
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        @else
                                            <!-- Tampilkan slot tersisa meski tidak ada event -->
                                            <div class="mt-auto w-full">
                                                @if($daySlotsLeft < 5)
                                                    <span class="text-[9px] font-medium text-gray-500">
                                                        Slot: {{ 5 - $daySlotsLeft }}/5
                                                    </span>
                                                @endif
                                            </div>
                                        @endif
                                    </a>

                                    @php $dayIterator->addDay(); @endphp
                                @endwhile
                            </div>
                        </div>
                        
                        <div class="px-6 py-3 bg-gray-50 border-t border-gray-100 flex gap-4 text-xs text-gray-500 flex-wrap">
                            <div class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-amber-400"></span> Pending</div>
                            <div class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-indigo-500"></span> Confirmed</div>
                            <div class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-cyan-400"></span> In Progress</div>
                            <div class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-green-500"></span> Completed</div>
                            <div class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-gray-300"></span> Lainnya</div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 h-full flex flex-col">
                        <div class="p-4 border-b border-gray-100">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h3 class="font-bold text-lg text-gray-900">
                                        Jadwal {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}
                                    </h3>
                                    <p class="text-sm text-gray-500 mt-1">
                                        {{ $bookings->count() }} sesi pemotretan
                                        @if($slotsLeft > 0)
                                            <span class="ml-2 text-green-600 font-medium">({{ $slotsLeft }} slot tersisa)</span>
                                        @else
                                            <span class="ml-2 text-red-600 font-medium">(PENUH)</span>
                                        @endif
                                    </p>
                                </div>
                                
                                <div class="flex items-center gap-2">
                                    <!-- Filter Status -->
                                    <select id="statusFilter" onchange="filterByStatus(this.value)" 
                                            class="text-sm border-gray-200 rounded-lg px-3 py-1.5 bg-gray-50">
                                        <option value="" {{ $statusFilter == '' ? 'selected' : '' }}>Semua Status</option>
                                        <option value="pending" {{ $statusFilter == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="confirmed" {{ $statusFilter == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                        <option value="in_progress" {{ $statusFilter == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="completed" {{ $statusFilter == 'completed' ? 'selected' : '' }}>Completed</option>
                                    </select>
                                    
                                    <!-- Tombol Share ke WhatsApp -->
                                    <button onclick="shareScheduleToWhatsApp()" 
                                            class="inline-flex items-center gap-2 px-3 py-1.5 bg-green-600 text-white text-xs font-medium rounded-lg hover:bg-green-700 transition shadow-sm">
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/>
                                        </svg>
                                        Share
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="p-4 flex-1 overflow-y-auto max-h-[600px] space-y-4 custom-scrollbar">
                            @if($bookings->count() > 0)
                                @foreach($bookings as $booking)
                                    <div class="group relative bg-white border border-gray-200 rounded-xl p-4 hover:border-indigo-300 hover:shadow-md transition-all duration-200">
                                       

                                        <div class="absolute left-0 top-4 bottom-4 w-1 rounded-r-full
                                            @if($booking->status == 'pending') bg-amber-400
                                            @elseif($booking->status == 'confirmed') bg-indigo-500
                                            @elseif($booking->status == 'in_progress') bg-cyan-400
                                            @elseif($booking->status == 'completed') bg-green-500
                                            @else bg-gray-300 @endif">
                                        </div>

                                        <div class="pl-3">
                                            <div class="flex justify-between items-start mb-2">
                                                <div>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                        {{ date('H:i', strtotime($booking->event_time)) }} WIB
                                                    </span>
                                                    @if($booking->package->duration_hours)
                                                        <span class="text-xs text-gray-500 ml-2">
                                                            - {{ date('H:i', strtotime($booking->event_time . ' + ' . $booking->package->duration_hours . ' hours')) }}
                                                        </span>
                                                    @endif
                                                </div>
                                                
                                                <span class="text-[10px] uppercase font-bold tracking-wider
                                                    @if($booking->status == 'pending') text-amber-600
                                                    @elseif($booking->status == 'confirmed') text-indigo-600
                                                    @elseif($booking->status == 'in_progress') text-cyan-600
                                                    @elseif($booking->status == 'completed') text-green-600
                                                    @else text-gray-500 @endif">
                                                    {{ $booking->status }}
                                                </span>
                                            </div>

                                            <h4 class="font-bold text-gray-900">{{ $booking->user->name }}</h4>
                                            <p class="text-sm text-gray-600 mb-3">{{ $booking->package->name }}</p>

                                            <div class="flex items-center gap-3 mt-3 pt-3 border-t border-gray-50">
                                                <a href="{{ route('admin.bookings.show', $booking) }}" class="text-xs font-medium text-indigo-600 hover:text-indigo-800">Detail</a>
                                                <span class="text-gray-300">|</span>
                                                <a href="https://wa.me/{{ $booking->user->phone }}" target="_blank" class="text-xs font-medium text-green-600 hover:text-green-800 flex items-center">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                                    WhatsApp
                                                </a>
                                                <span class="text-gray-300">|</span>
                                                <button onclick="shareSingleBooking({{ $booking->id }})" 
                                                        class="text-xs font-medium text-gray-600 hover:text-gray-800 flex items-center"
                                                        title="Share detail booking">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                                                    </svg>
                                                    Share
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-10">
                                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                    <h4 class="text-gray-900 font-medium">Tidak ada jadwal</h4>
                                    <p class="text-sm text-gray-500 mt-1">
                                        @if($statusFilter)
                                            Tidak ada booking dengan status "{{ $statusFilter }}" untuk tanggal ini.
                                        @else
                                            Belum ada booking untuk tanggal ini.
                                        @endif
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
// Filter by status
function filterByStatus(status) {
    const url = new URL(window.location.href);
    if (status) {
        url.searchParams.set('status', status);
    } else {
        url.searchParams.delete('status');
    }
    window.location.href = url.toString();
}

// Share whole schedule to WhatsApp
function shareScheduleToWhatsApp() {
    const bookings = @json($whatsappData);
    const date = "{{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}";
    const slotsLeft = {{ $slotsLeft }};
    const slotsUsed = {{ 5 - $slotsLeft }};
    
    // Hitung statistik
    const totalBookings = bookings.length;
    const confirmedCount = bookings.filter(b => b.status === 'confirmed').length;
    const pendingCount = bookings.filter(b => b.status === 'pending').length;
    const progressCount = bookings.filter(b => b.status === 'in_progress').length;
    const completedCount = bookings.filter(b => b.status === 'completed').length;
    
    // Build message
    let message = `📅 *JADWAL PEMOTRETAN - ${date}*\n`;
    message += `📌 Studio: KPMemoPotret\n`;
    message += `━━━━━━━━━━━━━━━━━━━━\n\n`;
    
    if (totalBookings === 0) {
        message += `📭 *TIDAK ADA JADWAL*\n`;
        message += `Tidak ada booking untuk tanggal ini.\n`;
    } else {
        // Add each booking
        bookings.forEach((booking, index) => {
            const timeRange = booking.end_time 
                ? `${booking.time} - ${booking.end_time} WIB`
                : `${booking.time} WIB`;
                
            message += `🕐 ${timeRange}\n`;
            message += `👤 ${booking.name}\n`;
            message += `📦 ${booking.package}\n`;
            message += `💰 ${booking.status_label}\n`;
            message += `📞 ${booking.phone || '-'}\n`;
            message += `📍 ${booking.location || '-'}\n`;
            message += `🎫 #${booking.booking_code}\n`;
            
            if (index < bookings.length - 1) {
                message += `\n━━━━━━━━━━━━━━━━\n\n`;
            }
        });
    }
    
    message += `\n━━━━━━━━━━━━━━━━━━━━\n`;
    message += `📊 *STATISTIK JADWAL*\n`;
    message += `• Total: ${totalBookings} sesi\n`;
    if (confirmedCount > 0) message += `• ✅ Confirmed: ${confirmedCount}\n`;
    if (pendingCount > 0) message += `• ⏳ Pending: ${pendingCount}\n`;
    if (progressCount > 0) message += `• 📸 In Progress: ${progressCount}\n`;
    if (completedCount > 0) message += `• 🎉 Completed: ${completedCount}\n`;
    message += `• Slot: ${slotsUsed}/5 (${slotsLeft > 0 ? `${slotsLeft} tersisa` : 'PENUH'})\n`;
    
    // Encode untuk URL WhatsApp
    const encodedMessage = encodeURIComponent(message);
    const whatsappUrl = `https://wa.me/?text=${encodedMessage}`;
    
    // Buka di tab baru
    window.open(whatsappUrl, '_blank');
}

// Share single booking to WhatsApp
function shareSingleBooking(bookingId) {
    // Get booking data from data attributes on the card
    const card = event.target.closest('.group');
    if (!card) return;
    
    const name = card.querySelector('h4')?.textContent || '';
    const packageName = card.querySelector('p.text-gray-600')?.textContent || '';
    const timeElement = card.querySelector('.bg-gray-100');
    const time = timeElement ? timeElement.textContent.replace('WIB', '').trim() : '';
    const statusElement = card.querySelector('span[class*="text-"]');
    const status = statusElement ? statusElement.textContent.trim() : '';
    
    // Get other data from hidden inputs or data attributes
    const bookingCode = card.querySelector('[data-booking-code]')?.dataset.bookingCode || '';
    const phone = card.querySelector('[data-phone]')?.dataset.phone || '';
    const location = card.querySelector('[data-location]')?.dataset.location || '';
    const date = "{{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}";
    
    // Build message untuk single booking
    let message = `📅 *DETAIL BOOKING*\n`;
    message += `━━━━━━━━━━━━━━━━━━━━\n\n`;
    message += `👤 *Nama:* ${name}\n`;
    message += `📦 *Paket:* ${packageName}\n`;
    message += `📅 *Tanggal:* ${date}\n`;
    message += `🕐 *Waktu:* ${time} WIB\n`;
    message += `📍 *Lokasi:* ${location}\n`;
    message += `📞 *Kontak:* ${phone}\n`;
    message += `🎫 *Kode Booking:* ${bookingCode}\n`;
    message += `💰 *Status:* ${status}\n`;
    message += `\n━━━━━━━━━━━━━━━━━━━━\n`;
    message += `Studio: KPMemoPotret\n`;
    
    // Encode untuk URL WhatsApp
    const encodedMessage = encodeURIComponent(message);
    const whatsappUrl = `https://wa.me/?text=${encodedMessage}`;
    
    // Buka di tab baru
    window.open(whatsappUrl, '_blank');
}

</script>
</x-app-layout>