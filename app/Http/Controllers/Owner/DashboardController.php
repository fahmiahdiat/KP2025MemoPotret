<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Package;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ========== TANGGAL RANGE ==========
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();
        $startOfWeek = $now->copy()->startOfWeek();
        $endOfWeek = $now->copy()->endOfWeek();
        
        // ========== 1. CASH FLOW BULAN INI ==========
        $dpCashIn = Booking::whereNotNull('dp_verified_at')
            ->whereBetween('dp_verified_at', [$startOfMonth, $endOfMonth])
            ->sum('dp_amount');

        $remainingCashIn = Booking::whereNotNull('remaining_verified_at')
            ->whereBetween('remaining_verified_at', [$startOfMonth, $endOfMonth])
            ->sum(DB::raw('total_amount - dp_amount'));

        $totalCashIn = $dpCashIn + $remainingCashIn;
        
        // ========== 2. BOOKING STATS ==========
        $newBookingsThisMonth = Booking::whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->where('status', '!=', 'cancelled')
            ->count();
            
        $bookingValueThisMonth = Booking::whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount');
            
        $newBookingsThisWeek = Booking::whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->where('status', '!=', 'cancelled')
            ->count();
        
        // ========== 3. PERLU TINDAKAN ==========
        $pendingVerification = Booking::where('status', 'pending')
            ->whereNotNull('payment_proof')
            ->count();
            
        $pendingPayments = Booking::where('status', 'pending_lunas')
            ->count();
            
        $requiresAction = $pendingVerification + $pendingPayments;
        
        // ========== 4. PIUTANG ==========
        $outstandingBookings = Booking::whereNotNull('dp_verified_at')
            ->where('remaining_amount', '>', 0)
            ->where('status', '!=', 'cancelled')
            ->get();
            
        $outstandingAmount = $outstandingBookings->sum('remaining_amount');
        $outstandingCount = $outstandingBookings->count();
        
        // ========== 5. STATISTIK TAMBAHAN ==========
        $totalBookings = Booking::where('status', '!=', 'cancelled')->count();
        $avgBookingValue = $totalBookings > 0 ? Booking::where('status', '!=', 'cancelled')->avg('total_amount') : 0;
        
        $activeBookings = Booking::whereIn('status', ['confirmed', 'in_progress', 'results_uploaded', 'pending_lunas'])->count();
        $completedBookings = Booking::where('status', 'completed')->count();
        $cancelledBookings = Booking::where('status', 'cancelled')->count();
        
        // ========== 6. KLIEN & GROWTH ==========
        $totalClients = User::where('role', 'client')->count();
        $newClientsThisMonth = User::where('role', 'client')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->count();
        
        // Client Growth Statistics
        $clientGrowth = $this->getClientGrowthStats();
            
        // ========== 7. ACARA MENDATANG ==========
        $upcomingEvents = Booking::with(['user', 'package'])
            ->where('event_date', '>=', $now->format('Y-m-d'))
            ->whereIn('status', ['confirmed', 'in_progress', 'results_uploaded', 'pending_lunas'])
            ->orderBy('event_date', 'asc')
            ->orderBy('event_time', 'asc')
            ->limit(5)
            ->get();
            
        // ========== 8. TOP PAKET ==========
        $topPackages = Package::withCount(['bookings' => function($query) {
                $query->where('status', '!=', 'cancelled');
            }])
            ->withSum(['bookings' => function($query) {
                $query->where('status', '!=', 'cancelled');
            }], 'total_amount')
            ->orderBy('bookings_sum_total_amount', 'desc')
            ->limit(3)
            ->get();
        
        // ========== 9. DATA UNTUK GRAFIK ==========
        $revenueChart = $this->getRevenueChartData();
        
        // ========== 10. BOOKING TERBARU ==========
        $recentBookings = Booking::with(['user', 'package'])
            ->where('status', '!=', 'cancelled')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        return view('owner.dashboard', compact(
            // Cash Flow
            'totalCashIn', 'dpCashIn', 'remainingCashIn',
            
            // Booking Stats
            'newBookingsThisMonth', 'bookingValueThisMonth', 'newBookingsThisWeek',
            'requiresAction', 'pendingVerification', 'pendingPayments',
            'outstandingAmount', 'outstandingCount',
            'totalBookings', 'avgBookingValue', 'activeBookings', 'completedBookings', 'cancelledBookings',
            
            // Client Stats
            'totalClients', 'newClientsThisMonth', 'clientGrowth',
            
            // Operational
            'upcomingEvents', 'topPackages',
            
            // Charts
            'revenueChart',
            
            // Tables
            'recentBookings'
        ));
    }
    
    private function getRevenueChartData()
    {
        $data = [];
        $labels = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $startOfMonth = $month->copy()->startOfMonth();
            $endOfMonth = $month->copy()->endOfMonth();
            
            $dpRevenue = Booking::whereNotNull('dp_verified_at')
                ->whereBetween('dp_verified_at', [$startOfMonth, $endOfMonth])
                ->sum('dp_amount');
            
            $remainingRevenue = Booking::whereNotNull('remaining_verified_at')
                ->whereBetween('remaining_verified_at', [$startOfMonth, $endOfMonth])
                ->sum(DB::raw('total_amount - dp_amount'));
            
            $totalRevenue = $dpRevenue + $remainingRevenue;
            
            $data[] = $totalRevenue;
            $labels[] = $month->translatedFormat('M Y');
        }
        
        return [
            'labels' => $labels,
            'data' => $data
        ];
    }
    
    private function getClientGrowthStats()
    {
        $now = Carbon::now();
        
        // Data untuk 6 bulan terakhir
        $clientData = [];
        $growthLabels = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $startOfMonth = $month->copy()->startOfMonth();
            $endOfMonth = $month->copy()->endOfMonth();
            
            $newClients = User::where('role', 'client')
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->count();
            
            $totalClientsUntilMonth = User::where('role', 'client')
                ->where('created_at', '<=', $endOfMonth)
                ->count();
            
            $clientData[] = [
                'month' => $month->translatedFormat('M Y'),
                'new_clients' => $newClients,
                'total_clients' => $totalClientsUntilMonth,
                'growth' => $this->calculateMonthOverMonthGrowth($month)
            ];
            
            $growthLabels[] = $month->translatedFormat('M Y');
        }
        
        // Hitung growth percentages
        $currentMonth = Carbon::now()->startOfMonth();
        $previousMonth = Carbon::now()->subMonth()->startOfMonth();
        
        $currentClients = User::where('role', 'client')
            ->whereBetween('created_at', [$currentMonth, $currentMonth->copy()->endOfMonth()])
            ->count();
            
        $previousClients = User::where('role', 'client')
            ->whereBetween('created_at', [$previousMonth, $previousMonth->copy()->endOfMonth()])
            ->count();
        
        $momGrowth = $previousClients > 0 
            ? round((($currentClients - $previousClients) / $previousClients) * 100, 1)
            : ($currentClients > 0 ? 100 : 0);
        
        return [
            'chart_labels' => $growthLabels,
            'chart_data' => array_column($clientData, 'new_clients'),
            'month_over_month' => $momGrowth,
            'total_growth_6m' => $this->calculate6MonthGrowth(),
            'avg_monthly_growth' => $this->calculateAvgMonthlyGrowth($clientData),
            'data' => $clientData
        ];
    }
    
    private function calculateMonthOverMonthGrowth($month)
    {
        $currentStart = $month->copy()->startOfMonth();
        $currentEnd = $month->copy()->endOfMonth();
        
        $previousStart = $month->copy()->subMonth()->startOfMonth();
        $previousEnd = $month->copy()->subMonth()->endOfMonth();
        
        $currentClients = User::where('role', 'client')
            ->whereBetween('created_at', [$currentStart, $currentEnd])
            ->count();
            
        $previousClients = User::where('role', 'client')
            ->whereBetween('created_at', [$previousStart, $previousEnd])
            ->count();
        
        if ($previousClients > 0) {
            return round((($currentClients - $previousClients) / $previousClients) * 100, 1);
        }
        
        return $currentClients > 0 ? 100 : 0;
    }
    
    private function calculate6MonthGrowth()
    {
        $sixMonthsAgo = Carbon::now()->subMonths(6)->startOfMonth();
        $now = Carbon::now()->endOfMonth();
        
        $clientsSixMonthsAgo = User::where('role', 'client')
            ->where('created_at', '<', $sixMonthsAgo)
            ->count();
            
        $clientsNow = User::where('role', 'client')
            ->where('created_at', '<=', $now)
            ->count();
        
        if ($clientsSixMonthsAgo > 0) {
            return round((($clientsNow - $clientsSixMonthsAgo) / $clientsSixMonthsAgo) * 100, 1);
        }
        
        return $clientsNow > 0 ? 100 : 0;
    }
    
    private function calculateAvgMonthlyGrowth($clientData)
    {
        $growthValues = [];
        
        for ($i = 1; $i < count($clientData); $i++) {
            if ($clientData[$i-1]['new_clients'] > 0) {
                $growth = (($clientData[$i]['new_clients'] - $clientData[$i-1]['new_clients']) / $clientData[$i-1]['new_clients']) * 100;
                $growthValues[] = $growth;
            }
        }
        
        return count($growthValues) > 0 ? round(array_sum($growthValues) / count($growthValues), 1) : 0;
    }
}