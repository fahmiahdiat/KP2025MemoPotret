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
        
        // Default tanggal hari ini jika tidak ada input
        $date = $request->input('date', Carbon::today()->format('Y-m-d'));
        $statusFilter = $request->input('status', '');

        // 1. Ambil booking detail untuk LIST di sebelah kanan
        $bookings = Booking::whereDate('event_date', $date)
            ->where('status', '!=', 'cancelled')
            ->when($statusFilter, function ($query) use ($statusFilter) {
                return $query->where('status', $statusFilter);
            })
            ->with(['user', 'package'])
            ->orderBy('event_time')
            ->get();

        // 2. Hitung slot tersisa untuk tanggal ini
        $slotCount = Booking::whereDate('event_date', $date)
            ->where('status', '!=', 'cancelled')
            ->where(function ($q) {
                $q->whereNotNull('payment_proof')
                    ->orWhereNotNull('dp_verified_at');
            })
            ->count();

        $slotsLeft = 5 - $slotCount;
        $isDateFull = $slotsLeft <= 0;
        $isAlmostFull = $slotsLeft <= 1 && $slotsLeft > 0;

        // 3. Ambil data ringkasan untuk KALENDER
        $startOfMonth = Carbon::parse($date)->startOfMonth();
        $endOfMonth = Carbon::parse($date)->endOfMonth();

        $monthEvents = Booking::whereBetween('event_date', [$startOfMonth, $endOfMonth])
            ->where('status', '!=', 'cancelled')
            ->get(['event_date', 'status'])
            ->groupBy(function ($item) {
                return $item->event_date->format('Y-m-d');
            })
            ->map(function ($items) {
                return [
                    'count' => $items->count(),
                    'has_pending' => $items->contains('status', 'pending'),
                    'has_confirmed' => $items->contains('status', 'confirmed'),
                    'has_progress' => $items->contains('status', 'in_progress'),
                    'has_completed' => $items->contains('status', 'completed'),
                ];
            });

        $whatsappData = $bookings->map(function ($booking) {
            return [
                'time' => date('H:i', strtotime($booking->event_time)),
                'end_time' => $booking->package->duration_hours
                    ? date('H:i', strtotime($booking->event_time . ' + ' . $booking->package->duration_hours . ' hours'))
                    : null,
                'name' => $booking->user->name,
                'package' => $booking->package->name,
                'status' => $booking->status,
                'phone' => $booking->user->phone,
                'location' => $booking->event_location,
                'booking_code' => $booking->booking_code,
                'status_label' => match ($booking->status) {
                    'pending' => '⏳ Pending (Menunggu DP)',
                    'confirmed' => '✅ Confirmed (DP Terbayar)',
                    'in_progress' => '📸 Sedang Proses',
                    'completed' => '🎉 Selesai',
                    default => $booking->status,
                }
            ];
        });

        return view('admin.calendar', compact(
            'bookings',
            'date',
            'monthEvents',
            'statusFilter',
            'slotsLeft',
            'isDateFull',
            'isAlmostFull',
            'whatsappData' // TAMBAH INI
        ));
    }

    public function getEvents(Request $request)
    {
        $start = $request->input('start');
        $end = $request->input('end');

        $bookings = Booking::whereBetween('event_date', [$start, $end])
            ->where('status', '!=', 'cancelled')
            ->with(['user', 'package'])
            ->get();

        $events = $bookings->map(function ($booking) {
            // Warna event berdasarkan status
            $color = match ($booking->status) {
                'confirmed' => '#0d6efd', // Biru
                'in_progress' => '#0dcaf0', // Cyan
                'pending' => '#ffc107', // Kuning
                'completed' => '#198754', // Hijau
                default => '#6c757d' // Abu
            };

            // Tambahkan end time (asumsi durasi paket dalam jam)
            $startDateTime = Carbon::parse($booking->event_date->format('Y-m-d') . ' ' . $booking->event_time);
            $endDateTime = $startDateTime->copy()->addHours($booking->package->duration_hours ?? 1);

            return [
                'id' => $booking->id,
                'title' => $booking->user->name . ' (' . $booking->package->name . ')',
                'start' => $startDateTime->toIso8601String(),
                'end' => $endDateTime->toIso8601String(),
                'backgroundColor' => $color,
                'borderColor' => $color,
                'extendedProps' => [
                    'booking_code' => $booking->booking_code,
                    'status' => ucfirst($booking->status),
                    'location' => $booking->event_location
                ]
            ];
        });

        return response()->json($events);
    }
}