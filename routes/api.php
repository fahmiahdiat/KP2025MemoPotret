<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Booking;

Route::get('/booked-dates', function () {
    $bookedDates = Booking::whereIn('status', ['confirmed', 'in_progress', 'pending'])
        ->where('event_date', '>=', now()->format('Y-m-d'))
        ->pluck('event_date')
        ->map(function($date) {
            return \Carbon\Carbon::parse($date)->format('Y-m-d');
        })
        ->unique()
        ->values()
        ->toArray();

    return response()->json($bookedDates);
});