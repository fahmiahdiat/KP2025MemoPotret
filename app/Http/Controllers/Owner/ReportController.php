<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        // Financial Report
        $financialData = Booking::whereBetween('created_at', [$startDate, $endDate])
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
        $packagePopularity = \DB::table('bookings')
            ->join('packages', 'bookings.package_id', '=', 'packages.id')
            ->whereBetween('bookings.created_at', [$startDate, $endDate])
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
                'bookings' => function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('created_at', [$startDate, $endDate])
                        ->where('status', '!=', 'cancelled');
                }
            ])
            ->withSum([
                'bookings' => function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('created_at', [$startDate, $endDate])
                        ->where('status', '!=', 'cancelled');
                }
            ], 'total_amount')
            ->orderBy('bookings_sum_total_amount', 'desc')
            ->take(10)
            ->get();

        return view('owner.reports.index', compact(
            'financialData',
            'packagePopularity',
            'topClients',
            'startDate',
            'endDate'
        ));
    }

    public function export(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        $bookings = Booking::with(['user', 'package'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->get();

        // Simple CSV Export
        $filename = "report-{$startDate}-to-{$endDate}.csv";
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
        ];

        $callback = function () use ($bookings) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Kode Booking', 'Client', 'Paket', 'Tanggal', 'Jumlah', 'Status']);

            foreach ($bookings as $booking) {
                fputcsv($file, [
                    $booking->booking_code,
                    $booking->user->name,
                    $booking->package->name,
                    $booking->event_date,
                    'Rp ' . number_format($booking->total_amount, 0, ',', '.'),
                    $booking->status
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}