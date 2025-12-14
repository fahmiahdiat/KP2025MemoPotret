<?php

namespace App\Http\Controllers;
use App\Models\Booking; // ✅ TAMBAHKAN INI
use App\Models\Package;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $packages = Package::active()->latest()->take(3)->get();

        // FIX: Pastikan features selalu array
        $packages->each(function ($package) {
            if (is_string($package->features)) {
                $package->features = json_decode($package->features, true) ?? [];
            }
        });

        return view('home', compact('packages'));
    }

    public function packages()
    {
        $packages = Package::active()->get();

        // FIX: Pastikan features selalu array
        $packages->each(function ($package) {
            if (is_string($package->features)) {
                $package->features = json_decode($package->features, true) ?? [];
            }
        });

        return view('packages', compact('packages'));
    }

    public function showPackage(Package $package)
{
      // normalize features
    if (is_string($package->features)) {
        $package->features = json_decode($package->features, true) ?? [];
    }
    
    $otherPackages = Package::where('id', '!=', $package->id)
        ->where('is_active', true)
        ->take(3)
        ->get();

    // Get booked dates for this package
    $bookedDates = Booking::where('package_id', $package->id)
        ->whereIn('status', ['pending', 'confirmed', 'in_progress'])
        ->where('event_date', '>=', now())
        ->pluck('event_date')
        ->map(function($date) {
            return $date->format('Y-m-d');
        })
        ->unique()
        ->toArray();

    return view('package-detail', compact('package', 'otherPackages', 'bookedDates'));
}
}