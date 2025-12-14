<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-800">Laporan</h2>
            <a href="{{ route('owner.reports.export', request()->all()) }}" class="btn-primary">Export Excel</a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filter -->
            <div class="card mb-6">
                <form method="GET" class="grid md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm mb-1">Dari</label>
                        <input type="date" name="start_date" value="{{ $startDate }}" class="w-full border rounded px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm mb-1">Sampai</label>
                        <input type="date" name="end_date" value="{{ $endDate }}" class="w-full border rounded px-3 py-2">
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="btn-primary w-full">Filter</button>
                    </div>
                </form>
            </div>

            <!-- Summary -->
            <div class="grid md:grid-cols-4 gap-4 mb-6">
                <div class="card">
                    <div class="text-sm text-gray-500">Total Pendapatan</div>
                    <div class="text-2xl font-bold text-green-600">
                        Rp {{ number_format($financialData->sum('total_revenue'), 0, ',', '.') }}
                    </div>
                </div>
                <div class="card">
                    <div class="text-sm text-gray-500">Total Booking</div>
                    <div class="text-2xl font-bold">{{ $financialData->sum('total_bookings') }}</div>
                </div>
                <div class="card">
                    <div class="text-sm text-gray-500">Rata-rata/Booking</div>
                    <div class="text-2xl font-bold">
                        Rp {{ number_format($financialData->avg('total_revenue'), 0, ',', '.') }}
                    </div>
                </div>
                <div class="card">
                    <div class="text-sm text-gray-500">Hari Produktif</div>
                    <div class="text-2xl font-bold">{{ $financialData->where('total_bookings', '>', 0)->count() }}</div>
                </div>
            </div>

            <!-- Financial Table -->
            <div class="card mb-6">
                <h3 class="font-bold mb-4">Detail Harian</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b">
                                <th class="py-3 text-left">Tanggal</th>
                                <th class="py-3 text-left">Booking</th>
                                <th class="py-3 text-left">Pendapatan</th>
                                <th class="py-3 text-left">DP</th>
                                <th class="py-3 text-left">Sisa</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($financialData as $data)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-3">{{ \Carbon\Carbon::parse($data->date)->format('d/m') }}</td>
                                <td class="py-3">{{ $data->total_bookings }}</td>
                                <td class="py-3 font-bold text-green-600">Rp {{ number_format($data->total_revenue, 0, ',', '.') }}</td>
                                <td class="py-3">Rp {{ number_format($data->total_dp, 0, ',', '.') }}</td>
                                <td class="py-3">Rp {{ number_format($data->total_remaining, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="font-bold border-t">
                            <tr>
                                <td class="py-3">TOTAL</td>
                                <td class="py-3">{{ $financialData->sum('total_bookings') }}</td>
                                <td class="py-3 text-green-600">Rp {{ number_format($financialData->sum('total_revenue'), 0, ',', '.') }}</td>
                                <td class="py-3">Rp {{ number_format($financialData->sum('total_dp'), 0, ',', '.') }}</td>
                                <td class="py-3">Rp {{ number_format($financialData->sum('total_remaining'), 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Top Clients -->
            <div class="card">
                <h3 class="font-bold mb-4">Top Client</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b">
                                <th class="py-3 text-left">Nama</th>
                                <th class="py-3 text-left">Booking</th>
                                <th class="py-3 text-left">Total Belanja</th>
                                <th class="py-3 text-left">Rata-rata</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topClients as $client)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-3">{{ $client->name }}</td>
                                <td class="py-3">{{ $client->bookings_count }}</td>
                                <td class="py-3 font-bold text-green-600">Rp {{ number_format($client->bookings_sum_total_amount, 0, ',', '.') }}</td>
                                <td class="py-3">Rp {{ number_format($client->bookings_count > 0 ? $client->bookings_sum_total_amount / $client->bookings_count : 0, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>