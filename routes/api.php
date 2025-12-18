<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Booking;
use Carbon\Carbon;

// Endpoint untuk mendapatkan tanggal yang sudah penuh (5/5)
Route::get('/booked-dates', function () {
    // PERBAIKAN: Hitung semua booking yang aktif (termasuk pending dengan DP upload)
    $fullDates = Booking::where('event_date', '>=', now()->format('Y-m-d'))
        ->select('event_date')
        ->selectRaw('COUNT(*) as booked_count')
        ->where(function ($query) {
            $query->whereIn('status', ['confirmed', 'in_progress', 'pending'])
                ->where(function ($q) {
                    // Termasuk yang sudah upload DP walau belum diverifikasi
                    $q->whereNotNull('payment_proof')
                        ->orWhereNotNull('dp_verified_at');
                });
        })
        ->where('status', '!=', 'cancelled') // Exclude cancelled
        ->groupBy('event_date')
        ->having('booked_count', '>=', 5)
        ->pluck('event_date')
        ->map(function ($date) {
            return Carbon::parse($date)->format('Y-m-d');
        })
        ->values()
        ->toArray();

    \Log::info('API /booked-dates returned:', $fullDates);

    return response()->json($fullDates);
});

// Endpoint untuk mengecek slot pada tanggal tertentu
Route::get('/date-slots/{date}', function ($date) {
    \Log::info("API date-slots called: {$date}");

    $maxSlots = 5;

    // PERBAIKAN: Query yang lebih efisien dan logika yang benar
    $bookedCount = Booking::whereDate('event_date', $date)
        ->where(function ($query) {
            $query->whereIn('status', ['confirmed', 'in_progress', 'pending'])
                ->where(function ($q) {
                    // Termasuk yang sudah upload DP atau sudah diverifikasi
                    $q->whereNotNull('payment_proof')
                        ->orWhereNotNull('dp_verified_at');
                });
        })
        ->where('status', '!=', 'cancelled')
        ->count();

    $availableSlots = max(0, $maxSlots - $bookedCount);
    $isFull = $bookedCount >= $maxSlots;

    \Log::info("Slots for {$date}: booked={$bookedCount}, available={$availableSlots}, full={$isFull}");

    return response()->json([
        'date' => $date,
        'booked_slots' => $bookedCount,
        'available_slots' => $availableSlots,
        'max_slots' => $maxSlots,
        'is_available' => !$isFull,
        'is_full' => $isFull,
        'debug_info' => [
            'query_date' => $date,
            'booked_count' => $bookedCount
        ]
    ]);
});

// Endpoint debugging (opsional, bisa dihapus setelah fix)
Route::get('/date-slots/{date}', function ($date) {
    $maxSlots = 5;

    // Hitung slot terpakai
    $bookedCount = Booking::whereDate('event_date', $date)
        ->where('status', '!=', 'cancelled')
        ->where(function ($query) {
            $query->whereIn('status', ['confirmed', 'in_progress', 'pending'])
                ->where(function ($q) {
                    $q->whereNotNull('payment_proof')
                        ->orWhereNotNull('dp_verified_at');
                });
        })
        ->count();

    $availableSlots = max(0, $maxSlots - $bookedCount);
    $isFull = $bookedCount >= $maxSlots;

    return response()->json([
        'date' => $date,
        'booked_slots' => $bookedCount,
        'available_slots' => $availableSlots,
        'max_slots' => $maxSlots,
        'is_available' => !$isFull, // True jika masih ada slot
        'is_full' => $isFull,
        'debug_info' => [
            'query_date' => $date,
            'booked_count' => $bookedCount
        ]
    ]);
});