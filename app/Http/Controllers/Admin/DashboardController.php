<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Today's Schedule
        $today = Carbon::today()->format('Y-m-d');
        $todayBookings = Booking::where('event_date', $today)
            ->whereIn('status', ['confirmed', 'in_progress'])
            ->with(['user', 'package'])
            ->orderBy('event_time')
            ->get();

        // Pending Actions
        $pendingBookings = Booking::where('status', 'pending')->count();
        $pendingPayments = Booking::where('status', 'confirmed')
            ->whereDate('created_at', '>=', Carbon::now()->subDays(3))
            ->count();

        // Recent Bookings
        $recentBookings = Booking::with(['user', 'package'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'todayBookings',
            'pendingBookings',
            'pendingPayments',
            'recentBookings'
        ));
    }
}