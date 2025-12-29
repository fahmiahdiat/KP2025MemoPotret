<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold">Laporan Keuangan</h1>
                <div class="flex items-center gap-2 mt-1 text-sm text-gray-600">
                    <i class="fas fa-calendar"></i>
                    <span>Verifikasi: {{ $startDate }} s/d {{ $endDate }}</span>
                </div>
            </div>
            
            {{-- Filter Period --}}
            <form method="GET" class="flex items-center gap-2">
                <div class="flex items-center gap-2 bg-white border rounded-lg px-3 py-2">
                    <input type="date" name="start_date" value="{{ $startDate }}" 
                           class="border-0 p-0 text-sm w-32">
                    <span class="text-gray-400">-</span>
                    <input type="date" name="end_date" value="{{ $endDate }}" 
                           class="border-0 p-0 text-sm w-32">
                    <button type="submit" class="ml-2 text-blue-600">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </x-slot>
    <div class="py-6">
    {{-- BAGIAN 1: UANG MASUK PERIODE INI --}}
    <div class="mb-8">
        <h2 class="text-lg font-semibold mb-4">Uang Masuk (Verifikasi {{ $startDate }} - {{ $endDate }})</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            {{-- TOTAL UANG MASUK --}}
            <div class="bg-white border rounded-lg p-5">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm text-gray-500">Total Uang Masuk</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">
                            Rp {{ number_format($totalCashIn) }}
                        </p>
                        <p class="text-xs text-gray-400 mt-2">
                            {{ $stats['total_transactions'] }} transaksi diverifikasi
                        </p>
                    </div>
                    <div class="text-green-500">
                        <i class="fas fa-wallet text-2xl"></i>
                    </div>
                </div>
            </div>
            
            {{-- DP YANG MASUK --}}
            <div class="bg-white border rounded-lg p-5">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm text-gray-500">DP Diterima</p>
                        <p class="text-2xl font-bold text-blue-600 mt-1">
                            Rp {{ number_format($dpCashIn) }}
                        </p>
                        <div class="text-xs text-gray-500 mt-2">
                            <div>{{ $stats['dp_payments_count'] }} transaksi DP</div>
                            <div>Rata-rata: Rp {{ number_format($stats['avg_dp']) }}</div>
                        </div>
                    </div>
                    <div class="text-blue-500">
                        <i class="fas fa-money-check-alt text-2xl"></i>
                    </div>
                </div>
            </div>
            
            {{-- PELUNASAN YANG MASUK --}}
            <div class="bg-white border rounded-lg p-5">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm text-gray-500">Pelunasan Diterima</p>
                        <p class="text-2xl font-bold text-purple-600 mt-1">
                            Rp {{ number_format($remainingCashIn) }}
                        </p>
                        <div class="text-xs text-gray-500 mt-2">
                            <div>{{ $stats['remaining_payments_count'] }} transaksi pelunasan</div>
                            <div>Rata-rata: Rp {{ number_format($stats['avg_remaining']) }}</div>
                        </div>
                    </div>
                    <div class="text-purple-500">
                        <i class="fas fa-check-circle text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    {{-- BAGIAN 2: SITUASI KAS SAAT INI --}}
    <div class="mb-8">
        <h2 class="text-lg font-semibold mb-4">Situasi Kas Saat Ini</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            {{-- PIUTANG (DP SUDAH BAYAR, BELUM LUNAS) --}}
            <div class="bg-white border rounded-lg p-5">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm text-gray-500">Piutang (Belum Dilunasi)</p>
                        <p class="text-2xl font-bold text-amber-600 mt-1">
                            Rp {{ number_format($outstanding) }}
                        </p>
                        <p class="text-xs text-gray-400 mt-2">
                            DP sudah dibayar, pelunasan belum
                        </p>
                    </div>
                    <div class="text-amber-500">
                        <i class="fas fa-clock text-2xl"></i>
                    </div>
                </div>
            </div>
            
            {{-- NILAI KONTRAK PERIODE INI --}}
            <div class="bg-white border rounded-lg p-5">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm text-gray-500">Nilai Booking Baru</p>
                        <p class="text-2xl font-bold text-gray-700 mt-1">
                            Rp {{ number_format($bookingValue) }}
                        </p>
                        <p class="text-xs text-gray-400 mt-2">
                            Booking dibuat {{ $startDate }} - {{ $endDate }}
                        </p>
                    </div>
                    <div class="text-gray-500">
                        <i class="fas fa-file-contract text-2xl"></i>
                    </div>
                </div>
                
                {{-- Persentase Realisasi --}}
                @if($bookingValue > 0)
                <div class="mt-4 pt-4 border-t">
                    <div class="flex justify-between text-sm mb-1">
                        <span>Realisasi Kas:</span>
                        <span class="font-medium">
                            {{ round(($totalCashIn/$bookingValue)*100) }}%
                        </span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full" 
                             style="width: {{ min(100, round(($totalCashIn/$bookingValue)*100)) }}%">
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
        {{-- BAGIAN 3: DETAIL TRANSAKSI KAS --}}
    <div class="bg-white border rounded-lg">
        <div class="p-4 border-b flex justify-between items-center">
            <div>
                <h2 class="font-semibold">Detail Transaksi Kas</h2>
                <p class="text-sm text-gray-500">
                    {{ $transactions->count() }} pembayaran diverifikasi
                </p>
            </div>
            
            <div class="flex items-center gap-3">
                <a href="{{ route('owner.reports.financial.export', ['start_date' => $startDate, 'end_date' => $endDate]) }}" 
                   class="flex items-center gap-2 px-3 py-2 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 text-sm">
                    <i class="fas fa-download"></i>
                    Export Excel
                </a>
            </div>
        </div>
        
        {{-- TABEL SEDERHANA --}}
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr class="text-left text-sm text-gray-500">
                        <th class="p-3">Tanggal Verifikasi</th>
                        <th class="p-3">Kode Booking</th>
                        <th class="p-3">Klien</th>
                        <th class="p-3">Jenis</th>
                        <th class="p-3">Jumlah</th>
                        <th class="p-3">Status Booking</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $trx)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="p-3">
                            <div class="font-medium">{{ $trx['date']->format('d/m/Y') }}</div>
                            <div class="text-xs text-gray-500">{{ $trx['date']->format('H:i') }}</div>
                        </td>
                        
                        <td class="p-3">
                            <div class="font-medium text-blue-600">{{ $trx['booking']->booking_code }}</div>
                            <div class="text-xs text-gray-500">
                                Event: {{ $trx['booking']->event_date->format('d/m/Y') }}
                            </div>
                        </td>
                        
                        <td class="p-3">
                            <div>{{ $trx['booking']->user->name }}</div>
                            <div class="text-xs text-gray-500">{{ $trx['booking']->user->phone ?? '-' }}</div>
                        </td>
                        
                        <td class="p-3">
                            @if($trx['type'] == 'dp')
                            <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-full">
                                DP
                            </span>
                            @else
                            <span class="px-2 py-1 text-xs bg-purple-100 text-purple-800 rounded-full">
                                Pelunasan
                            </span>
                            @endif
                        </td>
                        
                        <td class="p-3">
                            <div class="font-bold">
                                Rp {{ number_format($trx['amount']) }}
                            </div>
                            @if($trx['type'] == 'dp')
                            <div class="text-xs text-gray-500">
                                Sisa: Rp {{ number_format($trx['booking']->remaining_amount) }}
                            </div>
                            @endif
                        </td>
                        
                        <td class="p-3">
                            @php
                                $statusLabels = [
                                    'confirmed' => 'Konfirmasi',
                                    'completed' => 'Selesai',
                                    'cancelled' => 'Batal'
                                ];
                            @endphp
                            <span class="px-2 py-1 text-xs rounded-full 
                                {{ $trx['booking']->status == 'completed' ? 'bg-green-100 text-green-800' : 
                                   ($trx['booking']->status == 'cancelled' ? 'bg-red-100 text-red-800' : 
                                   'bg-blue-100 text-blue-800') }}">
                                {{ $statusLabels[$trx['booking']->status] ?? $trx['booking']->status }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            @if($transactions->count() == 0)
            <div class="p-8 text-center text-gray-500">
                <i class="fas fa-wallet text-3xl mb-3"></i>
                <p>Tidak ada transaksi kas pada periode ini</p>
            </div>
            @endif
        </div>
        
        {{-- FOOTER TABEL --}}
        @if($transactions->count() > 0)
        <div class="p-4 border-t flex justify-between items-center text-sm">
            <div class="text-gray-500">
                Total {{ $transactions->count() }} transaksi
            </div>
            <div class="font-bold">
                Total: Rp {{ number_format($totalCashIn) }}
            </div>
        </div>
        @endif
    </div>
</div>
</x-app-layout>