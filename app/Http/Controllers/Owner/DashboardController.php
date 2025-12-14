<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use App\Models\Package;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Monthly Revenue
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $monthlyRevenue = Booking::where('status', '!=', 'cancelled')
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->sum('total_amount');

        // Booking Statistics
        $totalBookings = Booking::count();
        $pendingBookings = Booking::where('status', 'pending')->count();
        $completedBookings = Booking::where('status', 'completed')->count();

        // Package Performance
        $packageStats = Package::withCount([
            'bookings' => function ($query) {
                $query->where('status', '!=', 'cancelled');
            }
        ])
            ->orderBy('bookings_count', 'desc')
            ->take(5)
            ->get();

        // Recent Bookings
        $recentBookings = Booking::with(['user', 'package'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // FIX: User Growth Chart Data
        $userGrowth = User::where('role', 'client')
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as count')
            )
            ->whereYear('created_at', $currentYear)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(function ($item) {
                return [
                    'month' => Carbon::create()->month($item->month)->format('M'), // Jan, Feb, Mar
                    'count' => $item->count
                ];
            });

        // Jika data kosong, buat default
        if ($userGrowth->isEmpty()) {
            $userGrowth = collect([
                ['month' => 'Jan', 'count' => 0],
                ['month' => 'Feb', 'count' => 0],
                ['month' => 'Mar', 'count' => 0],
                ['month' => 'Apr', 'count' => 0],
                ['month' => 'May', 'count' => 0],
                ['month' => 'Jun', 'count' => 0],
            ]);
        }

        return view('owner.dashboard', compact(
            'monthlyRevenue',
            'totalBookings',
            'pendingBookings',
            'completedBookings',
            'packageStats',
            'recentBookings',
            'userGrowth'
        ));

        // Revenue Last 6 Months
        $revenueLast6Months = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $revenue = Booking::where('status', '!=', 'cancelled')
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->sum('total_amount');

            $revenueLast6Months[] = [
                'month' => $date->translatedFormat('M'),
                'revenue' => $revenue
            ];
        }

        return view('owner.dashboard', compact(
            'monthlyRevenue',
            'totalBookings',
            'pendingBookings',
            'completedBookings',
            'packageStats',
            'recentBookings',
            'userGrowth',
            'revenueLast6Months' // ✅ TAMBAHKAN
        ));
    }


}