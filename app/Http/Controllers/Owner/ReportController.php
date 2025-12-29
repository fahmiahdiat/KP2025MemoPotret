<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use App\Models\Package;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{

    // Laporan Keuangan
    public function financial(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        // ================================
        // 1. CASH FLOW (UANG BENAR-BENAR MASUK)
        // ================================

        // DP yang diverifikasi dalam periode ini
        $dpCashIn = Booking::whereNotNull('dp_verified_at')
            ->whereBetween('dp_verified_at', [$start, $end])
            ->sum('dp_amount');

        // Pelunasan yang diverifikasi dalam periode ini  
        $remainingCashIn = Booking::whereNotNull('remaining_verified_at')
            ->whereBetween('remaining_verified_at', [$start, $end])
            ->sum(DB::raw('total_amount - dp_amount'));


        $totalCashIn = $dpCashIn + $remainingCashIn;

        // ================================
        // 2. OUTSTANDING (PIUTANG)
        // ================================

        // Booking dengan DP sudah diverifikasi tapi belum lunas
        $outstanding = Booking::whereNotNull('dp_verified_at')
            ->where('remaining_amount', '>', 0)
            ->where('status', '!=', 'cancelled')
            ->sum('remaining_amount');

        // ================================
        // 3. BOOKING VALUE (NILAI KONTRAK)
        // ================================

        // Booking yang dibuat dalam periode (nilai kontrak)
        $bookingValue = Booking::whereBetween('created_at', [$start, $end])
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount');

        // ================================
        // 4. TRANSACTION LIST (untuk tabel)
        // ================================

        // Transaksi pembayaran (DP dan pelunasan)
        $transactions = collect();

        // Tambahkan DP payments
        $dpPayments = Booking::with(['user', 'package'])
            ->whereNotNull('dp_verified_at')
            ->whereBetween('dp_verified_at', [$start, $end])
            ->get()
            ->map(function ($booking) {
                return [
                    'type' => 'dp',
                    'date' => $booking->dp_verified_at,
                    'booking' => $booking,
                    'amount' => $booking->dp_amount,
                    'description' => 'DP - ' . $booking->booking_code,
                ];
            });

        // Tambahkan remaining payments
        $remainingPayments = Booking::with(['user', 'package'])
            ->whereNotNull('remaining_verified_at')
            ->whereBetween('remaining_verified_at', [$start, $end])
            ->get()
            ->map(function ($booking) {
                return [
                    'type' => 'pelunasan',
                    'date' => $booking->remaining_verified_at,
                    'booking' => $booking,
                    'amount' => $booking->total_amount - $booking->dp_amount,
                    'description' => 'Pelunasan - ' . $booking->booking_code,
                ];
            });

        $transactions = $dpPayments->merge($remainingPayments)
            ->sortByDesc('date')
            ->values();

        // ================================
        // 5. STATISTICS
        // ================================

        $stats = [
            'dp_payments_count' => Booking::whereNotNull('dp_verified_at')
                ->whereBetween('dp_verified_at', [$start, $end])
                ->count(),
            'remaining_payments_count' => Booking::whereNotNull('remaining_verified_at')
                ->whereBetween('remaining_verified_at', [$start, $end])
                ->count(),
            'total_transactions' => $transactions->count(),
            'avg_dp' => Booking::whereNotNull('dp_verified_at')
                ->whereBetween('dp_verified_at', [$start, $end])
                ->avg('dp_amount') ?? 0,
            'avg_remaining' => Booking::whereNotNull('remaining_verified_at')
                ->whereBetween('remaining_verified_at', [$start, $end])
                ->select(DB::raw('AVG(total_amount - dp_amount) as avg_remaining'))
                ->value('avg_remaining') ?? 0,

        ];

        return view('owner.reports.financial', compact(
            'dpCashIn',
            'remainingCashIn',
            'totalCashIn',
            'outstanding',
            'bookingValue',
            'transactions',
            'stats',
            'startDate',
            'endDate'
        ));
    }

    // Laporan Booking
    public function bookings(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->subMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        // Query bookings
        $query = Booking::with(['user', 'package'])
            ->whereBetween('created_at', [$start, $end]);

        // Filter by status jika ada
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $bookings = $query->orderBy('created_at', 'desc')
            ->paginate(20);

        // Hitung stats yang dibutuhkan di view
        $totalBookings = $bookings->total();
        $completedBookings = Booking::whereBetween('created_at', [$start, $end])
            ->where('status', 'completed')
            ->count();
        $conversionRate = $totalBookings > 0 ? round(($completedBookings / $totalBookings) * 100, 1) : 0;

        // Hitung processing days untuk completed bookings
        $completedBookingsWithDates = Booking::whereBetween('created_at', [$start, $end])
            ->where('status', 'completed')
            ->whereNotNull('completed_at')
            ->get();

        $avgProcessingDays = $completedBookingsWithDates->count() > 0
            ? round($completedBookingsWithDates->avg(function ($booking) {
                return $booking->created_at->diffInDays($booking->completed_at);
            }), 1)
            : 0;

        return view('owner.reports.bookings', compact(
            'bookings',
            'startDate',
            'endDate',
            'totalBookings',
            'completedBookings',
            'conversionRate',
            'avgProcessingDays'
        ));
    }

    // Laporan Paket
    public function packages(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->subYear()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        // Package performance
        $packagePerformance = Package::withCount([
            'bookings' => function ($query) use ($start, $end) {
                $query->whereBetween('created_at', [$start, $end]);
            }
        ])
            ->withSum([
                'bookings' => function ($query) use ($start, $end) {
                    $query->whereBetween('created_at', [$start, $end]);
                }
            ], 'total_amount')
            ->orderBy('bookings_sum_total_amount', 'desc')
            ->get();

        // Stats untuk cards
        $totalPackages = Package::count();
        $activePackages = Package::where('is_active', true)->count();
        $soldPackages = Package::has('bookings')->count();
        $totalSales = Booking::whereBetween('created_at', [$start, $end])->count();
        $totalRevenue = Booking::whereBetween('created_at', [$start, $end])->sum('total_amount');
        $avgBookingValue = $totalSales > 0 ? round($totalRevenue / $totalSales) : 0;

        // Top packages
        $topPackages = $packagePerformance->take(3);

        // Package comparison (untuk compat dengan view lama jika masih ada)
        $packageComparison = [
            'total_packages' => $totalPackages,
            'active_packages' => $activePackages,
            'packages_with_bookings' => $soldPackages,
            'avg_price' => Package::avg('price') ?? 0,
            'most_expensive' => Package::max('price') ?? 0,
            'cheapest' => Package::min('price') ?? 0,
        ];

        return view('owner.reports.packages', compact(
            'packagePerformance',
            'totalPackages',
            'activePackages',
            'soldPackages',
            'totalSales',
            'totalRevenue',
            'avgBookingValue',
            'topPackages',
            'packageComparison', // Tetap kirim untuk compatibility
            'startDate',
            'endDate'
        ));
    }

    // Laporan Klien
    public function clients(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->subYear()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        // Query clients with their bookings
        $query = User::where('role', 'client')
            ->withCount([
                'bookings' => function ($query) use ($start, $end) {
                    $query->whereBetween('created_at', [$start, $end]);
                }
            ])
            ->withSum([
                'bookings' => function ($query) use ($start, $end) {
                    $query->whereBetween('created_at', [$start, $end]);
                }
            ], 'total_amount')
            ->with([
                'bookings' => function ($query) use ($start, $end) {
                    $query->whereBetween('created_at', [$start, $end])
                        ->orderBy('created_at', 'desc')
                        ->limit(1);
                }
            ]);

        // Sorting
        if ($request->input('sort') == 'bookings') {
            $query->orderBy('bookings_count', 'desc');
        } elseif ($request->input('sort') == 'new') {
            $query->orderBy('created_at', 'desc');
        } else {
            $query->orderBy('bookings_sum_total_amount', 'desc');
        }

        $clients = $query->paginate(20);

        // Stats untuk cards
        $totalClients = User::where('role', 'client')->count();
        $newClients = User::where('role', 'client')
            ->whereBetween('created_at', [$start, $end])
            ->count();
        $activeClients = User::where('role', 'client')
            ->whereHas('bookings', function ($query) use ($start, $end) {
                $query->whereBetween('created_at', [$start, $end]);
            })
            ->count();
        $returningClients = User::where('role', 'client')
            ->whereHas('bookings', function ($query) use ($start, $end) {
                $query->whereBetween('created_at', [$start, $end]);
            }, '>', 1)
            ->count();

        $totalSpent = Booking::whereBetween('created_at', [$start, $end])->sum('total_amount');
        $avgClientValue = $activeClients > 0 ? round($totalSpent / $activeClients) : 0;

        // Top clients
        $topClients = User::where('role', 'client')
            ->withCount([
                'bookings' => function ($query) use ($start, $end) {
                    $query->whereBetween('created_at', [$start, $end]);
                }
            ])
            ->withSum([
                'bookings' => function ($query) use ($start, $end) {
                    $query->whereBetween('created_at', [$start, $end]);
                }
            ], 'total_amount')
            ->orderBy('bookings_sum_total_amount', 'desc')
            ->take(5)
            ->get();

        $loyalClients = User::where('role', 'client')
            ->has('bookings', '>', 1)
            ->withCount('bookings')
            ->withSum('bookings', 'total_amount')
            ->orderBy('bookings_count', 'desc')
            ->take(5)
            ->get();

        // Client analysis untuk compatibility
        $clientAnalysis = [
            'total_clients' => $totalClients,
            'clients_with_bookings' => $activeClients,
            'new_clients' => $newClients,
            'returning_clients' => $returningClients,
        ];

        return view('owner.reports.clients', compact(
            'clients',
            'totalClients',
            'newClients',
            'activeClients',
            'returningClients',
            'avgClientValue',
            'topClients',
            'loyalClients',
            'clientAnalysis', // Tetap kirim untuk compatibility
            'startDate',
            'endDate'
        ));
    }

    // Export function (EXISTING)
    public function export(Request $request)
    {
        $type = $request->input('type', 'financial');
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        $filename = "{$type}-report-{$startDate}-to-{$endDate}.csv";

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
        ];

        $callback = function () use ($type, $startDate, $endDate) {
            $file = fopen('php://output', 'w');

            switch ($type) {
                case 'bookings':
                    $this->exportBookings($file, $startDate, $endDate);
                    break;
                case 'packages':
                    $this->exportPackages($file, $startDate, $endDate);
                    break;
                case 'clients':
                    $this->exportClients($file, $startDate, $endDate);
                    break;
                case 'financial':
                default:
                    $this->exportFinancial($file, $startDate, $endDate);
                    break;
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // ReportController.php
    public function exportFinancial(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        $filename = "laporan-kas-{$startDate}-to-{$endDate}.csv";

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
        ];

        $callback = function () use ($startDate, $endDate) {
            $file = fopen('php://output', 'w');

            // Header
            fputcsv($file, [
                'Tanggal Verifikasi',
                'Jenis',
                'Kode Booking',
                'Klien',
                'Paket',
                'Jumlah',
                'Status Booking'
            ]);

            $start = Carbon::parse($startDate)->startOfDay();
            $end = Carbon::parse($endDate)->endOfDay();

            // DP Payments
            $dpPayments = Booking::with(['user', 'package'])
                ->whereNotNull('dp_verified_at')
                ->whereBetween('dp_verified_at', [$start, $end])
                ->get();

            foreach ($dpPayments as $booking) {
                fputcsv($file, [
                    $booking->dp_verified_at->format('Y-m-d H:i'),
                    'DP',
                    $booking->booking_code,
                    $booking->user->name,
                    $booking->package->name,
                    $booking->dp_amount,
                    $booking->status
                ]);
            }

            // Remaining Payments
            $remainingPayments = Booking::with(['user', 'package'])
                ->whereNotNull('remaining_verified_at')
                ->whereBetween('remaining_verified_at', [$start, $end])
                ->get();

            foreach ($remainingPayments as $booking) {
                fputcsv($file, [
                    $booking->remaining_verified_at->format('Y-m-d H:i'),
                    'Pelunasan',
                    $booking->booking_code,
                    $booking->user->name,
                    $booking->package->name,
                    $booking->total_amount - $booking->dp_amount,
                    $booking->status
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}