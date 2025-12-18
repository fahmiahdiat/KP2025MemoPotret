<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.bookings.index') }}" class="text-gray-500 hover:text-gray-700 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </a>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Detail Booking</h2>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-sm font-medium text-gray-600">#{{ $booking->booking_code }}</span>
                            <span class="text-sm text-gray-400">•</span>
                            <span class="text-sm text-gray-500">{{ $booking->created_at->format('d M Y, H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.bookings.index') }}"
                    class="px-4 py-2.5 bg-white text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition font-medium flex items-center shadow-sm">
                    ← Kembali ke Daftar
                </a>
                <a href="https://wa.me/{{ $booking->user->phone }}" target="_blank"
                    class="px-4 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium flex items-center shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z" />
                    </svg>
                    Chat Client
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Status Banner -->
            <div class="mb-8">
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-200">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                        <div class="flex items-center gap-4">
                            <div
                                class="p-3 rounded-xl bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-100">
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900">{{ $booking->package->name }}</h1>
                                <p class="text-gray-600 mt-1">{{ $booking->event_date->format('l, d F Y') }} •
                                    {{ date('H:i', strtotime($booking->event_time)) }} WIB
                                </p>
                            </div>
                        </div>

                        <div class="flex flex-col items-end">
                            <div class="mb-3">
                                @if($booking->status == 'pending')
                                    <span
                                        class="px-4 py-2 bg-gradient-to-r from-yellow-500 to-yellow-400 text-white rounded-full text-sm font-semibold shadow-sm">
                                        ⏳ Menunggu DP
                                    </span>
                                @elseif($booking->status == 'confirmed')
                                    <span
                                        class="px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-400 text-white rounded-full text-sm font-semibold shadow-sm">
                                        ✅ Dikonfirmasi
                                    </span>
                                @elseif($booking->status == 'in_progress')
                                    <span
                                        class="px-4 py-2 bg-gradient-to-r from-indigo-500 to-purple-400 text-white rounded-full text-sm font-semibold shadow-sm">
                                        🎬 Dalam Proses
                                    </span>
                                @elseif($booking->status == 'results_uploaded')
                                    <span
                                        class="px-4 py-2 bg-gradient-to-r from-purple-500 to-pink-400 text-white rounded-full text-sm font-semibold shadow-sm">
                                        📤 Hasil Diupload
                                    </span>
                                @elseif($booking->status == 'pending_lunas')
                                    <span
                                        class="px-4 py-2 bg-gradient-to-r from-orange-500 to-orange-400 text-white rounded-full text-sm font-semibold shadow-sm">
                                        💰 Menunggu Pelunasan
                                    </span>
                                @elseif($booking->status == 'completed')
                                    <span
                                        class="px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-400 text-white rounded-full text-sm font-semibold shadow-sm">
                                        ✨ Selesai
                                    </span>
                                @elseif($booking->status == 'cancelled')
                                    <span
                                        class="px-4 py-2 bg-gradient-to-r from-red-500 to-red-400 text-white rounded-full text-sm font-semibold shadow-sm">
                                        ❌ Dibatalkan
                                    </span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-500">{{ $booking->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid lg:grid-cols-3 gap-8">
                <!-- Left Column - Main Content -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Client & Package Section -->
                    <div class="grid md:grid-cols-2 gap-6">
                        <!-- Client Information -->
                        <div
                            class="bg-white rounded-2xl shadow-lg p-6 border border-gray-200 hover:shadow-xl transition-shadow duration-300">
                            <div class="flex items-center mb-6">
                                <div
                                    class="p-3 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-xl mr-4 shadow-sm">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-bold text-xl text-gray-900">Informasi Client</h3>
                                    <p class="text-sm text-gray-500">Detail pelanggan</p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div
                                    class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        <span class="text-gray-600">Nama</span>
                                    </div>
                                    <span class="font-semibold text-gray-900">{{ $booking->user->name }}</span>
                                </div>

                                <div
                                    class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                        <span class="text-gray-600">Email</span>
                                    </div>
                                    <span class="font-semibold text-gray-900">{{ $booking->user->email }}</span>
                                </div>

                                <div
                                    class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                        <span class="text-gray-600">Telepon</span>
                                    </div>
                                    <span class="font-semibold text-gray-900">{{ $booking->user->phone }}</span>
                                </div>

                                <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-xl">
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-700">Total Booking</span>
                                        <span class="px-3 py-1 bg-blue-100 text-blue-700 font-semibold rounded-full">
                                            {{ $booking->user->bookings->count() }} kali
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Package Information -->
                        <div
                            class="bg-white rounded-2xl shadow-lg p-6 border border-gray-200 hover:shadow-xl transition-shadow duration-300">
                            <div class="flex items-center mb-6">
                                <div
                                    class="p-3 bg-gradient-to-br from-green-500 to-emerald-500 rounded-xl mr-4 shadow-sm">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-bold text-xl text-gray-900">Paket Layanan</h3>
                                    <p class="text-sm text-gray-500">Detail paket yang dipilih</p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div
                                    class="p-4 bg-gradient-to-r from-indigo-50 to-purple-50 border border-indigo-100 rounded-xl">
                                    <div class="text-center">
                                        <p class="text-sm text-gray-500 mb-1">Nama Paket</p>
                                        <p class="font-bold text-xl text-indigo-700">{{ $booking->package->name }}</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div class="p-4 bg-gray-50 border border-gray-200 rounded-xl">
                                        <div class="text-center">
                                            <p class="text-sm text-gray-500 mb-1">Harga</p>
                                            <p class="text-xl font-bold text-green-600">
                                                Rp {{ number_format($booking->package->price, 0, ',', '.') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="p-4 bg-gray-50 border border-gray-200 rounded-xl">
                                        <div class="text-center">
                                            <p class="text-sm text-gray-500 mb-1">Durasi</p>
                                            <p class="text-xl font-bold text-blue-600">
                                                {{ $booking->package->duration_hours }} jam
                                            </p>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- Event Details -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-200">
                        <div class="flex items-center mb-6">
                            <div class="p-3 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-xl mr-4 shadow-sm">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-xl text-gray-900">Detail Acara</h3>
                                <p class="text-sm text-gray-500">Informasi jadwal dan lokasi</p>
                            </div>
                        </div>

                        <div class="grid md:grid-cols-3 gap-4 mb-8">
                            <div
                                class="bg-gradient-to-br from-blue-50 to-blue-100 p-5 rounded-xl border border-blue-200">
                                <div class="text-center">
                                    <div class="flex items-center justify-center mb-2">
                                        <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span class="text-sm font-medium text-blue-700">Tanggal</span>
                                    </div>
                                    <div class="text-2xl font-bold text-gray-900">
                                        {{ $booking->event_date->format('d F Y') }}
                                    </div>
                                    <div class="text-sm text-gray-500 mt-1">{{ $booking->event_date->format('l') }}
                                    </div>
                                </div>
                            </div>

                            <div
                                class="bg-gradient-to-br from-green-50 to-emerald-100 p-5 rounded-xl border border-green-200">
                                <div class="text-center">
                                    <div class="flex items-center justify-center mb-2">
                                        <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="text-sm font-medium text-green-700">Waktu Mulai</span>
                                    </div>
                                    <div class="text-2xl font-bold text-gray-900">
                                        {{ date('H:i', strtotime($booking->event_time)) }}
                                    </div>
                                    <div class="text-sm text-gray-500 mt-1">WIB</div>
                                </div>
                            </div>

                            <div
                                class="bg-gradient-to-br from-purple-50 to-violet-100 p-5 rounded-xl border border-purple-200">
                                <div class="text-center">
                                    <div class="flex items-center justify-center mb-2">
                                        <svg class="w-5 h-5 text-purple-600 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="text-sm font-medium text-purple-700">Durasi</span>
                                    </div>
                                    <div class="text-2xl font-bold text-gray-900">
                                        {{ $booking->package->duration_hours }} jam
                                    </div>
                                    <div class="text-sm text-gray-500 mt-1">kerja</div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <p class="text-sm font-medium text-gray-700 mb-2 flex items-center">
                                    <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Lokasi Acara
                                </p>
                                <div
                                    class="p-4 bg-gray-50 border border-gray-200 rounded-xl hover:bg-gray-100 transition">
                                    <p class="text-gray-700">{{ $booking->event_location }}</p>
                                </div>
                            </div>

                            @if($booking->notes)
                                <div>
                                    <p class="text-sm font-medium text-gray-700 mb-2 flex items-center">
                                        <svg class="w-5 h-5 text-blue-400 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.34 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                        </svg>
                                        Catatan Client
                                    </p>
                                    <div class="p-4 bg-blue-50 border border-blue-200 rounded-xl">
                                        <p class="text-gray-700">{{ $booking->notes }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Payment Information Section - SUDAH DIPERBAIKI -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-200">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center">
                                <div
                                    class="p-3 bg-gradient-to-br from-green-500 to-emerald-500 rounded-xl mr-4 shadow-sm">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-bold text-xl text-gray-900">Informasi Pembayaran</h3>
                                    <p class="text-sm text-gray-500">Status dan detail pembayaran</p>
                                </div>
                            </div>

                            @if($booking->status == 'pending')
                                <button onclick="verifyPayment({{ $booking->id }})"
                                    class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-blue-500 text-white rounded-lg hover:from-blue-700 hover:to-blue-600 transition font-medium shadow-md hover:shadow-lg">
                                    Verifikasi DP
                                </button>
                            @endif
                        </div>

                        <!-- Statistik Pembayaran -->
                        <div class="grid md:grid-cols-3 gap-4 mb-8">
                            <!-- Total Biaya -->
                            <div
                                class="bg-gradient-to-br from-gray-50 to-gray-100 p-5 rounded-xl border border-gray-200 shadow-sm">
                                <div class="text-center">
                                    <div class="text-sm text-gray-600 font-medium mb-2">Total Biaya</div>
                                    <div class="text-2xl font-bold text-green-600">
                                        Rp {{ number_format($booking->total_amount, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>

                            <!-- DP (50%) -->
                            <div
                                class="bg-gradient-to-br from-blue-50 to-blue-100 p-5 rounded-xl border border-blue-200 shadow-sm">
                                <div class="text-center">
                                    <div class="text-sm text-blue-700 font-medium mb-2">DP (50%)</div>
                                    <div
                                        class="text-xl font-bold {{ $booking->dp_amount > 0 ? 'text-green-600' : 'text-yellow-600' }}">
                                        @if($booking->dp_verified_at)
                                            ✅ Rp {{ number_format($booking->dp_amount, 0, ',', '.') }}
                                        @elseif($booking->payment_proof)
                                            ⏳ Rp {{ number_format($booking->dp_amount, 0, ',', '.') }}
                                        @else
                                            Belum Dibayar
                                        @endif
                                    </div>
                                    @if($booking->dp_verified_at)
                                        <div class="mt-3">
                                            <span
                                                class="px-3 py-1 bg-green-100 text-green-800 text-sm font-medium rounded-full">
                                                ✓ Terverifikasi
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Sisa Tagihan -->
                            <div
                                class="bg-gradient-to-br from-purple-50 to-violet-100 p-5 rounded-xl border border-purple-200 shadow-sm">
                                <div class="text-center">
                                    <div class="text-sm text-purple-700 font-medium mb-2">Sisa Tagihan</div>
                                    <div
                                        class="text-xl font-bold {{ $booking->remaining_amount > 0 ? 'text-red-600' : 'text-green-600' }}">
                                        @if($booking->remaining_amount == 0)
                                            ✅ Rp 0
                                        @else
                                            Rp {{ number_format($booking->remaining_amount, 0, ',', '.') }}
                                        @endif
                                    </div>

                                    <div class="mt-3">
                                        @if($booking->remaining_amount == 0)
                                            <span
                                                class="px-3 py-1 bg-green-100 text-green-800 text-sm font-medium rounded-full">
                                                ✅ LUNAS
                                            </span>
                                        @elseif($booking->remaining_amount > 0 && $booking->remaining_payment_proof)
                                            <span
                                                class="px-3 py-1 bg-yellow-100 text-yellow-800 text-sm font-medium rounded-full">
                                                ⏳ Menunggu Verifikasi
                                            </span>
                                        @else
                                            <span
                                                class="px-3 py-1 {{ $booking->payment_status_class }} text-sm font-medium rounded-full">
                                                {{ $booking->payment_status_text }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Bukti Transfer Section - DIPERBAIKI SESUAI FORMAT YANG ANDA MAU -->
                        <div class="space-y-6">
                            <!-- Bukti Transfer DP -->
                            @if($booking->payment_proof)
                                <div class="border border-gray-200 rounded-xl overflow-hidden">
                                    <div class="bg-blue-50 px-4 py-3 border-b border-blue-100">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span class="font-medium text-gray-900">Bukti Transfer DP (50%)</span>
                                            </div>
                                            @if($booking->dp_verified_at)
                                                <span
                                                    class="px-3 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">
                                                    ✓ Sudah Diverifikasi
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="p-4">
                                        <div class="flex items-center justify-between">
                                            <div class="space-y-2 flex-1">
                                                @if($booking->payment_notes)
                                                    <p class="text-sm text-gray-600">
                                                        <span class="font-medium">Catatan Client:</span>
                                                        {{ $booking->payment_notes }}
                                                    </p>
                                                @endif

                                                <!-- TIMESTAMP DP LANGSUNG DI CARD -->
                                                <div class="text-xs text-gray-500 space-y-1">
                                                    @if($booking->dp_uploaded_at)
                                                        <div class="flex items-center">
                                                            <svg class="w-3 h-3 mr-1 text-blue-500" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                                                            </svg>
                                                            <span>Client upload:
                                                                {{ $booking->dp_uploaded_at->format('d/m/Y H:i') }}</span>
                                                        </div>
                                                    @endif

                                                    @if($booking->dp_verified_at)
                                                        <div class="flex items-center">
                                                            <svg class="w-3 h-3 mr-1 text-green-500" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                            <span>Admin verifikasi:
                                                                {{ $booking->dp_verified_at->format('d/m/Y H:i') }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="flex gap-2 ml-4">
                                                <a href="{{ asset('storage/' . $booking->payment_proof) }}" target="_blank"
                                                    class="px-3 py-2 bg-blue-100 text-blue-700 hover:bg-blue-200 rounded-lg text-sm font-medium flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    Lihat
                                                </a>
                                                <a href="{{ asset('storage/' . $booking->payment_proof) }}" download
                                                    class="px-3 py-2 bg-gray-100 text-gray-700 hover:bg-gray-200 rounded-lg text-sm font-medium flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                    </svg>
                                                    Download
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Bukti Transfer Pelunasan -->
                            @if($booking->remaining_payment_proof)
                                <div
                                    class="border {{ $booking->remaining_amount == 0 ? 'border-green-200' : 'border-yellow-200' }} rounded-xl overflow-hidden">
                                    <div
                                        class="{{ $booking->remaining_amount == 0 ? 'bg-green-50 border-b border-green-100' : 'bg-yellow-50 border-b border-yellow-100' }} px-4 py-3">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <svg class="w-5 h-5 {{ $booking->remaining_amount == 0 ? 'text-green-600' : 'text-yellow-600' }} mr-2"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span class="font-medium text-gray-900">Bukti Transfer Pelunasan</span>
                                            </div>
                                            @if($booking->remaining_amount == 0)
                                                <span
                                                    class="px-3 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">
                                                    ✓ Sudah Diverifikasi
                                                </span>
                                            @else
                                                <span
                                                    class="px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-medium rounded-full">
                                                    ⏳ Menunggu Verifikasi
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="p-4">
                                        <div class="flex items-center justify-between">
                                            <div class="space-y-2 flex-1">
                                                @if($booking->remaining_payment_notes)
                                                    <p class="text-sm text-gray-600">
                                                        <span class="font-medium">Catatan Client:</span>
                                                        {{ $booking->remaining_payment_notes }}
                                                    </p>
                                                @endif

                                                <!-- TIMESTAMP PELUNASAN LANGSUNG DI CARD -->
                                                <div class="text-xs text-gray-500 space-y-1">
                                                    @if($booking->remaining_uploaded_at)
                                                        <div class="flex items-center">
                                                            <svg class="w-3 h-3 mr-1 {{ $booking->remaining_amount == 0 ? 'text-green-500' : 'text-yellow-500' }}"
                                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                                                            </svg>
                                                            <span>Client upload:
                                                                {{ $booking->remaining_uploaded_at->format('d/m/Y H:i') }}</span>
                                                        </div>
                                                    @endif

                                                    @if($booking->remaining_verified_at)
                                                        <div class="flex items-center">
                                                            <svg class="w-3 h-3 mr-1 text-green-500" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                            <span>Admin verifikasi:
                                                                {{ $booking->remaining_verified_at->format('d/m/Y H:i') }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="flex gap-2 ml-4">
                                                <a href="{{ asset('storage/' . $booking->remaining_payment_proof) }}"
                                                    target="_blank"
                                                    class="px-3 py-2 {{ $booking->remaining_amount == 0 ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-yellow-100 text-yellow-700 hover:bg-yellow-200' }} rounded-lg text-sm font-medium flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    Lihat
                                                </a>
                                                <a href="{{ asset('storage/' . $booking->remaining_payment_proof) }}"
                                                    download
                                                    class="px-3 py-2 bg-gray-100 text-gray-700 hover:bg-gray-200 rounded-lg text-sm font-medium flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                    </svg>
                                                    Download
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if(!$booking->payment_proof && !$booking->remaining_payment_proof)
                                <div class="text-center py-8 border-2 border-dashed border-gray-300 rounded-xl">
                                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="text-gray-500 font-medium">Belum ada bukti transfer</p>
                                    <p class="text-sm text-gray-400 mt-1">Client belum mengupload bukti pembayaran</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Booking Timeline -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-200">
                        <div class="flex items-center mb-6">
                            <div class="p-3 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-xl mr-4 shadow-sm">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-xl text-gray-900">Proses Booking</h3>
                                <p class="text-sm text-gray-500">Timeline status booking</p>
                            </div>
                        </div>

                        <div class="space-y-8">
                            @php
                                $timeline = [
                                    ['status' => 'pending', 'icon' => '⏳', 'title' => 'Menunggu DP', 'desc' => 'Booking dibuat, menunggu pembayaran DP', 'is_auto' => true],
                                    ['status' => 'confirmed', 'icon' => '✅', 'title' => 'DP Terverifikasi', 'desc' => 'Admin verifikasi DP secara manual', 'is_auto' => false],
                                    ['status' => 'in_progress', 'icon' => '🎬', 'title' => 'Dalam Proses', 'desc' => 'Sesi Pemotretan', 'is_auto' => true],
                                    ['status' => 'results_uploaded', 'icon' => '📤', 'title' => 'Hasil Diupload', 'desc' => 'Admin mengupload hasil foto', 'is_auto' => false],
                                    ['status' => 'pending_lunas', 'icon' => '💰', 'title' => 'Menunggu Pelunasan', 'desc' => 'Client upload bukti pelunasan', 'is_auto' => false],
                                    ['status' => 'completed', 'icon' => '✨', 'title' => 'Selesai', 'desc' => 'Pelunasan diverifikasi, client bisa download', 'is_auto' => false],
                                ];

                                $currentIndex = -1;
                                $bookingStatus = strtolower(trim($booking->status));

                                foreach ($timeline as $index => $step) {
                                    $stepStatus = strtolower(trim($step['status']));
                                    if ($stepStatus === $bookingStatus) {
                                        $currentIndex = $index;
                                        break;
                                    }
                                }

                                if ($booking->status == 'cancelled') {
                                    $timeline[] = [
                                        'status' => 'cancelled',
                                        'icon' => '❌',
                                        'title' => 'Dibatalkan',
                                        'desc' => 'Booking dibatalkan',
                                        'is_auto' => false,
                                        'is_cancelled' => true
                                    ];
                                    $currentIndex = count($timeline) - 1;
                                }
                            @endphp

                            @foreach($timeline as $index => $step)
                                                    <div class="flex items-start">
                                                        <div class="flex-shrink-0 relative">
                                                            <div class="w-12 h-12 rounded-full flex items-center justify-center border-4 
                                                                    {{ $index < $currentIndex ? 'border-green-500 bg-green-50 text-green-600 shadow-sm' :
                                ($index == $currentIndex ? 'border-blue-500 bg-blue-50 text-blue-600 shadow-md' :
                                    (isset($step['is_cancelled']) && $step['is_cancelled'] ? 'border-red-500 bg-red-50 text-red-600 shadow-md' :
                                        'border-gray-300 bg-gray-50 text-gray-400')) }}">
                                                                <span class="text-xl">{{ $step['icon'] }}</span>
                                                            </div>
                                                            @if($index < count($timeline) - 1 && !(isset($step['is_cancelled']) && $step['is_cancelled']))
                                                                                        <div class="absolute left-1/2 top-12 w-0.5 h-10 -translate-x-1/2 
                                                                                                    {{ $index < $currentIndex ? 'bg-green-400' :
                                                                (isset($step['is_cancelled']) ? 'bg-transparent' : 'bg-gray-300') }}">
                                                                                        </div>
                                                            @endif
                                                        </div>
                                                        <div
                                                            class="ml-5 pb-8 {{ $index < count($timeline) - 1 && !(isset($step['is_cancelled']) && $step['is_cancelled']) ? 'border-b border-gray-200' : '' }}">
                                                            <div class="flex items-center justify-between">
                                                                <p
                                                                    class="font-semibold text-lg 
                                                                        {{ $index <= $currentIndex ? (isset($step['is_cancelled']) && $step['is_cancelled'] ? 'text-red-700' : 'text-gray-900') : 'text-gray-500' }}">
                                                                    {{ $step['title'] }}
                                                                </p>
                                                                @if(isset($step['is_cancelled']) && $step['is_cancelled'])
                                                                    <span
                                                                        class="px-3 py-1 bg-red-100 text-red-700 text-xs font-medium rounded-full">Dibatalkan</span>
                                                                @elseif($step['is_auto'])
                                                                    <span
                                                                        class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded-full">Otomatis</span>
                                                                @else
                                                                    <span
                                                                        class="px-3 py-1 bg-yellow-100 text-yellow-700 text-xs font-medium rounded-full">Manual</span>
                                                                @endif
                                                            </div>

                                                            <p
                                                                class="text-sm {{ $index <= $currentIndex ? 'text-gray-600' : 'text-gray-400' }} mt-2">
                                                                {{ $step['desc'] }}
                                                            </p>

                                                            @if($index == $currentIndex && !isset($step['is_cancelled']))
                                                                <div
                                                                    class="mt-3 inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 text-sm font-medium rounded-full">
                                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                            d="M5 13l4 4L19 7" />
                                                                    </svg>
                                                                    Status Saat Ini
                                                                </div>
                                                            @elseif(isset($step['is_cancelled']) && $index == $currentIndex)
                                                                <div
                                                                    class="mt-3 inline-flex items-center px-3 py-1 bg-red-100 text-red-700 text-sm font-medium rounded-full">
                                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                            d="M6 18L18 6M6 6l12 12" />
                                                                    </svg>
                                                                    Status Saat Ini
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Right Column - Quick Actions & Results -->
                <div class="space-y-6">
                    <!-- Quick Actions -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-200">
                        <h3 class="font-bold text-lg text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            Quick Actions
                        </h3>

                        <div class="space-y-4">
                            <!-- Verifikasi DP -->
                            @if($booking->status == 'pending')
                                <button onclick="verifyPayment({{ $booking->id }})"
                                    class="w-full px-4 py-4 bg-gradient-to-r from-blue-600 to-blue-500 text-white rounded-xl hover:from-blue-700 hover:to-blue-600 transition font-medium flex items-center justify-center shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Verifikasi DP
                                </button>

                                <!-- Batalkan Booking -->
                                <button onclick="adminCancelBooking({{ $booking->id }})"
                                    class="w-full px-4 py-4 bg-gradient-to-r from-red-600 to-red-500 text-white rounded-xl hover:from-red-700 hover:to-red-600 transition font-medium flex items-center justify-center shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Batalkan Booking
                                </button>
                            @endif

                            <!-- Upload Hasil -->
                            @if($booking->status == 'in_progress')
                                <a href="#uploadSection"
                                    class="w-full px-4 py-4 bg-gradient-to-r from-purple-600 to-purple-500 text-white rounded-xl hover:from-purple-700 hover:to-purple-600 transition font-medium flex items-center justify-center shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                                    </svg>
                                    Upload Hasil Foto
                                </a>
                            @endif

                            <!-- Verifikasi Pelunasan -->
                            @if(($booking->status == 'results_uploaded' || $booking->status == 'pending_lunas') && $booking->remaining_amount > 0)
                                <button onclick="verifyFullPayment({{ $booking->id }})"
                                    class="w-full px-4 py-4 bg-gradient-to-r from-green-600 to-emerald-500 text-white rounded-xl hover:from-green-700 hover:to-emerald-600 transition font-medium flex items-center justify-center shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Verifikasi Pelunasan
                                </button>
                            @endif

                            <!-- Edit Link -->
                            @if($booking->drive_link && in_array($booking->status, ['results_uploaded', 'completed', 'pending_lunas']))
                                <a href="{{ request()->fullUrlWithQuery(['edit' => 'true']) }}#uploadSection"
                                    class="w-full px-4 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition font-medium flex items-center justify-center border border-gray-300">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Edit Link Hasil
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Results Section - SUDAH DIPERBAIKI -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-200" id="uploadSection">
                        <h3 class="font-bold text-lg text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                            </svg>
                            Hasil Foto
                        </h3>

                        @if($booking->drive_link)
                            <!-- Info Status Download -->
                            <div class="p-4 rounded-xl mb-4
                                    @if($booking->canDownloadResults())
                                        bg-green-50 border border-green-200
                                    @else
                                        bg-purple-50 border border-purple-200
                                    @endif">

                                @if($booking->canDownloadResults())
                                    <div class="flex items-center mb-3">
                                        <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <div>
                                            <p class="font-semibold text-green-800">✅ Client bisa download hasil</p>
                                            <p class="text-sm text-green-700">Pelunasan sudah diverifikasi</p>
                                        </div>
                                    </div>
                                @else
                                    <div class="flex items-center mb-3">
                                        @if($booking->status == 'results_uploaded')
                                            <svg class="w-5 h-5 text-purple-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <div>
                                                <p class="font-semibold text-purple-800">📤 Hasil sudah diupload</p>
                                                <p class="text-sm text-purple-700">
                                                    @if($booking->remaining_amount > 0)
                                                        Tunggu pelunasan Rp {{ number_format($booking->remaining_amount, 0, ',', '.') }}
                                                    @elseif($booking->status == 'pending_lunas')
                                                        ⏳ Menunggu verifikasi pelunasan
                                                    @else
                                                        Menunggu verifikasi admin
                                                    @endif
                                                </p>
                                            </div>
                                        @elseif($booking->status == 'pending_lunas')
                                            <svg class="w-5 h-5 text-yellow-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <div>
                                                <p class="font-semibold text-yellow-800">💰 Menunggu Verifikasi Pelunasan</p>
                                                <p class="text-sm text-yellow-700">
                                                    Client sudah upload bukti pelunasan
                                                </p>
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                <!-- Link Google Drive -->
                                <div class="flex items-center justify-between mb-3 p-3 bg-white border 
                                        @if($booking->canDownloadResults()) border-green-100 @else border-purple-100 @endif 
                                        rounded-lg">
                                    <a href="{{ $booking->drive_link }}" target="_blank"
                                        class="text-blue-600 hover:text-blue-800 text-sm truncate flex-1 font-medium">
                                        {{ Str::limit($booking->drive_link, 40) }}
                                    </a>
                                    <button onclick="copyToClipboard('{{ $booking->drive_link }}')"
                                        class="ml-2 p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                    </button>
                                </div>

                                @if($booking->admin_notes)
                                    <div
                                        class="mt-3 pt-3 border-t 
                                                @if($booking->canDownloadResults()) border-green-100 @else border-purple-100 @endif">
                                        <p class="text-sm text-gray-600">
                                            <span class="font-medium text-gray-700">Catatan Admin:</span>
                                            {{ $booking->admin_notes }}
                                        </p>
                                    </div>
                                @endif

                                <!-- TIMESTAMP HASIL -->
                                <div class="mt-3 text-xs text-gray-500 space-y-1">
                                    @if($booking->results_uploaded_at)
                                        <div>📤 Upload pertama: {{ $booking->results_uploaded_at->format('d/m/Y H:i') }}</div>
                                    @endif

                                    @if($booking->results_updated_at && $booking->results_updated_at != $booking->results_uploaded_at)
                                        <div>✏️ Terakhir diperbarui: {{ $booking->results_updated_at->format('d/m/Y H:i') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Upload/Edit Form -->
                        @if(!$booking->drive_link || request('edit') == 'true')
                            <form action="{{ route('admin.bookings.upload-results', $booking) }}" method="POST"
                                onsubmit="return validateDriveLink()" id="uploadResultsForm" class="space-y-4">
                                @csrf

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                        <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                        </svg>
                                        Link Google Drive
                                    </label>
                                    <input type="url" name="drive_link" id="drive_link"
                                        placeholder="https://drive.google.com/..."
                                        value="{{ old('drive_link', $booking->drive_link) }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition"
                                        required>
                                    @error('drive_link')
                                        <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan untuk Client
                                        (Opsional)</label>
                                    <textarea name="notes" rows="3"
                                        placeholder="Contoh: Hasil foto sudah siap, silakan download setelah pelunasan."
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition">{{ old('notes', $booking->admin_notes) }}</textarea>
                                    <p class="text-xs text-gray-500 mt-1">Catatan ini akan ditampilkan di halaman client</p>
                                </div>

                                @if($booking->status == 'completed')
                                    <input type="hidden" name="keep_status_completed" value="1">
                                @endif

                                <button type="submit"
                                    class="w-full px-4 py-3 bg-gradient-to-r from-purple-600 to-purple-500 text-white rounded-lg hover:from-purple-700 hover:to-purple-600 transition font-medium flex items-center justify-center shadow-md hover:shadow-lg">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                                    </svg>
                                    {{ $booking->drive_link ? 'Update Link' : 'Upload Hasil' }}
                                </button>

                                @if($booking->drive_link && request('edit') == 'true')
                                    <a href="{{ route('admin.bookings.show', $booking) }}#uploadSection"
                                        class="w-full px-4 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition font-medium flex items-center justify-center border border-gray-300 mt-2">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        Batalkan Edit
                                    </a>
                                @endif
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Fungsi copy ke clipboard
            function copyToClipboard(text) {
                navigator.clipboard.writeText(text).then(function () {
                    const alert = document.createElement('div');
                    alert.className = 'fixed bottom-6 right-6 bg-green-500 text-white px-5 py-3 rounded-lg shadow-xl z-50 flex items-center';
                    alert.innerHTML = `
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                        ✅ Link disalin ke clipboard!
                    `;
                    document.body.appendChild(alert);

                    setTimeout(() => {
                        alert.remove();
                    }, 3000);
                }).catch(function (err) {
                    console.error('Failed to copy: ', err);
                });
            }

            function verifyFullPayment(id) {
                if (confirm('Verifikasi bahwa client sudah melunasi pembayaran?\n\nSetelah diverifikasi:\n• Status akan menjadi "Selesai"\n• Client bisa download hasil')) {
                    console.log('Verifikasi pelunasan untuk booking:', id);

                    // Show loading
                    const button = document.querySelector('[onclick="verifyFullPayment(' + id + ')"]');
                    const originalText = button.innerHTML;
                    button.innerHTML = '<svg class="w-5 h-5 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg> Memproses...';
                    button.disabled = true;

                    fetch(`/admin/bookings/${id}/verify-full-payment`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({})
                    })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok: ' + response.status);
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                const alert = document.createElement('div');
                                alert.className = 'fixed top-6 right-6 bg-green-500 text-white px-5 py-3 rounded-lg shadow-xl z-50 flex items-center';
                                alert.innerHTML = `
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                ✅ Pelunasan berhasil diverifikasi! Client sekarang bisa download.
                            `;
                                document.body.appendChild(alert);

                                setTimeout(() => {
                                    location.reload();
                                }, 2000);
                            } else {
                                button.innerHTML = originalText;
                                button.disabled = false;
                                alert('Gagal: ' + (data.message || 'Terjadi kesalahan'));
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            button.innerHTML = originalText;
                            button.disabled = false;
                            alert('Terjadi kesalahan saat verifikasi pelunasan: ' + error.message);
                        });
                }
            }

            // Fungsi Verifikasi DP
            function verifyPayment(id) {
                if (confirm('Verifikasi pembayaran DP 50% untuk booking ini?\n\nSetelah diverifikasi, status akan otomatis menjadi "Dalam Proses".')) {
                    console.log('Verifikasi DP untuk booking:', id);

                    const button = document.querySelector('[onclick="verifyPayment(' + id + ')"]');
                    const originalText = button.innerHTML;
                    button.innerHTML = '<svg class="w-5 h-5 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg> Memproses...';
                    button.disabled = true;

                    fetch(`/admin/bookings/${id}/verify-payment`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({})
                    })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok: ' + response.status);
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                const alert = document.createElement('div');
                                alert.className = 'fixed top-6 right-6 bg-green-500 text-white px-5 py-3 rounded-lg shadow-xl z-50 flex items-center';
                                alert.innerHTML = `
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    ✅ DP berhasil diverifikasi! Status otomatis dalam proses.
                                `;
                                document.body.appendChild(alert);

                                setTimeout(() => {
                                    location.reload();
                                }, 2000);
                            } else {
                                button.innerHTML = originalText;
                                button.disabled = false;
                                alert('Gagal: ' + (data.message || 'Terjadi kesalahan'));
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            button.innerHTML = originalText;
                            button.disabled = false;
                            alert('Terjadi kesalahan saat verifikasi DP: ' + error.message);
                        });
                }
            }


            // Fungsi Batalkan Booking
            function adminCancelBooking(bookingId) {
                if (confirm('⚠️ BATALKAN BOOKING?\n\n• Status akan menjadi: CANCELLED\n• DP TIDAK DIKEMBALIKAN\n• Booking akan dihapus dari jadwal\n\nAlasan: DP tidak valid / data bermasalah')) {
                    console.log('Membatalkan booking:', bookingId);

                    const button = document.querySelector('[onclick="adminCancelBooking(' + bookingId + ')"]');
                    const originalText = button.innerHTML;
                    button.innerHTML = '<svg class="w-5 h-5 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg> Memproses...';
                    button.disabled = true;

                    fetch(`/admin/bookings/${bookingId}/cancel`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            initiated_by: 'admin',
                            cancel_reason: 'invalid_payment',
                            cancel_details: 'DP tidak valid'
                        })
                    })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok: ' + response.status);
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                alert('✅ Booking berhasil dibatalkan!');
                                location.reload();
                            } else {
                                button.innerHTML = originalText;
                                button.disabled = false;
                                alert('❌ Gagal: ' + (data.message || 'Gagal membatalkan booking'));
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            button.innerHTML = originalText;
                            button.disabled = false;
                            alert('❌ Terjadi kesalahan: ' + error.message);
                        });
                }
            }

            // Fungsi validasi upload link
            function validateDriveLink() {
                const driveLink = document.getElementById('drive_link');
                const submitBtn = document.querySelector('#uploadResultsForm button[type="submit"]');
                const isEditing = '{{ $booking->drive_link ? "true" : "false" }}' === 'true';
                const currentStatus = '{{ $booking->status }}';

                if (!driveLink || !driveLink.value) {
                    alert('Link Google Drive wajib diisi!');
                    return false;
                }

                let confirmMessage = '';
                if (isEditing) {
                    if (currentStatus === 'completed') {
                        confirmMessage = '✅ Update link hasil?\n\nStatus tetap: SELESAI\n• Client tetap bisa download\n• Link akan diperbarui';
                    } else {
                        confirmMessage = '✅ Update link hasil?\n\nStatus tetap: {{ strtoupper($booking->status) }}\n• Link akan diperbarui';
                    }
                } else {
                    confirmMessage = '✅ Upload hasil foto?\n\nSetelah upload:\n• Status akan menjadi: HASIL DIUPLOAD\n• Client belum bisa download sampai lunas';
                }

                const confirmSend = confirm(confirmMessage);

                if (!confirmSend) {
                    return false;
                }

                if (submitBtn) {
                    const originalText = submitBtn.innerHTML;
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<svg class="w-5 h-5 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg> Memproses...';

                    setTimeout(() => {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    }, 5000);
                }

                return true;
            }

            // Validasi real-time untuk input link
            document.addEventListener('DOMContentLoaded', function () {
                const driveLinkInput = document.getElementById('drive_link');
                if (driveLinkInput) {
                    driveLinkInput.addEventListener('input', function (e) {
                        const link = e.target.value;
                        const hint = document.getElementById('linkHint');

                        if (!hint) {
                            const hintElement = document.createElement('p');
                            hintElement.id = 'linkHint';
                            hintElement.className = 'text-xs mt-2';
                            e.target.parentNode.appendChild(hintElement);
                        }

                        if (link.includes('drive.google.com')) {
                            document.getElementById('linkHint').textContent = '✅ Link Google Drive valid';
                            document.getElementById('linkHint').className = 'text-xs mt-2 text-green-600';
                        } else if (link) {
                            document.getElementById('linkHint').textContent = '⚠️ Pastikan link dari Google Drive';
                            document.getElementById('linkHint').className = 'text-xs mt-2 text-yellow-600';
                        } else {
                            document.getElementById('linkHint').textContent = '';
                        }
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>