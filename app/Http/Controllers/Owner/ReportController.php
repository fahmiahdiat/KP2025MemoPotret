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
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        // Konversi ke Carbon untuk query yang benar
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        // Hitung statistik untuk dashboard
        $stats = [
            'monthly_revenue' => Booking::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
                ->where('status', '!=', 'cancelled')
                ->sum('total_amount'),

            'monthly_bookings' => Booking::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->count(),

            'new_clients' => User::where('role', 'client')
                ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
                ->count(),

            'sold_packages' => Package::has('bookings')->count(),
        ];

        // Financial Report dengan rentang tanggal yang benar
        $financialData = Booking::whereBetween('created_at', [$start, $end])
            ->where('status', '!=', 'cancelled')
            ->selectRaw('
                DATE(created_at) as date,
                COUNT(*) as total_bookings,
                SUM(total_amount) as total_revenue,
                SUM(dp_amount) as total_dp,
                SUM(remaining_amount) as total_remaining
            ')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Debug: cek query
        // dd([
        //     'start' => $start,
        //     'end' => $end,
        //     'count' => Booking::whereBetween('created_at', [$start, $end])->count(),
        //     'all_bookings' => Booking::select('id', 'created_at', 'status')->get()
        // ]);

        // Package Popularity
        $packagePopularity = DB::table('bookings')
            ->join('packages', 'bookings.package_id', '=', 'packages.id')
            ->whereBetween('bookings.created_at', [$start, $end])
            ->selectRaw('
                packages.name,
                COUNT(bookings.id) as booking_count,
                SUM(bookings.total_amount) as total_amount
            ')
            ->groupBy('packages.id', 'packages.name')
            ->orderBy('booking_count', 'desc')
            ->get();

        // Client Report
        $topClients = User::where('role', 'client')
            ->withCount([
                'bookings' => function ($query) use ($start, $end) {
                    $query->whereBetween('created_at', [$start, $end])
                        ->where('status', '!=', 'cancelled');
                }
            ])
            ->withSum([
                'bookings' => function ($query) use ($start, $end) {
                    $query->whereBetween('created_at', [$start, $end])
                        ->where('status', '!=', 'cancelled');
                }
            ], 'total_amount')
            ->orderBy('bookings_sum_total_amount', 'desc')
            ->take(10)
            ->get();

        return view('owner.reports.index', compact(
            'stats',
            'financialData',
            'packagePopularity',
            'topClients',
            'startDate',
            'endDate'
        ));
    }

    // Laporan Keuangan
    public function financial(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        // Konversi ke Carbon untuk query yang benar
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        // Debug: cek data booking
        // $allBookings = Booking::select('id', 'created_at', 'status', 'total_amount')->get();
        // dd($allBookings);

        // Financial Report
        $financialData = Booking::whereBetween('created_at', [$start, $end])
            ->where('status', '!=', 'cancelled')
            ->selectRaw('
                DATE(created_at) as date,
                COUNT(*) as total_bookings,
                SUM(total_amount) as total_revenue,
                SUM(dp_amount) as total_dp,
                SUM(remaining_amount) as total_remaining
            ')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Package Popularity
        $packagePopularity = DB::table('bookings')
            ->join('packages', 'bookings.package_id', '=', 'packages.id')
            ->whereBetween('bookings.created_at', [$start, $end])
            ->selectRaw('
                packages.name,
                COUNT(bookings.id) as booking_count,
                SUM(bookings.total_amount) as total_amount
            ')
            ->groupBy('packages.id', 'packages.name')
            ->orderBy('booking_count', 'desc')
            ->get();

        // Client Report
        $topClients = User::where('role', 'client')
            ->withCount([
                'bookings' => function ($query) use ($start, $end) {
                    $query->whereBetween('created_at', [$start, $end])
                        ->where('status', '!=', 'cancelled');
                }
            ])
            ->withSum([
                'bookings' => function ($query) use ($start, $end) {
                    $query->whereBetween('created_at', [$start, $end])
                        ->where('status', '!=', 'cancelled');
                }
            ], 'total_amount')
            ->orderBy('bookings_sum_total_amount', 'desc')
            ->take(10)
            ->get();

        return view('owner.reports.financial', compact(
            'financialData',
            'packagePopularity',
            'topClients',
            'startDate',
            'endDate'
        ));
    }

    // Laporan Booking
    public function bookings(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->subMonths(3)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        // Konversi ke Carbon untuk query yang benar
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        // Debug: cek total booking dalam rentang
        // $totalInRange = Booking::whereBetween('created_at', [$start, $end])->count();
        // $allBookings = Booking::select('id', 'created_at', 'status')->get();
        // dd([
        //     'start' => $start,
        //     'end' => $end,
        //     'total_in_range' => $totalInRange,
        //     'all_bookings' => $allBookings
        // ]);

        // Booking Statistics by Status
        $bookingStats = Booking::whereBetween('created_at', [$start, $end])
            ->selectRaw('
                status,
                COUNT(*) as count,
                SUM(total_amount) as total_amount,
                AVG(total_amount) as avg_amount
            ')
            ->groupBy('status')
            ->orderBy('count', 'desc')
            ->get();

        // Monthly Trend - Perbaikan untuk MySQL/MariaDB
        $monthlyTrend = Booking::whereBetween('created_at', [$start, $end])
            ->selectRaw('
                DATE_FORMAT(created_at, "%Y-%m") as month,
                COUNT(*) as booking_count,
                SUM(total_amount) as total_revenue,
                SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed_count
            ')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Conversion Rate
        $conversionData = [
            'total_bookings' => Booking::whereBetween('created_at', [$start, $end])->count(),
            'pending_bookings' => Booking::whereBetween('created_at', [$start, $end])->where('status', 'pending')->count(),
            'confirmed_bookings' => Booking::whereBetween('created_at', [$start, $end])->where('status', 'confirmed')->count(),
            'completed_bookings' => Booking::whereBetween('created_at', [$start, $end])->where('status', 'completed')->count(),
            'cancelled_bookings' => Booking::whereBetween('created_at', [$start, $end])->where('status', 'cancelled')->count(),
        ];

        // Average Time Analysis - PERBAIKAN DISINI
        $timeAnalysis = Booking::whereBetween('created_at', [$start, $end])
            ->whereNotNull('dp_verified_at')
            ->where('dp_verified_at', '>', 'created_at') // Tambahkan kondisi ini
            ->selectRaw('
            AVG(TIMESTAMPDIFF(HOUR, created_at, dp_verified_at)) as avg_hours_to_verify,
            AVG(TIMESTAMPDIFF(DAY, created_at, event_date)) as avg_days_before_event
        ')
            ->first();

        return view('owner.reports.bookings', compact(
            'bookingStats',
            'monthlyTrend',
            'conversionData',
            'timeAnalysis',
            'startDate',
            'endDate'
        ));
    }

    // Laporan Paket
    public function packages(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->subYear()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        // Konversi ke Carbon untuk query yang benar
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        // Package Performance
        $packagePerformance = Package::withCount([
            'bookings' => function ($query) use ($start, $end) {
                $query->whereBetween('created_at', [$start, $end])
                    ->where('status', '!=', 'cancelled');
            }
        ])
            ->withSum([
                'bookings' => function ($query) use ($start, $end) {
                    $query->whereBetween('created_at', [$start, $end])
                        ->where('status', '!=', 'cancelled');
                }
            ], 'total_amount')
            ->orderBy('bookings_sum_total_amount', 'desc')
            ->get();

        // Monthly Package Trends
        $monthlyPackageTrends = DB::table('bookings')
            ->join('packages', 'bookings.package_id', '=', 'packages.id')
            ->whereBetween('bookings.created_at', [$start, $end])
            ->where('bookings.status', '!=', 'cancelled')
            ->selectRaw('
                packages.name,
                DATE_FORMAT(bookings.created_at, "%Y-%m") as month,
                COUNT(bookings.id) as booking_count,
                SUM(bookings.total_amount) as total_amount
            ')
            ->groupBy('packages.id', 'packages.name', 'month')
            ->orderBy('month')
            ->orderBy('total_amount', 'desc')
            ->get();

        // Package Comparison
        $packageComparison = [
            'total_packages' => Package::count(),
            'active_packages' => Package::where('is_active', true)->count(),
            'packages_with_bookings' => Package::has('bookings')->count(),
            'avg_price' => Package::avg('price') ?? 0,
            'most_expensive' => Package::max('price') ?? 0,
            'cheapest' => Package::min('price') ?? 0,
        ];

        return view('owner.reports.packages', compact(
            'packagePerformance',
            'monthlyPackageTrends',
            'packageComparison',
            'startDate',
            'endDate'
        ));
    }

    // Laporan Klien
    public function clients(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->subYear()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        // Konversi ke Carbon untuk query yang benar
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        // Top Clients by Revenue
        $topClientsByRevenue = User::where('role', 'client')
            ->withCount([
                'bookings' => function ($query) use ($start, $end) {
                    $query->whereBetween('created_at', [$start, $end])
                        ->where('status', '!=', 'cancelled');
                }
            ])
            ->withSum([
                'bookings' => function ($query) use ($start, $end) {
                    $query->whereBetween('created_at', [$start, $end])
                        ->where('status', '!=', 'cancelled');
                }
            ], 'total_amount')
            ->orderBy('bookings_sum_total_amount', 'desc')
            ->take(20)
            ->get();

        // Client Loyalty Analysis
        $loyalClients = User::where('role', 'client')
            ->has('bookings', '>', 1)
            ->withCount('bookings')
            ->withSum('bookings', 'total_amount')
            ->orderBy('bookings_count', 'desc')
            ->take(15)
            ->get();

        // New vs Returning Clients
        $clientAnalysis = [
            'total_clients' => User::where('role', 'client')->count(),
            'clients_with_bookings' => User::where('role', 'client')->has('bookings')->count(),
            'new_clients' => User::where('role', 'client')
                ->whereBetween('created_at', [$start, $end])
                ->count(),
            'returning_clients' => User::where('role', 'client')
                ->has('bookings', '>', 1)
                ->whereHas('bookings', function ($query) use ($start, $end) {
                    $query->whereBetween('created_at', [$start, $end]);
                })
                ->count(),
        ];

        // Client Geographic Distribution (if location data exists)
        $clientLocations = Booking::whereBetween('created_at', [$start, $end])
            ->select('event_location')
            ->whereNotNull('event_location')
            ->groupBy('event_location')
            ->selectRaw('event_location, COUNT(*) as booking_count')
            ->orderBy('booking_count', 'desc')
            ->take(10)
            ->get();

        return view('owner.reports.clients', compact(
            'topClientsByRevenue',
            'loyalClients',
            'clientAnalysis',
            'clientLocations',
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

    private function exportFinancial($file, $startDate, $endDate)
    {
        fputcsv($file, ['Tanggal', 'Total Booking', 'Total Pendapatan', 'Total DP', 'Total Sisa Tagihan']);

        $data = Booking::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->selectRaw('
                DATE(created_at) as date,
                COUNT(*) as total_bookings,
                SUM(total_amount) as total_revenue,
                SUM(dp_amount) as total_dp,
                SUM(remaining_amount) as total_remaining
            ')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        foreach ($data as $row) {
            fputcsv($file, [
                $row->date,
                $row->total_bookings,
                $row->total_revenue,
                $row->total_dp,
                $row->total_remaining
            ]);
        }
    }
}