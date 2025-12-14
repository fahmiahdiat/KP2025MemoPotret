<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $bookings = Booking::where('user_id', $user->id)
            ->with('package')
            ->latest()
            ->paginate(10);

        $stats = [
            'total' => Booking::where('user_id', $user->id)->count(),
            'pending' => Booking::where('user_id', $user->id)->where('status', 'pending')->count(),
            'confirmed' => Booking::where('user_id', $user->id)->where('status', 'confirmed')->count(),
            'completed' => Booking::where('user_id', $user->id)->where('status', 'completed')->count(),
        ];

        return view('client.dashboard', compact('bookings', 'stats'));
    }
}