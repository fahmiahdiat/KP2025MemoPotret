<x-app-layout>
    {{-- Header Page --}}
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">
                        Booking #{{ $booking->booking_code }}
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">
                        Dibuat pada {{ $booking->created_at->format('d F Y, H:i') }}
                    </p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('client.dashboard') }}"
                        class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition shadow-sm">
                        ← Dashboard
                    </a>

                    @if($booking->status != 'cancelled')
                        <button onclick="printInvoice()"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 rounded-lg text-sm font-medium text-white hover:bg-indigo-700 transition shadow-sm">
                            Cetak Invoice
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- TAMPILAN KHUSUS UNTUK STATUS CANCELLED --}}
            @if($booking->status == 'cancelled')
                <div class="mb-8">
                    <div
                        class="bg-gradient-to-r from-gray-50 to-gray-100 border border-gray-200 rounded-2xl p-6 text-center mb-6">
                        <div
                            class="w-16 h-16 mx-auto mb-4 rounded-full bg-white border-4 border-gray-200 flex items-center justify-center">
                            <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-3">Booking Dibatalkan</h3>

                        <div
                            class="inline-flex items-center gap-3 px-4 py-2 bg-white rounded-lg border border-gray-200 mb-4">
                            @if($booking->cancelled_by == 'admin')
                                <div class="w-6 h-6 rounded-full bg-red-100 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-gray-700">Dibatalkan oleh Admin</span>
                            @else
                                <div class="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-gray-700">Dibatalkan oleh Anda</span>
                            @endif
                        </div>

                        @if($booking->cancel_reason)
                            <div class="max-w-md mx-auto">
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                                    <p class="text-xs text-gray-600 font-medium mb-1">Alasan:</p>
                                    <p class="text-sm text-gray-800 italic">"{{ $booking->cancel_reason }}"</p>
                                </div>
                            </div>
                        @endif

                        <div class="mt-4 text-sm text-gray-500">
                            <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Dibatalkan pada {{ $booking->cancelled_at->format('d F Y, H:i') }}
                        </div>
                    </div>
                </div>
            @endif

            {{-- TAMPILAN NORMAL UNTUK STATUS LAINNYA --}}
            @if($booking->status != 'cancelled' && $booking->status == 'pending')
                <div class="mb-8 bg-amber-50 border border-amber-200 rounded-xl p-4 flex items-start gap-4">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.928-.833-2.698 0L4.342 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-amber-800">Menunggu Pembayaran DP</h3>
                        <p class="text-sm text-amber-700 mt-1">
                            Silakan lakukan pembayaran DP sebesar 50% untuk mengonfirmasi booking Anda. Slot tanggal belum
                            aman sampai DP terverifikasi.
                        </p>
                    </div>
                </div>
            @endif

            <div class="grid lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-8">

                    {{-- DETAIL LAYANAN --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                            <h3 class="font-bold text-lg text-gray-900">Detail Layanan</h3>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                                @if($booking->status == 'pending') bg-amber-100 text-amber-800
                                @elseif($booking->status == 'confirmed') bg-blue-100 text-blue-800
                                @elseif($booking->status == 'in_progress') bg-purple-100 text-purple-800
                                @elseif($booking->status == 'completed') bg-green-100 text-green-800
                                @elseif($booking->status == 'cancelled') bg-gray-100 text-gray-700
                                @endif">
                                @if($booking->status == 'cancelled')
                                    Dibatalkan
                                @else
                                    {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                                @endif
                            </span>
                        </div>

                        <div class="p-6">
                            <div class="flex flex-col md:flex-row justify-between gap-6 mb-8">
                                <div>
                                    <p class="text-sm text-gray-500 mb-1">Paket Dipilih</p>
                                    <div class="font-bold text-xl text-gray-900">{{ $booking->package->name }}</div>
                                    <div class="text-sm text-indigo-600 font-medium mt-1">
                                        Rp {{ number_format($booking->total_amount, 0, ',', '.') }}
                                    </div>
                                </div>
                                <div class="text-right md:text-left">
                                    <p class="text-sm text-gray-500 mb-1">Tanggal Acara</p>
                                    <div class="font-bold text-lg text-gray-900 flex items-center gap-2">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        {{ $booking->event_date->format('d F Y') }}
                                    </div>
                                    <div class="text-sm text-gray-600 mt-1 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ date('H:i', strtotime($booking->event_time)) }} WIB
                                        ({{ $booking->package->duration_hours }} Jam)
                                    </div>
                                </div>
                            </div>

                            <div class="grid md:grid-cols-2 gap-8">
                                <div>
                                    <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wide mb-3">Lokasi &
                                        Catatan</h4>
                                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 h-full">
                                        <div class="mb-4">
                                            <p class="text-xs text-gray-500 mb-1">Lokasi</p>
                                            <p class="text-sm text-gray-800 font-medium">{{ $booking->event_location }}
                                            </p>
                                        </div>
                                        @if($booking->notes)
                                            <div>
                                                <p class="text-xs text-gray-500 mb-1">Catatan Tambahan</p>
                                                <p class="text-sm text-gray-700 italic">"{{ $booking->notes }}"</p>
                                            </div>
                                        @else
                                            <p class="text-sm text-gray-400 italic">Tidak ada catatan khusus</p>
                                        @endif
                                    </div>
                                </div>

                                <div>
                                    <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wide mb-3">Fasilitas
                                        Paket</h4>
                                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 h-full">
                                        @if($booking->package->features && is_array($booking->package->features))
                                            <ul class="space-y-2">
                                                @foreach($booking->package->features as $feature)
                                                    <li class="flex items-start text-sm text-gray-600">
                                                        <svg class="w-4 h-4 text-green-500 mr-2 mt-0.5 flex-shrink-0"
                                                            fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd"
                                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                        {{ $feature }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <p class="text-sm text-gray-400 italic">Detail fasilitas tidak tersedia</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- RINCIAN PEMBAYARAN (HANYA UNTUK NON-CANCELLED) --}}
                    @if($booking->status != 'cancelled')
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                                <h3 class="font-bold text-lg text-gray-900">Rincian Pembayaran</h3>
                            </div>
                            <div class="p-6">
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
                                    <div class="bg-white p-4 rounded-xl border border-gray-200">
                                        <p class="text-xs text-gray-500 uppercase font-bold tracking-wider mb-1">Total Biaya
                                        </p>
                                        <p class="text-xl font-bold text-gray-900">Rp
                                            {{ number_format($booking->total_amount, 0, ',', '.') }}
                                        </p>
                                    </div>

                                    <div
                                        class="p-4 rounded-xl border {{ $booking->dp_amount > 0 ? 'bg-blue-50 border-blue-100' : 'bg-amber-50 border-amber-100' }}">
                                        <p
                                            class="text-xs {{ $booking->dp_amount > 0 ? 'text-blue-600' : 'text-amber-600' }} uppercase font-bold tracking-wider mb-1">
                                            Down Payment (50%)</p>
                                        <p
                                            class="text-xl font-bold {{ $booking->dp_amount > 0 ? 'text-blue-700' : 'text-amber-700' }}">
                                            @if($booking->dp_amount > 0)
                                                Rp {{ number_format($booking->dp_amount, 0, ',', '.') }}
                                            @else
                                                Belum Dibayar
                                            @endif
                                        </p>
                                        @if(!$booking->dp_uploaded_at)
                                            <div class="text-xs font-medium text-amber-600 mt-1">
                                                ⏳ Belum Dibayar
                                            </div>
                                        @elseif($booking->status === 'pending')
                                            <div class="flex items-center mt-1 text-xs font-medium text-amber-600">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1 4V8h2v6H9z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                Menunggu Verifikasi Admin
                                            </div>
                                        @elseif($booking->status === 'confirmed')
                                            <div class="flex items-center mt-1 text-xs font-medium text-blue-600">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                DP Terverifikasi
                                            </div>
                                        @endif

                                    </div>

                                    <div class="bg-white p-4 rounded-xl border border-gray-200">
                                        <p class="text-xs text-gray-500 uppercase font-bold tracking-wider mb-1">Sisa
                                            Tagihan</p>
                                        <p class="text-xl font-bold text-gray-900">Rp
                                            {{ number_format($booking->remaining_amount, 0, ',', '.') }}
                                        </p>
                                        <p class="text-xs text-red-500 mt-1 font-medium">
                                            {{ $booking->remaining_amount > 0 ? 'Wajib lunas H-7 acara' : 'Lunas' }}
                                        </p>
                                    </div>
                                </div>

                                @if($booking->status == 'pending' && $booking->dp_amount == 0)
                                    <div class="bg-blue-50 border border-blue-100 rounded-xl p-6">
                                        <div class="flex gap-4">
                                            <div class="flex-shrink-0">
                                                <div
                                                    class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                                        </path>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="flex-1">
                                                <h4 class="text-lg font-bold text-blue-900 mb-1">Instruksi Pembayaran DP</h4>
                                                <p class="text-sm text-blue-700 mb-4">Silakan transfer DP sebesar <strong>Rp
                                                        {{ number_format($booking->total_amount * 0.5, 0, ',', '.') }}</strong>
                                                    ke rekening berikut:</p>

                                                <div class="grid sm:grid-cols-2 gap-4">
                                                    <div class="bg-white p-3 rounded-lg border border-blue-100">
                                                        <p class="text-xs text-gray-500 uppercase">Bank BCA</p>
                                                        <p class="font-mono font-bold text-lg text-gray-900">123-456-7890</p>
                                                        <p class="text-xs text-gray-600">a.n Memo Potret Studio</p>
                                                    </div>
                                                    <div class="bg-white p-3 rounded-lg border border-blue-100">
                                                        <p class="text-xs text-gray-500 uppercase">Kode Referensi</p>
                                                        <p class="font-mono font-bold text-lg text-blue-600">
                                                            {{ $booking->booking_code }}
                                                        </p>
                                                        <p class="text-xs text-gray-600">Cantumkan di berita transfer</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if ($booking->status !== 'cancelled')
                        <section class="rounded-2xl border border-gray-200 bg-white shadow-sm overflow-hidden">

                            {{-- TOP STATUS BAR --}}
                            <div class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        Hasil Dokumentasi
                                    </h3>
                                    <p class="text-sm text-gray-500">
                                        Akses dan status hasil foto booking Anda
                                    </p>
                                </div>                               
                            </div>

                            <div class="p-6 space-y-6">

                                {{-- STATE: BELUM ADA HASIL --}}
                                @if (!in_array($booking->status, ['results_uploaded', 'completed', 'pending_lunas']))
                                    
                                    <div class="grid sm:grid-cols-3 gap-4">
                                        <div class="sm:col-span-2 rounded-xl border border-gray-200 p-5 bg-gray-50">
                                            
                                            {{-- KONDISI 1: SEBELUM ACARA (Pending / Confirmed) --}}
                                            @if(in_array($booking->status, ['pending', 'confirmed']))
                                                <p class="text-sm font-medium text-gray-900 flex items-center gap-2">
                                                    <span class="text-xl">📅</span> Menunggu Pelaksanaan Acara
                                                </p>
                                                <p class="text-sm text-gray-600 mt-2 leading-relaxed">
                                                    Hasil dokumentasi foto dan video akan mulai diproses setelah acara Anda selesai dilaksanakan pada tanggal <strong>{{ $booking->event_date->format('d F Y') }}</strong>.
                                                </p>
                                            
                                            {{-- KONDISI 2: SEDANG DIPROSES (In Progress) --}}
                                            @elseif($booking->status == 'in_progress')
                                                <p class="text-sm font-medium text-gray-900 flex items-center gap-2">
                                                    <span class="text-xl animate-pulse">📷</span> Dokumentasi Sedang Diproses
                                                </p>
                                                <p class="text-sm text-gray-600 mt-2 leading-relaxed">
                                                    Acara telah selesai! Tim kami saat ini sedang melakukan proses seleksi (curating) dan editing foto terbaik Anda. Notifikasi akan dikirim setelah hasil siap diunduh.
                                                </p>
                                            @endif

                                        </div>

                                        <div class="rounded-xl border border-dashed border-gray-300 p-5 text-center text-sm text-gray-500 flex flex-col items-center justify-center">
                                            <svg class="w-8 h-8 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                            <p>Link download belum tersedia</p>
                                        </div>
                                    </div>

                                @endif

                                {{-- STATE: HASIL SUDAH ADA --}}
                                @if (in_array($booking->status, ['results_uploaded', 'completed', 'pending_lunas']))
                                    <div class="grid lg:grid-cols-3 gap-6">

                                        {{-- LEFT: INFO & CATATAN --}}
                                        <div class="lg:col-span-2 space-y-4">

                                            <div class="rounded-xl border border-gray-200 p-5">
                                                <p class="text-sm font-medium text-gray-900">
                                                    File Dokumentasi
                                                </p>
                                                <p class="text-sm text-gray-600 mt-1">
                                                    Semua hasil foto telah disimpan secara aman.
                                                </p>
                                            </div>

                                            @if ($booking->admin_notes)
                                                <div class="rounded-xl border border-gray-200 bg-gray-50 p-5">
                                                    <p class="text-xs font-semibold text-gray-500 uppercase mb-1">
                                                        Catatan Admin
                                                    </p>
                                                    <p class="text-sm text-gray-700 leading-relaxed italic">
                                                        "{{ $booking->admin_notes }}"
                                                    </p>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- RIGHT: ACTION PANEL --}}
                                        <div class="rounded-xl border border-gray-200 p-5 flex flex-col justify-between">

                                            {{-- LOCKED --}}
                                            @if ($booking->remaining_amount > 0)
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900">
                                                        Akses Terkunci
                                                    </p>
                                                    <p class="text-sm text-gray-600 mt-1">
                                                        Sisa pembayaran:
                                                        <span class="font-semibold text-red-600">
                                                            Rp {{ number_format($booking->remaining_amount, 0, ',', '.') }}
                                                        </span>
                                                    </p>
                                                </div>

                                                <div class="mt-4">
                                                    @if ($booking->status === 'pending_lunas')
                                                        <div class="w-full text-center px-4 py-2 rounded-lg bg-amber-100 text-amber-800 text-sm font-medium">
                                                            Pembayaran sedang diverifikasi
                                                        </div>
                                                    @else
                                                        <button
                                                            onclick="document.getElementById('pelunasanModal').showModal()"
                                                            class="w-full px-4 py-2.5 rounded-lg bg-green-600 text-white text-sm font-medium hover:bg-green-700 transition"
                                                        >
                                                            Upload Bukti Pelunasan
                                                        </button>
                                                    @endif
                                                </div>

                                                {{-- UNLOCKED --}}
                                            @else
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900">
                                                        Akses Unduhan
                                                    </p>
                                                    <p class="text-sm text-gray-600 mt-1">
                                                        Anda dapat mengunduh seluruh hasil foto.
                                                    </p>
                                                </div>

                                                <div class="mt-4 space-y-3">
                                                    <a
                                                        href="{{ $booking->drive_link }}"
                                                        target="_blank"
                                                        class="block text-sm font-medium text-indigo-600 hover:underline break-all"
                                                    >
                                                        {{ $booking->drive_link }}
                                                    </a>

                                                    <a
                                                        href="{{ $booking->drive_link }}"
                                                        target="_blank"
                                                        class="block w-full text-center px-4 py-2.5 rounded-lg bg-green-600 text-white text-sm font-medium hover:bg-green-700 transition"
                                                    >
                                                        Download Semua Foto
                                                    </a>
                                                </div>
                                            @endif

                                        </div>
                                    </div>
                                @endif

                            </div>
                        </section>
                    @endif

                </div>

                <div class="lg:col-span-1 space-y-6">

                    {{-- TIMELINE STATUS --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="font-bold text-lg text-gray-900 mb-6">Status Booking</h3>
                        <div class="relative pl-4 border-l-2 border-gray-100 space-y-8">
                            
                            @foreach($timelineSteps as $step)
                                <div class="relative group">
                                    {{-- DOT INDICATOR --}}
                                    <div class="absolute -left-[21px] top-1 w-4 h-4 rounded-full border-2 border-white transition-all duration-500
                                        {{ $step['is_active'] ? 'bg-indigo-600 ring-4 ring-indigo-50 scale-110' : 'bg-gray-200' }}">
                                    </div>

                                    {{-- TEXT CONTENT --}}
                                    <div class="transition-opacity duration-500 {{ $step['is_active'] ? 'opacity-100' : 'opacity-40 grayscale' }}">
                                        <h4 class="text-sm font-bold {{ $step['is_active'] ? 'text-indigo-900' : 'text-gray-500' }}">
                                            {{ $step['title'] }}
                                        </h4>
                                        <p class="text-xs text-gray-500 mt-0.5">{{ $step['description'] }}</p>
                                        
                                        @if($step['is_active'] && $step['date'] != '-')
                                            <div class="flex items-center mt-1 text-xs font-medium text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded w-fit">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                {{ $step['date'] }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                    {{-- AKSI CEPAT (HANYA UNTUK NON-CANCELLED) --}}
                    @if($booking->status != 'cancelled')
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                            <h3 class="font-bold text-lg text-gray-900 mb-4">Aksi Cepat</h3>

                            <div class="space-y-3">

                                @if($booking->status === 'results_uploaded' && $booking->remaining_amount > 0)
                                    <button onclick="document.getElementById('pelunasanModal').showModal()"
                                        class="w-full flex items-center justify-center px-4 py-3 bg-green-600 text-white font-medium rounded-xl hover:bg-green-700 transition shadow-md hover:shadow-lg">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                            </path>
                                        </svg>
                                        Bayar Pelunasan
                                    </button>
                                @endif

                                <a href="https://wa.me/6281234567890?text=Halo%20Admin,%20saya%20mau%20tanya%20tentang%20booking%20{{ $booking->booking_code }}"
                                    target="_blank"
                                    class="w-full flex items-center justify-center px-4 py-3 bg-white border border-gray-200 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition">
                                    <svg class="w-5 h-5 mr-2 text-green-500" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z" />
                                    </svg>
                                    Hubungi Admin
                                </a>

                                @if($canCancel && $booking->status != 'cancelled')
                                    <button onclick="document.getElementById('cancelModal').showModal()"
                                        class="w-full flex items-center justify-center px-4 py-3 border border-red-100 text-red-600 font-medium rounded-xl hover:bg-red-50 transition">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        Batalkan Booking
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

    {{-- MODAL UPLOAD DP (HANYA UNTUK NON-CANCELLED) --}}
    @if($booking->status != 'cancelled')
        <dialog id="uploadModal"
            class="bg-transparent backdrop:bg-black/50 p-0 w-full max-w-lg rounded-2xl shadow-2xl open:animate-fade-in-up">
            <div class="bg-white">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                    <h3 class="font-bold text-gray-900">Upload Bukti DP</h3>
                    <button onclick="document.getElementById('uploadModal').close()"
                        class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
                </div>
                <form action="{{ route('client.bookings.upload-payment', $booking) }}" method="POST"
                    enctype="multipart/form-data" class="p-6">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">File Bukti Transfer</label>
                        <input type="file" name="payment_proof"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                            accept="image/*,.pdf" required>
                        <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, PDF (Max 2MB)</p>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                        <textarea name="payment_notes" rows="2"
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                            placeholder="Contoh: Transfer a.n Budi"></textarea>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="document.getElementById('uploadModal').close()"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700 shadow-sm">Upload</button>
                    </div>
                </form>
            </div>
        </dialog>

        <dialog id="pelunasanModal"
            class="bg-transparent backdrop:bg-black/50 p-0 w-full max-w-lg rounded-2xl shadow-2xl open:animate-fade-in-up">
            <div class="bg-white">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                    <h3 class="font-bold text-gray-900">Upload Bukti Pelunasan</h3>
                    <button onclick="document.getElementById('pelunasanModal').close()"
                        class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
                </div>
                <form action="{{ route('client.bookings.upload-remaining-payment', $booking) }}" method="POST"
                    enctype="multipart/form-data" class="p-6">
                    @csrf
                    <div class="bg-green-50 border border-green-100 rounded-lg p-4 mb-4">
                        <p class="text-sm text-green-800">Sisa yang harus dibayar:</p>
                        <p class="text-xl font-bold text-green-700">Rp
                            {{ number_format($booking->remaining_amount, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">File Bukti Transfer</label>
                        <input type="file" name="payment_proof"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100"
                            accept="image/*,.pdf" required>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                        <textarea name="remaining_payment_notes" rows="2"
                            class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 text-sm"></textarea>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="document.getElementById('pelunasanModal').close()"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 shadow-sm">Upload</button>
                    </div>
                </form>
            </div>
        </dialog>

        {{-- MODAL CANCEL DENGAN FORM SEDERHANA --}}
        <dialog id="cancelModal"
            class="bg-transparent backdrop:bg-black/50 p-0 w-full max-w-lg rounded-2xl shadow-2xl open:animate-fade-in-up">
            <div class="bg-white">
                <div class="px-6 py-4 border-b border-red-100 flex justify-between items-center bg-red-50">
                    <h3 class="font-bold text-red-900">Konfirmasi Pembatalan</h3>
                    <button onclick="document.getElementById('cancelModal').close()"
                        class="text-red-400 hover:text-red-600 text-2xl leading-none">&times;</button>
                </div>
                <form action="{{ route('client.bookings.cancel', $booking) }}" method="POST" class="p-6">
                    @csrf
                    @method('DELETE')
                    <div class="bg-red-50 p-4 rounded-lg border border-red-100 mb-6">
                        <div class="flex gap-3">
                            <svg class="w-6 h-6 text-red-600 flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.928-.833-2.698 0L4.342 16.5c-.77.833.192 2.5 1.732 2.5z">
                                </path>
                            </svg>
                            <div>
                                <h4 class="font-bold text-red-800 text-sm">Peringatan!</h4>
                                <p class="text-xs text-red-700 mt-1">
                                    Pembatalan booking akan menyebabkan <strong>DP 50% HANGUS</strong>.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Alasan singkat</label>
                        <input type="text" name="cancel_reason"
                            class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500 text-sm"
                            placeholder="Contoh: Jadwal berubah" required>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="document.getElementById('cancelModal').close()"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50">Kembali</button>
                        <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 shadow-sm">Ya,
                            Batalkan</button>
                    </div>
                </form>
            </div>
        </dialog>
    @endif

    @push('scripts')
        <script>
            function printInvoice() {
                const printWindow = window.open('', '_blank');
                printWindow.document.write(`
                        <html>
                        <head>
                            <title>Invoice #{{ $booking->booking_code }}</title>
                            <style>
                                body { font-family: sans-serif; padding: 40px; color: #333; }
                                .header { display: flex; justify-content: space-between; border-bottom: 2px solid #eee; padding-bottom: 20px; margin-bottom: 30px; }
                                .logo { font-size: 24px; font-weight: bold; color: #4F46E5; }
                                .invoice-details { text-align: right; }
                                .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 40px; margin-bottom: 40px; }
                                .label { font-size: 12px; color: #666; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 5px; }
                                .value { font-size: 16px; font-weight: 500; }
                                table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
                                th { text-align: left; padding: 12px; background: #f9fafb; border-bottom: 1px solid #eee; font-size: 12px; text-transform: uppercase; color: #666; }
                                td { padding: 12px; border-bottom: 1px solid #eee; }
                                .total-row td { font-weight: bold; font-size: 18px; border-top: 2px solid #333; }
                                .footer { text-align: center; font-size: 12px; color: #999; margin-top: 50px; }
                            </style>
                        </head>
                        <body>
                            <div class="header">
                                <div class="logo">MEMO POTRET</div>
                                <div class="invoice-details">
                                    <h1>INVOICE</h1>
                                    <p>#{{ $booking->booking_code }}</p>
                                    <p>{{ date('d M Y') }}</p>
                                </div>
                            </div>

                            <div class="info-grid">
                                <div>
                                    <div class="label">Tagihan Untuk:</div>
                                    <div class="value">{{ auth()->user()->name }}</div>
                                    <div>{{ auth()->user()->email }}</div>
                                </div>
                                <div style="text-align: right;">
                                    <div class="label">Status:</div>
                                    <div class="value" style="color: {{ $booking->status == 'cancelled' ? '#DC2626' : ($booking->remaining_amount > 0 ? '#DC2626' : '#059669') }}">
                                        {{ $booking->status == 'cancelled' ? 'DIBATALKAN' : ($booking->remaining_amount > 0 ? 'BELUM LUNAS' : 'LUNAS') }}
                                    </div>
                                </div>
                            </div>

                            <table>
                                <thead>
                                    <tr>
                                        <th>Deskripsi Layanan</th>
                                        <th>Harga</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <strong>{{ $booking->package->name }}</strong><br>
                                            <small>{{ $booking->event_date->format('d F Y') }} • {{ $booking->package->duration_hours }} Jam</small>
                                        </td>
                                        <td>Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td>Subtotal</td>
                                        <td>Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td>Sudah Dibayar (DP)</td>
                                        <td>- Rp {{ number_format($booking->dp_amount, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr class="total-row">
                                        <td>Sisa Tagihan</td>
                                        <td>Rp {{ number_format($booking->remaining_amount, 0, ',', '.') }}</td>
                                    </tr>
                                </tfoot>
                            </table>

                            <div class="footer">
                                <p>Terima kasih telah mempercayakan momen spesial Anda kepada Memo Potret.</p>
                            </div>

                            <script>window.print(); window.onafterprint = function(){ window.close() };<\/script>
                        </body>
                        </html>
                    `);
                printWindow.document.close();
            }
        </script>
        <style>
            dialog[open] {
                animation: fade-in 0.3s ease-out;
            }

            @keyframes fade-in {
                from {
                    opacity: 0;
                    transform: scale(0.95);
                }

                to {
                    opacity: 1;
                    transform: scale(1);
                }
            }

            dialog::backdrop {
                background: rgba(0, 0, 0, 0.5);
                backdrop-filter: blur(2px);
            }
        </style>
    @endpush
</x-app-layout>