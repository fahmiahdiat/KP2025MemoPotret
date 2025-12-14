<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Booking #{{ $booking->booking_code }}</h2>
                <p class="text-sm text-gray-500">{{ $booking->created_at->format('d F Y') }}</p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('client.dashboard') }}" class="btn-secondary">← Dashboard</a>
                <button onclick="printInvoice()" class="btn-primary">📄 Cetak Invoice</button>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <!-- Alert Status -->
            @if($booking->status == 'pending')
                <div class="mb-6 p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                <strong>Menunggu Verifikasi Pembayaran DP</strong><br>
                                Upload bukti transfer DP 50% untuk konfirmasi booking.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid lg:grid-cols-3 gap-6">
                <!-- Main Content -->
                <div class="lg:col-span-2">
                    <!-- Package & Event Details -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                        <div class="flex flex-col md:flex-row md:justify-between md:items-start mb-6 gap-4">
                            <div>
                                <h3 class="font-bold text-lg text-gray-900">Detail Paket & Acara</h3>
                                <div class="mt-2 flex items-center flex-wrap gap-2">
                                    @if($booking->status == 'pending')
                                        <span class="badge-warning">Menunggu DP</span>
                                    @elseif($booking->status == 'confirmed')
                                        <span class="badge-primary">Dikonfirmasi</span>
                                    @elseif($booking->status == 'in_progress')
                                        <span class="badge-info">Dalam Proses</span>
                                    @elseif($booking->status == 'completed')
                                        <span class="badge-success">Selesai</span>
                                    @endif
                                    <span class="text-sm text-gray-500">
                                        Terakhir update: {{ $booking->updated_at->diffForHumans() }}
                                    </span>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-bold text-green-600">
                                    Rp {{ number_format($booking->total_amount, 0, ',', '.') }}
                                </div>
                                <div class="text-sm text-gray-500">{{ $booking->package->name }}</div>
                            </div>
                        </div>

                        <!-- Package Features -->
                        <div class="mb-6">
                            <h4 class="font-medium text-gray-700 mb-3">Fasilitas Paket:</h4>
                            @if($booking->package->features && is_array($booking->package->features))
                                <div class="grid md:grid-cols-2 gap-3">
                                    @foreach($booking->package->features as $feature)
                                        @if(is_string($feature))
                                            <div class="flex items-start">
                                                <svg class="w-5 h-5 text-green-500 mt-0.5 mr-3 flex-shrink-0" fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                <span class="text-sm text-gray-700">{{ $feature }}</span>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm text-gray-500 italic">Tidak ada fasilitas yang tercantum</p>
                            @endif
                        </div>

                        <!-- Event Details -->
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="font-medium text-gray-700 mb-3">Detail Acara</h4>
                                <div class="space-y-3">
                                    <div>
                                        <div class="text-sm text-gray-500">Tanggal Acara</div>
                                        <div class="font-medium">{{ $booking->event_date->format('d F Y') }}</div>
                                    </div>
                                    <div>
                                        <div class="text-sm text-gray-500">Waktu Mulai</div>
                                        <div class="font-medium">{{ date('H:i', strtotime($booking->event_time)) }} WIB
                                        </div>
                                    </div>
                                    <div>
                                        <div class="text-sm text-gray-500">Durasi</div>
                                        <div class="font-medium">{{ $booking->package->duration_hours }} jam</div>
                                    </div>
                                    <div>
                                        <div class="text-sm text-gray-500">Estimasi Selesai</div>
                                        <div class="font-medium">
                                            @php
                                                $endTime = \Carbon\Carbon::parse($booking->event_time)
                                                    ->addHours($booking->package->duration_hours)
                                                    ->format('H:i');
                                            @endphp
                                            {{ $endTime }} WIB
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-700 mb-3">Lokasi Acara</h4>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="font-medium text-gray-800">{{ $booking->event_location }}</p>
                                    @if($booking->notes)
                                        <div class="mt-3 pt-3 border-t border-gray-200">
                                            <div class="text-sm text-gray-500 mb-1">Catatan Khusus:</div>
                                            <p class="text-sm text-gray-700">{{ $booking->notes }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Information -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                        <h3 class="font-bold text-lg text-gray-900 mb-6">Informasi Pembayaran</h3>

                        <!-- Payment Summary -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
                            <div class="bg-green-50 p-4 rounded-lg border border-green-100">
                                <div class="text-sm text-gray-600 mb-1">Total Biaya</div>
                                <div class="text-2xl font-bold text-green-600">Rp
                                    {{ number_format($booking->total_amount, 0, ',', '.') }}
                                </div>
                            </div>
                            <div
                                class="{{ $booking->dp_amount > 0 ? 'bg-blue-50 border-blue-100' : 'bg-yellow-50 border-yellow-100' }} p-4 rounded-lg border">
                                <div class="text-sm text-gray-600 mb-1">DP (50%)</div>
                                <div
                                    class="text-xl font-bold {{ $booking->dp_amount > 0 ? 'text-blue-600' : 'text-yellow-600' }}">
                                    @if($booking->dp_amount > 0)
                                        Rp {{ number_format($booking->dp_amount, 0, ',', '.') }}
                                    @else
                                        Belum Dibayar
                                    @endif
                                </div>
                                @if($booking->dp_amount > 0)
                                    <div class="text-xs text-green-600 font-medium mt-1">✓ Terverifikasi</div>
                                @endif
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <div class="text-sm text-gray-600 mb-1">Sisa Pembayaran</div>
                                <div class="text-xl font-bold text-gray-700">
                                    Rp {{ number_format($booking->remaining_amount, 0, ',', '.') }}
                                </div>
                                <div class="text-xs text-gray-500 mt-1">
                                    {{ $booking->event_date->diffInDays(now()) > 7 ? 'Bayar H-7' : 'Segera bayar' }}
                                </div>
                            </div>
                        </div>

                        <!-- Payment Instructions -->
                        @if($booking->status == 'pending' && $booking->dp_amount == 0)
                            <div
                                class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-6 mb-8">
                                <div class="flex items-center mb-4">
                                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                        <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-blue-800 text-lg">Instruksi Pembayaran DP</h4>
                                        <p class="text-sm text-blue-600">Transfer 50% untuk konfirmasi booking</p>
                                    </div>
                                </div>

                                <div class="grid md:grid-cols-2 gap-6 mb-6">
                                    <div class="bg-white p-4 rounded-lg border border-blue-100">
                                        <div class="space-y-4">
                                            <div>
                                                <div class="text-xs text-gray-500 font-medium mb-1">Nominal Transfer</div>
                                                <div class="text-xl font-bold text-blue-700">Rp
                                                    {{ number_format($booking->total_amount * 0.5, 0, ',', '.') }}
                                                </div>
                                            </div>
                                            <div>
                                                <div class="text-xs text-gray-500 font-medium mb-1">Kode Booking</div>
                                                <div
                                                    class="font-mono bg-blue-50 text-blue-700 px-3 py-1.5 rounded-md text-sm font-bold">
                                                    {{ $booking->booking_code }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="bg-white p-4 rounded-lg border border-blue-100">
                                        <div class="space-y-4">
                                            <div>
                                                <div class="text-xs text-gray-500 font-medium mb-1">Bank Tujuan</div>
                                                <div class="flex items-center">
                                                    <div
                                                        class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center mr-2">
                                                        <span class="text-sm font-bold text-blue-700">BCA</span>
                                                    </div>
                                                    <span class="font-medium">Bank Central Asia</span>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="text-xs text-gray-500 font-medium mb-1">No. Rekening</div>
                                                <div class="font-mono text-lg font-bold text-gray-800">123-456-7890</div>
                                            </div>
                                            <div>
                                                <div class="text-xs text-gray-500 font-medium mb-1">Atas Nama</div>
                                                <div class="font-medium text-gray-800">Memo Potret Studio</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
                                    <div class="flex">
                                        <svg class="h-5 w-5 text-yellow-400 mt-0.5 mr-3 flex-shrink-0" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <div>
                                            <p class="text-sm text-yellow-800 font-medium">
                                                ⚠️ <strong>Penting:</strong> Upload bukti transfer maksimal 1x24 jam setelah
                                                booking
                                            </p>
                                            <p class="text-xs text-yellow-700 mt-1">
                                                • Pastikan nama pengirim sesuai nama akun<br>
                                                • Jumlah transfer harus tepat<br>
                                                • Tanggal transfer jelas terbaca
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Call to Action untuk mengarahkan ke sidebar -->
                                <div class="mt-6 pt-6 border-t border-blue-200">
                                    <div class="text-center">
                                        <p class="text-sm text-blue-600 mb-3">
                                            <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <strong>Sudah Transfer?</strong> Upload bukti melalui menu <strong>"Aksi
                                                Cepat"</strong> di sidebar →
                                        </p>
                                        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                                            <span class="text-xs text-gray-500">
                                                Format: JPG/PNG/PDF, maksimal 2MB
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Payment History -->
                        @if($booking->dp_amount > 0)
                            <div class="mt-8">
                                <h4 class="font-medium text-gray-700 mb-4 text-lg">Riwayat Pembayaran</h4>
                                <div class="overflow-hidden border border-gray-200 rounded-xl">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th
                                                    class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Jenis</th>
                                                <th
                                                    class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Tanggal</th>
                                                <th
                                                    class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Jumlah</th>
                                                <th
                                                    class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Status</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200 bg-white">
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div
                                                            class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                                            <span class="text-sm font-medium text-blue-700">DP</span>
                                                        </div>
                                                        <div>
                                                            <div class="text-sm font-medium text-gray-900">Down Payment
                                                                (50%)</div>
                                                            <div class="text-xs text-gray-500">Pembayaran pertama</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $booking->updated_at->format('d/m/Y H:i') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-bold text-green-600">
                                                        Rp {{ number_format($booking->dp_amount, 0, ',', '.') }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span
                                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd"
                                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                        Terverifikasi
                                                    </span>
                                                </td>
                                            </tr>
                                            @if($booking->remaining_amount > 0)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="flex items-center">
                                                            <div
                                                                class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center mr-3">
                                                                <span class="text-sm font-medium text-gray-700">PL</span>
                                                            </div>
                                                            <div>
                                                                <div class="text-sm font-medium text-gray-900">Pelunasan</div>
                                                                <div class="text-xs text-gray-500">Pembayaran akhir</div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">-</td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm font-bold text-gray-700">
                                                            Rp {{ number_format($booking->remaining_amount, 0, ',', '.') }}
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span
                                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd"
                                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
                                                            Menunggu
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Results Section -->
                    @if($booking->drive_link)
                        @if($booking->canDownloadResults())
                            <!-- Client BISA download (sudah lunas) -->
                            <div
                                class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-xl p-8 text-center">
                                <div class="max-w-md mx-auto">
                                    <div
                                        class="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>

                                    <h4 class="text-xl font-bold text-green-800 mb-3">🎉 Hasil Foto Siap Diunduh!</h4>
                                    <p class="text-gray-700 mb-6">Foto dan video dokumentasi acara Anda sudah siap. Silakan
                                        unduh melalui link di bawah ini.</p>

                                    <div class="space-y-4">
                                        <a href="{{ $booking->drive_link }}" target="_blank"
                                            class="btn-success inline-flex items-center justify-center px-6 py-3 text-base font-medium">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                            Download Hasil Lengkap
                                        </a>

                                        @if($booking->drive_password)
                                            <div class="bg-white p-4 rounded-lg border border-green-100">
                                                <div class="text-sm text-gray-700 mb-2">
                                                    <span class="font-medium">Password:</span> {{ $booking->drive_password }}
                                                </div>
                                                <p class="text-xs text-gray-500">Gunakan password ini untuk mengakses folder</p>
                                            </div>
                                        @endif

                                        <div class="bg-white p-4 rounded-lg border border-green-100">
                                            <div class="text-sm text-gray-700 mb-2">
                                                <span class="font-medium">Informasi:</span>
                                            </div>
                                            <div class="flex flex-wrap justify-center gap-4 text-xs text-gray-600">
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 text-green-500 mr-1" fill="currentColor"
                                                        viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    Link aktif 30 hari
                                                </div>
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 text-green-500 mr-1" fill="currentColor"
                                                        viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    Format HD & Full Quality
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <p class="text-sm text-gray-600 mt-6">
                                        Jika mengalami kendala saat mengunduh, silakan hubungi tim support kami.
                                    </p>
                                </div>
                            </div>
                        @else
                            <!-- Client BELUM bisa download (belum lunas) -->
                            <div
                                class="bg-gradient-to-r from-purple-50 to-pink-50 border border-purple-200 rounded-xl p-8 text-center">
                                <div class="max-w-md mx-auto">
                                    <div
                                        class="w-16 h-16 rounded-full bg-purple-100 flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                    </div>

                                    <h4 class="text-xl font-bold text-purple-800 mb-3">Hasil Foto Sudah Siap!</h4>
                                    <p class="text-gray-700 mb-4">
                                        Foto dan video dokumentasi acara Anda sudah siap diupload oleh admin.
                                        <span class="font-semibold text-purple-700">Silakan selesaikan pembayaran pelunasan
                                            untuk mengakses hasil.</span>
                                    </p>

                                    <div class="bg-white p-4 rounded-lg border border-purple-200 mb-6">
                                        <div class="text-center">
                                            <div class="text-sm text-gray-600 mb-2">Sisa Pembayaran</div>
                                            <div class="text-2xl font-bold text-red-600">
                                                Rp {{ number_format($booking->remaining_amount, 0, ',', '.') }}
                                            </div>
                                            @if($booking->admin_notes)
                                                <div class="mt-3 p-3 bg-purple-50 rounded-lg">
                                                    <p class="text-sm text-purple-700">{{ $booking->admin_notes }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    @if($booking->status == 'results_uploaded' && $booking->remaining_amount > 0)
                                        <button onclick="document.getElementById('pelunasanModal').showModal()"
                                            class="btn-primary inline-flex items-center justify-center px-6 py-3 text-base font-medium">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Bayar Pelunasan
                                        </button>
                                    @endif

                                    <p class="text-sm text-gray-600 mt-6">
                                        Setelah pembayaran diverifikasi admin, Anda akan bisa mengunduh hasil.
                                    </p>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>

                <!-- Sidebar -->
                <div>
                    <!-- Status Timeline -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                        <div class="flex items-center mb-6">
                            <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="font-bold text-lg text-gray-900">Timeline Status</h3>
                        </div>

                        <div class="space-y-6">
                            @foreach($timelineSteps as $index => $step)
                                <div class="relative">
                                    <!-- Line connector -->
                                    @if($index < count($timelineSteps) - 1)
                                        <div class="absolute left-4 top-8 bottom-0 w-0.5 bg-gray-200"></div>
                                    @endif

                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <div class="w-8 h-8 rounded-full flex items-center justify-center
                                                {{ $currentStatusIndex >= array_search($step['status_key'], array_column($timelineSteps, 'status_key')) 
                                                    ? 'bg-indigo-600 text-white shadow-md' : 'bg-gray-100 text-gray-400' }}">
                                                {{ $step['icon'] }}
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="flex items-center">
                                                <h4 class="font-medium text-gray-900">{{ $step['title'] }}</h4>
                                                @if($step['date'] != '-')
                                                    <span class="ml-2 text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                                        {{ $step['date'] }}
                                                    </span>
                                                @endif
                                            </div>
                                            <p class="mt-1 text-sm text-gray-600">{{ $step['description'] }}</p>
                                            
                                            <!-- Tampilkan indikator status saat ini -->
                                            @if($booking->status == $step['status_key'] && $step['status_key'] != 'completed')
                                                <span class="inline-flex items-center mt-2 px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                                    </svg>
                                                    Status Saat Ini
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div id="quick-actions" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-lg text-gray-900">Aksi Cepat</h3>
                                <p class="text-sm text-gray-500">Pilih aksi yang ingin dilakukan</p>
                            </div>
                        </div>

                        <div class="space-y-3">
                            @if($booking->status == 'pending')
                                <!-- Upload DP Button - DIPERBAIKI: Button ditutup dengan benar -->
                                <button onclick="document.getElementById('uploadModal').showModal()"
                                    class="w-full flex items-center justify-center px-4 py-3 bg-gradient-to-r from-blue-500 to-indigo-500 text-white font-medium rounded-lg hover:from-blue-600 hover:to-indigo-600 transition shadow-md">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                    💳 Upload Bukti Transfer
                                </button>
                            @endif

                            {{-- Tombol upload pelunasan untuk status confirmed, in_progress, dan results_uploaded --}}
                            @if(in_array($booking->status, ['confirmed', 'in_progress', 'results_uploaded']) && $booking->remaining_amount > 0)
                                <button onclick="document.getElementById('pelunasanModal').showModal()"
                                    class="w-full flex items-center justify-center px-4 py-3 bg-gradient-to-r from-green-500 to-emerald-500 text-white font-medium rounded-lg hover:from-green-600 hover:to-emerald-600 transition shadow-md">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    💰 Upload Bukti Pelunasan
                                </button>
                                
                                <!-- Info sisa pembayaran -->
                                <div class="p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Sisa Pembayaran:</span>
                                        <span class="font-bold text-red-600">
                                            Rp {{ number_format($booking->remaining_amount, 0, ',', '.') }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">
                                        @if($booking->status == 'results_uploaded')
                                            ⚡ Hasil sudah siap! Segera lunasi untuk download.
                                        @else
                                            ⏳ Bayar sebelum H-7 acara
                                        @endif
                                    </p>
                                </div>
                            @endif

                            @if($booking->canDownloadResults())
                                <!-- Download Results Button -->
                                <a href="{{ $booking->drive_link }}" target="_blank"
                                    class="w-full flex items-center justify-center px-4 py-3 bg-gradient-to-r from-green-500 to-emerald-500 text-white font-medium rounded-lg hover:from-green-600 hover:to-emerald-600 transition shadow-md">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    📥 Download Hasil
                                </a>
                            @endif

                            <!-- Always Available Actions -->
                            <a href="https://wa.me/6281234567890?text=Halo%20Admin%20Memo%20Potret,%20saya%20ingin%20bertanya%20tentang%20booking%20{{ $booking->booking_code }}"
                                target="_blank"
                                class="w-full flex items-center justify-center px-4 py-3 bg-gradient-to-r from-gray-600 to-gray-700 text-white font-medium rounded-lg hover:from-gray-700 hover:to-gray-800 transition shadow-md">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z"
                                        clip-rule="evenodd" />
                                </svg>
                                💬 Konsultasi dengan Admin
                            </a>

                            <button onclick="printInvoice()"
                                class="w-full flex items-center justify-center px-4 py-3 bg-white border-2 border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition shadow-sm">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                </svg>
                                📄 Cetak Invoice
                            </button>
                        </div>

                        <!-- Quick Info -->
                        @if($booking->status == 'pending')
                            <div class="mt-6 pt-6 border-t border-gray-100">
                                <div class="flex items-start">
                                    <svg class="w-4 h-4 text-blue-500 mt-0.5 mr-2 flex-shrink-0" fill="currentColor"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <div class="text-xs text-gray-600">
                                        <strong>Verifikasi:</strong> Proses 1x24 jam kerja setelah upload
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Contact Info -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <h3 class="font-bold text-lg text-gray-900">Butuh Bantuan?</h3>
                        </div>

                        <div class="space-y-4">
                            <div class="flex items-start p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                <svg class="w-5 h-5 text-gray-400 mt-0.5 mr-3 flex-shrink-0" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                        clip-rule="evenodd" />
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Jam Operasional</p>
                                    <p class="text-sm text-gray-600">08:00 - 17:00 WIB (Senin-Sabtu)</p>
                                </div>
                            </div>
                            <div class="flex items-start p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                <svg class="w-5 h-5 text-gray-400 mt-0.5 mr-3 flex-shrink-0" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M14.243 5.757a6 6 0 10-.986 9.284 1 1 0 111.087 1.678A8 8 0 1118 10a3 3 0 01-4.8 2.401A4 4 0 1114 10a1 1 0 102 0c0-1.537-.586-3.07-1.757-4.243zM12 10a2 2 0 10-4 0 2 2 0 004 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Email Support</p>
                                    <p class="text-sm text-gray-600">support@memopotret.com</p>
                                    <p class="text-xs text-gray-500 mt-1">Balasan dalam 1x24 jam</p>
                                </div>
                            </div>
                            <div class="flex items-start p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                <svg class="w-5 h-5 text-gray-400 mt-0.5 mr-3 flex-shrink-0" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M7 2a2 2 0 00-2 2v12a2 2 0 002 2h6a2 2 0 002-2V4a2 2 0 00-2-2H7zm3 14a1 1 0 100-2 1 1 0 000 2z"
                                        clip-rule="evenodd" />
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Telepon/WhatsApp</p>
                                    <p class="text-sm text-gray-600">0812-3456-7890</p>
                                    <p class="text-xs text-gray-500 mt-1">Fast response via WhatsApp</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upload Modal HTML5 Dialog -->
        <dialog id="uploadModal"
            class="bg-transparent backdrop:bg-black/50 p-0 max-w-2xl w-full rounded-xl overflow-hidden">
            <div class="bg-white rounded-xl shadow-2xl modal-content-container">
                <!-- Header - FIXED -->
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white p-6 flex-shrink-0">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold">Upload Bukti Transfer</h3>
                                <p class="text-sm text-blue-100 mt-1">Booking #{{ $booking->booking_code }}</p>
                            </div>
                        </div>
                        <button onclick="document.getElementById('uploadModal').close()"
                            class="text-white hover:text-gray-200 text-2xl">
                            &times;
                        </button>
                    </div>
                </div>

                <!-- Body dengan SCROLL -->
                <div class="modal-body-scroll p-6">
                    <form action="{{ route('client.bookings.upload-payment', $booking) }}" method="POST"
                        enctype="multipart/form-data" id="uploadForm">
                        @csrf

                        <!-- Konten form upload -->
                        <div class="mb-6">
                            <div class="flex items-center mb-4">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                    <span class="text-sm font-bold text-blue-600">1</span>
                                </div>
                                <h6 class="font-semibold text-gray-800">Pilih File Bukti Transfer</h6>
                            </div>
                            <div
                                class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-blue-400 transition bg-gray-50">
                                <input type="file" name="payment_proof" id="payment_proof" class="hidden"
                                    accept="image/*,.pdf" required>
                                <label for="payment_proof" class="cursor-pointer">
                                    <div class="btn-primary inline-flex items-center px-6 py-3">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                        </svg>
                                        Pilih File
                                    </div>
                                </label>
                                <p class="text-sm text-gray-500 mt-4" id="fileInfo">
                                    Format: JPG, PNG, PDF (maksimal 2MB)
                                </p>
                                <div id="previewContainer" class="mt-4 hidden">
                                    <div
                                        class="flex items-center justify-between bg-green-50 p-3 rounded-lg border border-green-200">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <span class="text-sm font-medium text-gray-700" id="fileName"></span>
                                        </div>
                                        <button type="button" onclick="clearFile()"
                                            class="text-red-500 hover:text-red-700">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <div class="flex items-center mb-4">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                    <span class="text-sm font-bold text-blue-600">2</span>
                                </div>
                                <h6 class="font-semibold text-gray-800">Tambahkan Catatan (Opsional)</h6>
                            </div>
                            <textarea name="payment_notes" rows="3"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"
                                placeholder="Contoh: Transfer tanggal {{ date('d F Y') }}, atas nama [Nama Anda], bank [Nama Bank]"></textarea>
                        </div>

                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg mb-6">
                            <div class="flex">
                                <svg class="h-5 w-5 text-yellow-400 mt-0.5 mr-3 flex-shrink-0" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-yellow-800">Pastikan bukti transfer memenuhi
                                        kriteria:</p>
                                    <ul class="mt-2 text-sm text-yellow-700 list-disc pl-5 space-y-1">
                                        <li>Nama pengirim sesuai dengan nama akun Anda</li>
                                        <li>Jumlah transfer tepat <strong>Rp
                                                {{ number_format($booking->total_amount * 0.5, 0, ',', '.') }}</strong>
                                        </li>
                                        <li>Tanggal transfer jelas terbaca</li>
                                        <li>File tidak blur dan informasi terbaca jelas</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="bg-blue-50 p-4 rounded-lg mb-6">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 text-blue-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span class="text-sm text-blue-700 font-medium">Proses verifikasi biasanya memakan waktu
                                    1x24 jam kerja</span>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Footer - FIXED -->
                <div class="flex-shrink-0 border-t bg-gray-50 p-6">
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="document.getElementById('uploadModal').close()"
                            class="btn-secondary px-6 py-2.5">
                            Batal
                        </button>
                        <button type="submit" form="uploadForm" class="btn-primary px-8 py-2.5 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Upload & Verifikasi
                        </button>
                    </div>
                </div>
            </div>
        </dialog>

        <!-- Cancel Modal HTML5 Dialog -->
        <dialog id="cancelModal"
            class="bg-transparent backdrop:bg-black/50 p-0 max-w-2xl w-full rounded-xl overflow-hidden">
            <div class="bg-white rounded-xl shadow-2xl modal-content-container">
                <!-- Header - FIXED -->
                <div class="bg-gradient-to-r from-red-500 to-pink-500 text-white p-6 flex-shrink-0">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.928-.833-2.698 0L4.342 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold">Ajukan Pembatalan</h3>
                                <p class="text-sm text-red-100 mt-1">Hati-hati! Pembatalan memiliki konsekuensi</p>
                            </div>
                        </div>
                        <button onclick="document.getElementById('cancelModal').close()"
                            class="text-white hover:text-gray-200 text-2xl">
                            &times;
                        </button>
                    </div>
                </div>

                <!-- Body dengan SCROLL -->
                <div class="modal-body-scroll p-6">
                    <form action="{{ route('client.bookings.cancel', $booking) }}" method="POST" id="cancelForm">
                        @csrf @method('DELETE')

                        <!-- Konten form cancel -->
                        <div class="bg-red-50 border border-red-200 rounded-xl p-5 mb-6">
                            <div class="flex">
                                <svg class="h-6 w-6 text-red-500 mt-0.5 mr-3 flex-shrink-0" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                <div>
                                    <p class="text-lg font-bold text-red-700">⚠️ Perhatian Penting!</p>
                                    <p class="text-sm text-red-600 mt-2">
                                        Pembatalan hanya dapat dilakukan <strong>minimal H-30 hari</strong> sebelum
                                        acara.<br>
                                        Acara Anda: <strong>{{ $booking->event_date->format('d F Y') }}</strong>
                                        (H-{{ $daysBeforeEvent }})
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-3">Alasan Pembatalan</label>
                            <select name="cancel_reason"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                required>
                                <option value="">Pilih alasan pembatalan...</option>
                                <option value="reschedule">Ingin reschedule/jadwal ulang</option>
                                <option value="budget">Masalah anggaran/keuangan</option>
                                <option value="change_plan">Perubahan rencana acara</option>
                                <option value="emergency">Keadaan darurat</option>
                                <option value="dissatisfied">Tidak puas dengan layanan</option>
                                <option value="other">Lainnya</option>
                            </select>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-3">Detail Alasan
                                Pembatalan</label>
                            <textarea name="cancel_details" rows="4"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-red-500 focus:border-red-500 resize-none"
                                placeholder="Mohon jelaskan alasan pembatalan secara detail..." required></textarea>
                            <p class="text-xs text-gray-500 mt-2">Penjelasan yang detail membantu kami meningkatkan
                                layanan</p>
                        </div>

                        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-5 mb-6">
                            <div class="flex">
                                <svg class="h-6 w-6 text-yellow-500 mt-0.5 mr-3 flex-shrink-0" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 000 2h6a1 1 0 100-2H7z"
                                        clip-rule="evenodd" />
                                </svg>
                                <div>
                                    <p class="text-lg font-bold text-yellow-800">Kebijakan Pembatalan</p>
                                    <ul class="mt-3 text-sm text-yellow-700 list-disc pl-5 space-y-2">
                                        <li><strong>DP 50% tidak dapat dikembalikan</strong> untuk setiap booking yang
                                            dibatalkan.</li>
                                        <li>Pengajuan pembatalan akan diproses dalam 3x24 jam kerja</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Footer - FIXED -->
                <div class="flex-shrink-0 border-t bg-gray-50 p-6">
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="document.getElementById('cancelModal').close()"
                            class="btn-secondary px-6 py-2.5">
                            Kembali
                        </button>
                        <button type="submit" form="cancelForm" class="btn-danger px-8 py-2.5 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Ajukan Pembatalan
                        </button>
                    </div>
                </div>
            </div>
        </dialog>

        <!-- Pelunasan Modal HTML5 Dialog -->
        <dialog id="pelunasanModal"
            class="bg-transparent backdrop:bg-black/50 p-0 max-w-2xl w-full rounded-xl overflow-hidden">
            <div class="bg-white rounded-xl shadow-2xl modal-content-container">
                <!-- Header - FIXED -->
                <div class="bg-gradient-to-r from-green-500 to-emerald-500 text-white p-6 flex-shrink-0">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold">Upload Bukti Pelunasan</h3>
                                <p class="text-sm text-green-100 mt-1">Booking #{{ $booking->booking_code }}</p>
                            </div>
                        </div>
                        <button onclick="document.getElementById('pelunasanModal').close()"
                            class="text-white hover:text-gray-200 text-2xl">
                            &times;
                        </button>
                    </div>
                </div>

                <!-- Body dengan SCROLL -->
                <div class="modal-body-scroll p-6">
                    <form action="{{ route('client.bookings.upload-remaining-payment', $booking) }}" method="POST"
                        enctype="multipart/form-data" id="pelunasanForm">
                        @csrf

                        <!-- Info Pelunasan -->
                        <div
                            class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-xl p-5 mb-6">
                            <div class="flex items-center">
                                <svg class="h-6 w-6 text-green-500 mr-3 flex-shrink-0" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                                <div>
                                    <p class="text-lg font-bold text-green-800">Informasi Pelunasan</p>
                                    <p class="text-sm text-green-700 mt-1">
                                        Sisa pembayaran yang harus dilunasi:
                                        <strong class="text-xl">Rp
                                            {{ number_format($booking->remaining_amount, 0, ',', '.') }}</strong>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Konten form upload pelunasan -->
                        <div class="mb-6">
                            <div class="flex items-center mb-4">
                                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center mr-3">
                                    <span class="text-sm font-bold text-green-600">1</span>
                                </div>
                                <h6 class="font-semibold text-gray-800">Pilih File Bukti Transfer Pelunasan</h6>
                            </div>
                            <div
                                class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-green-400 transition bg-gray-50">
                                <input type="file" name="payment_proof" id="pelunasan_proof" class="hidden"
                                    accept="image/*,.pdf" required>
                                <label for="pelunasan_proof" class="cursor-pointer">
                                    <div class="btn-success inline-flex items-center px-6 py-3">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                        </svg>
                                        Pilih File
                                    </div>
                                </label>
                                <p class="text-sm text-gray-500 mt-4" id="pelunasanFileInfo">
                                    Format: JPG, PNG, PDF (maksimal 2MB)
                                </p>
                                <div id="pelunasanPreviewContainer" class="mt-4 hidden">
                                    <div
                                        class="flex items-center justify-between bg-green-50 p-3 rounded-lg border border-green-200">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <span class="text-sm font-medium text-gray-700"
                                                id="pelunasanFileName"></span>
                                        </div>
                                        <button type="button" onclick="clearPelunasanFile()"
                                            class="text-red-500 hover:text-red-700">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <div class="flex items-center mb-4">
                                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center mr-3">
                                    <span class="text-sm font-bold text-green-600">2</span>
                                </div>
                                <h6 class="font-semibold text-gray-800">Tambahkan Catatan (Opsional)</h6>
                            </div>
                            <textarea name="remaining_payment_notes" rows="3"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-500 resize-none"
                                placeholder="Contoh: Transfer pelunasan tanggal {{ date('d F Y') }}, bank [Nama Bank]"></textarea>
                        </div>

                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg mb-6">
                            <div class="flex">
                                <svg class="h-5 w-5 text-yellow-400 mt-0.5 mr-3 flex-shrink-0" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-yellow-800">Pastikan bukti transfer memenuhi
                                        kriteria:</p>
                                    <ul class="mt-2 text-sm text-yellow-700 list-disc pl-5 space-y-1">
                                        <li>Nama pengirim sesuai dengan nama akun Anda</li>
                                        <li>Jumlah transfer tepat <strong>Rp
                                                {{ number_format($booking->remaining_amount, 0, ',', '.') }}</strong>
                                        </li>
                                        <li>Tanggal transfer jelas terbaca</li>
                                        <li>File tidak blur dan informasi terbaca jelas</li>
                                        <li><strong>Deadline:</strong> Pelunasan maksimal H-7 sebelum acara</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="bg-blue-50 p-4 rounded-lg mb-6">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 text-blue-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span class="text-sm text-blue-700 font-medium">Proses verifikasi pelunasan biasanya
                                    memakan
                                    waktu
                                    1x24 jam kerja. Setelah terverifikasi, booking status akan berubah menjadi "Dalam
                                    Proses".</span>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Footer - FIXED -->
                <div class="flex-shrink-0 border-t bg-gray-50 p-6">
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="document.getElementById('pelunasanModal').close()"
                            class="btn-secondary px-6 py-2.5">
                            Batal
                        </button>
                        <button type="submit" form="pelunasanForm" class="btn-success px-8 py-2.5 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Upload Bukti Pelunasan
                        </button>
                    </div>
                </div>
            </div>
        </dialog>

        @push('scripts')
            <script>
                function printInvoice() {
                    // Create printable invoice
                    const printWindow = window.open('', '_blank');
                    printWindow.document.write(`
                        <html>
                        <head>
                            <title>Invoice #{{ $booking->booking_code }}</title>
                            <style>
                                @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
                                * { margin: 0; padding: 0; box-sizing: border-box; }
                                body { font-family: 'Inter', sans-serif; margin: 40px; color: #374151; }
                                .invoice-container { max-width: 800px; margin: 0 auto; }
                                .invoice-header { border-bottom: 3px solid #4f46e5; padding-bottom: 30px; margin-bottom: 40px; }
                                .invoice-title { font-size: 32px; font-weight: 700; color: #1f2937; margin-bottom: 5px; }
                                .invoice-subtitle { color: #6b7280; font-size: 14px; }
                                .section-title { font-size: 18px; font-weight: 600; color: #1f2937; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #e5e7eb; }
                                .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 40px; margin-bottom: 40px; }
                                .info-box { background: #f9fafb; padding: 20px; border-radius: 10px; border: 1px solid #e5e7eb; }
                                .info-label { font-size: 12px; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 5px; }
                                .info-value { font-size: 16px; font-weight: 500; color: #1f2937; }
                                .invoice-table { width: 100%; border-collapse: collapse; margin: 30px 0; }
                                .invoice-table th { background: #f3f4f6; text-align: left; padding: 15px; font-size: 13px; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; border: 1px solid #e5e7eb; }
                                .invoice-table td { padding: 15px; border: 1px solid #e5e7eb; }
                                .invoice-table .total-row { background: #f0f9ff; font-weight: 600; }
                                .amount { text-align: right; }
                                .total-section { background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); color: white; padding: 25px; border-radius: 12px; margin-top: 30px; }
                                .total-label { font-size: 14px; opacity: 0.9; }
                                .total-value { font-size: 28px; font-weight: 700; margin-top: 5px; }
                                .footer { margin-top: 50px; padding-top: 20px; border-top: 1px solid #e5e7eb; text-align: center; color: #6b7280; font-size: 12px; }
                                @media print {
                                    body { margin: 20px; }
                                    .no-print { display: none; }
                                }
                            </style>
                        </head>
                        <body>
                            <div class="invoice-container">
                                <div class="invoice-header">
                                    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                                        <div>
                                            <h1 class="invoice-title">MEMO POTRET STUDIO</h1>
                                            <p class="invoice-subtitle">Professional Photography & Videography Services</p>
                                            <p style="margin-top: 20px; color: #6b7280;">
                                                Jl. Contoh No. 123, Jakarta<br>
                                                support@memopotret.com • 0812-3456-7890
                                            </p>
                                        </div>
                                        <div style="text-align: right;">
                                            <div style="background: #4f46e5; color: white; padding: 20px; border-radius: 10px; display: inline-block;">
                                                <div style="font-size: 14px; opacity: 0.9;">INVOICE</div>
                                                <div style="font-size: 24px; font-weight: 700;">#{{ $booking->booking_code }}</div>
                                                <div style="font-size: 14px; margin-top: 5px;">{{ $booking->created_at->format('d F Y') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="info-grid">
                                    <div class="info-box">
                                        <div class="info-label">Kepada</div>
                                        <div class="info-value" style="font-size: 18px; font-weight: 600; margin-bottom: 10px;">{{ $booking->user->name }}</div>
                                        <div style="color: #6b7280; font-size: 14px;">
                                            {{ $booking->user->email }}<br>
                                            {{ $booking->user->phone ?? 'N/A' }}
                                        </div>
                                    </div>
                                    <div class="info-box">
                                        <div class="info-label">Detail Acara</div>
                                        <div style="display: grid; gap: 10px;">
                                            <div>
                                                <div style="font-size: 14px; color: #6b7280;">Tanggal</div>
                                                <div class="info-value">{{ $booking->event_date->format('d F Y') }}</div>
                                            </div>
                                            <div>
                                                <div style="font-size: 14px; color: #6b7280;">Waktu</div>
                                                <div class="info-value">{{ date('H:i', strtotime($booking->event_time)) }} WIB</div>
                                            </div>
                                            <div>
                                                <div style="font-size: 14px; color: #6b7280;">Lokasi</div>
                                                <div class="info-value">{{ $booking->event_location }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <h3 class="section-title">Rincian Layanan</h3>
                                <table class="invoice-table">
                                    <thead>
                                        <tr>
                                            <th width="50%">Deskripsi</th>
                                            <th>Tanggal</th>
                                            <th>Waktu</th>
                                            <th>Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div style="font-weight: 600;">{{ $booking->package->name }}</div>
                                                <div style="font-size: 13px; color: #6b7280; margin-top: 5px;">
                                                    {{ $booking->package->description ?? 'Paket photography professional' }}
                                                </div>
                                            </td>
                                            <td>{{ $booking->event_date->format('d/m/Y') }}</td>
                                            <td>{{ date('H:i', strtotime($booking->event_time)) }}</td>
                                            <td class="amount">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</td>
                                        </tr>
                                    </tbody>
                                </table>

                                <h3 class="section-title">Rincian Pembayaran</h3>
                                <table class="invoice-table">
                                    <tbody>
                                        <tr>
                                            <td width="80%">Total Biaya Layanan</td>
                                            <td class="amount">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <td>Down Payment (50%)</td>
                                            <td class="amount">Rp {{ number_format($booking->dp_amount, 0, ',', '.') }}</td>
                                        </tr>
                                        <tr class="total-row">
                                            <td><strong>Sisa Pembayaran</strong></td>
                                            <td class="amount"><strong>Rp {{ number_format($booking->remaining_amount, 0, ',', '.') }}</strong></td>
                                        </tr>
                                    </tbody>
                                </table>

                                <div class="total-section">
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <div>
                                            <div class="total-label">STATUS PEMBAYARAN</div>
                                            <div class="total-value">{{ strtoupper($booking->status) }}</div>
                                        </div>
                                        <div style="text-align: right;">
                                            <div class="total-label">TOTAL TAGIHAN</div>
                                            <div class="total-value">Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</div>
                                        </div>
                                    </div>
                                </div>

                                <div style="margin-top: 40px; padding: 20px; background: #f9fafb; border-radius: 10px; border: 1px solid #e5e7eb;">
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
                                        <div>
                                            <div style="font-size: 14px; color: #6b7280; margin-bottom: 10px;">Ketentuan Pembayaran</div>
                                            <ul style="font-size: 13px; color: #4b5563; line-height: 1.6; padding-left: 20px;">
                                                <li>DP 50% untuk konfirmasi booking</li>
                                                <li>Pelunasan Maksimal H+7 sesudah acara</li>
                                                <li>Transfer ke BCA 123-456-7890</li>
                                                <li>Atas nama: Memo Potret Studio</li>
                                            </ul>
                                        </div>
                                        <div>
                                            <div style="font-size: 14px; color: #6b7280; margin-bottom: 10px;">Informasi Tambahan</div>
                                            <div style="font-size: 13px; color: #4b5563; line-height: 1.6;">
                                                <strong>Status:</strong> {{ ucfirst($booking->status) }}<br>
                                                <strong>Durasi:</strong> {{ $booking->package->duration_hours }} jam<br>
                                                <strong>Catatan:</strong> {{ $booking->notes ?? 'Tidak ada catatan' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="footer">
                                    <p>Invoice ini sah dan diterbitkan secara elektronik oleh Memo Potret Studio</p>
                                    <p style="margin-top: 10px;">Tanggal cetak: {{ date('d F Y H:i') }}</p>
                                </div>

                                <div class="no-print" style="margin-top: 50px; text-align: center;">
                                    <button onclick="window.print()" style="padding: 12px 30px; background: #4f46e5; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; margin-right: 10px;">
                                        🖨️ Cetak Invoice
                                    </button>
                                    <button onclick="window.close()" style="padding: 12px 30px; background: #6b7280; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                                        ✕ Tutup
                                    </button>
                                </div>
                            </div>
                        </body>
                        </html>
                    `);
                    printWindow.document.close();
                }

                // File upload preview functionality
                document.getElementById('payment_proof').addEventListener('change', function (e) {
                    const file = e.target.files[0];
                    if (file) {
                        const fileName = file.name;
                        const fileSize = (file.size / 1024 / 1024).toFixed(2); // MB

                        if (fileSize > 2) {
                            alert('File terlalu besar! Maksimal 2MB.');
                            this.value = '';
                            return;
                        }

                        document.getElementById('fileName').textContent = `${fileName} (${fileSize} MB)`;
                        document.getElementById('previewContainer').classList.remove('hidden');
                        document.getElementById('fileInfo').classList.add('hidden');
                    }
                });

                function clearFile() {
                    document.getElementById('payment_proof').value = '';
                    document.getElementById('previewContainer').classList.add('hidden');
                    document.getElementById('fileInfo').classList.remove('hidden');
                }

                // Form validation
                document.getElementById('uploadForm')?.addEventListener('submit', function (e) {
                    const fileInput = document.getElementById('payment_proof');
                    if (!fileInput.value) {
                        e.preventDefault();
                        alert('Silakan pilih file bukti transfer terlebih dahulu!');
                        fileInput.focus();
                    }
                });

                // Validasi cancel form
                document.getElementById('cancelForm')?.addEventListener('submit', function (e) {
                    // Data dari controller
                    const daysBeforeEvent = {{ $daysBeforeEvent }};
                    const dpAmount = {{ $dpAmount }};

                    // Validasi di frontend
                    if (daysBeforeEvent < 30) {
                        e.preventDefault();
                        alert(`❌ TIDAK BISA DIBATALKAN!\n\n` +
                            `Pembatalan hanya dapat dilakukan MAKSIMAL H-30 hari sebelum acara.\n` +
                            `Acara Anda H-${daysBeforeEvent}.\n` +
                            `Silakan hubungi admin untuk konsultasi.`);
                        return;
                    }

                    // Validasi jika acara sudah lewat
                    if (daysBeforeEvent < 0) {
                        e.preventDefault();
                        alert(`❌ TIDAK BISA DIBATALKAN!\n\n` +
                            `Acara sudah berlalu.\n` +
                            `Tidak dapat membatalkan acara yang sudah selesai.`);
                        return;
                    }

                    // Konfirmasi DP hangus
                    const confirmCancel = confirm(`⚠️ KONFIRMASI PEMBATALAN\n\n` +
                        `DP 50% (Rp ${dpAmount.toLocaleString('id-ID')}) TIDAK AKAN DIKEMBALIKAN.\n` +
                        `Tindakan ini TIDAK DAPAT DIBATALKAN.\n\n` +
                        `Yakin ingin membatalkan booking?`);

                    if (!confirmCancel) {
                        e.preventDefault();
                    }
                });

                document.getElementById('pelunasan_proof')?.addEventListener('change', function (e) {
                    const file = e.target.files[0];
                    if (file) {
                        const fileName = file.name;
                        const fileSize = (file.size / 1024 / 1024).toFixed(2); // MB

                        if (fileSize > 2) {
                            alert('File terlalu besar! Maksimal 2MB.');
                            this.value = '';
                            return;
                        }

                        document.getElementById('pelunasanFileName').textContent = `${fileName} (${fileSize} MB)`;
                        document.getElementById('pelunasanPreviewContainer').classList.remove('hidden');
                        document.getElementById('pelunasanFileInfo').classList.add('hidden');
                    }
                });

                function clearPelunasanFile() {
                    document.getElementById('pelunasan_proof').value = '';
                    document.getElementById('pelunasanPreviewContainer').classList.add('hidden');
                    document.getElementById('pelunasanFileInfo').classList.remove('hidden');
                }

                // Form validation untuk pelunasan
                document.getElementById('pelunasanForm')?.addEventListener('submit', function (e) {
                    const fileInput = document.getElementById('pelunasan_proof');
                    if (!fileInput.value) {
                        e.preventDefault();
                        alert('Silakan pilih file bukti transfer pelunasan terlebih dahulu!');
                        fileInput.focus();
                        return;
                    }

                    // Konfirmasi jumlah pelunasan
                    const remainingAmount = {{ $booking->remaining_amount }};
                    const confirmPayment = confirm(`Konfirmasi Upload Bukti Pelunasan\n\n` +
                        `Jumlah yang harus dilunasi: Rp ${remainingAmount.toLocaleString('id-ID')}\n\n` +
                        `Pastikan jumlah transfer sesuai dengan nominal di atas.\n` +
                        `Lanjutkan upload?`);

                    if (!confirmPayment) {
                        e.preventDefault();
                    }
                });
            </script>
        @endpush

        <style>
            /* Animasi untuk modal dialog */
            dialog {
                animation: fadeIn 0.3s ease-out;
                border: none;
                border-radius: 0.75rem;
                max-height: 90vh;
                overflow: hidden;
            }

            dialog::backdrop {
                background-color: rgba(0, 0, 0, 0.5);
                animation: fadeIn 0.3s ease-out;
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                }
                to {
                    opacity: 1;
                }
            }

            /* Responsif untuk mobile */
            @media (max-width: 640px) {
                dialog {
                    margin: 1rem;
                    width: calc(100% - 2rem);
                    max-width: none;
                    max-height: 85vh;
                }
            }

            /* Container untuk konten modal dengan scroll */
            .modal-content-container {
                max-height: 80vh;
                display: flex;
                flex-direction: column;
            }

            .modal-body-scroll {
                flex: 1;
                overflow-y: auto;
                max-height: calc(80vh - 200px);
                padding-right: 0.5rem;
            }

            /* Custom scrollbar */
            .modal-body-scroll::-webkit-scrollbar {
                width: 6px;
            }

            .modal-body-scroll::-webkit-scrollbar-track {
                background: #f1f1f1;
                border-radius: 3px;
            }

            .modal-body-scroll::-webkit-scrollbar-thumb {
                background: #c1c1c1;
                border-radius: 3px;
            }

            .modal-body-scroll::-webkit-scrollbar-thumb:hover {
                background: #a1a1a1;
            }

            .btn-success {
                background-color: #10b981;
                color: white;
                padding: 0.5rem 1.5rem;
                border-radius: 0.5rem;
                font-weight: 500;
                transition: background-color 0.2s;
            }

            .btn-success:hover {
                background-color: #059669;
            }
        </style>
    </div>
</x-app-layout>