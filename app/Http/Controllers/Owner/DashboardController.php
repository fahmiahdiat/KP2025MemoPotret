<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use App\Models\Package;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Current month and year
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        
        // Monthly Revenue
        $monthlyRevenue = Booking::where('status', '!=', 'cancelled')
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->sum('total_amount') ?? 0;

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

        // Active Clients
        $activeClients = User::where('role', 'client')
            ->where('is_active', true)
            ->count();

        // Generate chart data for last 6 months
        $chartMonths = [];
        $chartRevenues = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $chartMonths[] = $date->translatedFormat('M');
            
            $revenue = Booking::where('status', '!=', 'cancelled')
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->sum('total_amount') ?? 0;
            $chartRevenues[] = $revenue;
        }

        return view('owner.dashboard', [
            'monthlyRevenue' => $monthlyRevenue,
            'totalBookings' => $totalBookings,
            'pendingBookings' => $pendingBookings,
            'completedBookings' => $completedBookings,
            'packageStats' => $packageStats,
            'recentBookings' => $recentBookings,
            'activeClients' => $activeClients,
            'chartMonths' => $chartMonths,
            'chartRevenues' => $chartRevenues
        ]);
    }
}