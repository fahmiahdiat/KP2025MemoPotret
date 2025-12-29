<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Package;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Helper untuk mengalihkan role selain client ke dashboard masing-masing.
     */
    private function redirectNonClient()
    {
        if (auth()->check() && !auth()->user()->isClient()) {
            return match(auth()->user()->role) {
                'admin' => redirect()->route('admin.dashboard'),
                'owner' => redirect()->route('owner.dashboard'),
                default => redirect()->route('dashboard'),
            };
        }
        return null;
    }

    public function index()
    {
        // Jalankan proteksi: Jika admin/owner mampir sini, redirect.
        if ($redirect = $this->redirectNonClient()) return $redirect;

        $packages = Package::active()->latest()->take(3)->get();

        $packages->each(function ($package) {
            if (is_string($package->features)) {
                $package->features = json_decode($package->features, true) ?? [];
            }
        });

        return view('home', compact('packages'));
    }

    public function packages()
    {
        // Jalankan proteksi
        if ($redirect = $this->redirectNonClient()) return $redirect;

        $packages = Package::active()->get();

        $packages->each(function ($package) {
            if (is_string($package->features)) {
                $package->features = json_decode($package->features, true) ?? [];
            }
        });

        return view('packages', compact('packages'));
    }

    public function showPackage(Package $package)
    {
        // Jalankan proteksi
        if ($redirect = $this->redirectNonClient()) return $redirect;

        if (is_string($package->features)) {
            $package->features = json_decode($package->features, true) ?? [];
        }

        $otherPackages = Package::where('id', '!=', $package->id)
            ->where('is_active', true)
            ->get();

        $startDate = now()->format('Y-m-d');
        $endDate = now()->addDays(90)->format('Y-m-d');

        $bookedDates = Booking::select('event_date')
            ->whereBetween('event_date', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) {
                $query->whereIn('status', ['confirmed', 'in_progress', 'pending', 'completed'])
                    ->where(function ($q) {
                    $q->whereNotNull('payment_proof')
                        ->orWhereNotNull('dp_verified_at');
                });
            })
            ->groupBy('event_date')
            ->havingRaw('COUNT(*) >= 5')
            ->pluck('event_date')
            ->map(function ($date) {
                return \Carbon\Carbon::parse($date)->format('Y-m-d');
            })
            ->toArray();

        return view('package-detail', compact('package', 'otherPackages', 'bookedDates'));
    }
}