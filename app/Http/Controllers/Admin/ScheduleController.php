<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->input('date', Carbon::today()->format('Y-m-d'));
        
        $bookings = Booking::where('event_date', $date)
            ->whereIn('status', ['confirmed', 'in_progress'])
            ->with(['user', 'package'])
            ->orderBy('event_time')
            ->get();

        // Get month events for calendar
        $monthEvents = Booking::whereMonth('event_date', Carbon::parse($date)->month)
            ->whereYear('event_date', Carbon::parse($date)->year)
            ->whereIn('status', ['confirmed', 'in_progress'])
            ->get(['event_date', 'status'])
            ->groupBy('event_date')
            ->map(function($item) {
                return [
                    'count' => $item->count(),
                    'has_pending' => $item->where('status', 'pending')->count() > 0
                ];
            });

        return view('admin.calendar', compact('bookings', 'date', 'monthEvents'));
    }

    public function getEvents(Request $request)
    {
        $start = $request->input('start');
        $end = $request->input('end');

        $bookings = Booking::whereBetween('event_date', [$start, $end])
            ->whereIn('status', ['confirmed', 'in_progress', 'pending'])
            ->with(['user', 'package'])
            ->get();

        $events = $bookings->map(function($booking) {
            $color = match($booking->status) {
                'confirmed' => '#0d6efd',
                'in_progress' => '#0dcaf0',
                'pending' => '#ffc107',
                default => '#6c757d'
            };

            return [
                'id' => $booking->id,
                'title' => $booking->user->name . ' - ' . $booking->package->name,
                'start' => $booking->event_date . 'T' . $booking->event_time,
                'end' => $booking->event_date . 'T' . Carbon::parse($booking->event_time)
                    ->addHours($booking->package->duration_hours)->format('H:i'),
                'color' => $color,
                'extendedProps' => [
                    'booking_code' => $booking->booking_code,
                    'status' => $booking->status,
                    'location' => $booking->event_location
                ]
            ];
        });

        return response()->json($events);
    }
}