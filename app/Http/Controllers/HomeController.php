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
            ->get();

        // ✅ FIX: Hitung tanggal yang BENAR-BENAR PENUH (>= 5 slot terisi)
        // TAMBAHKAN 30 hari ke depan untuk batasan kalender
        $startDate = now()->format('Y-m-d');
        $endDate = now()->addDays(90)->format('Y-m-d'); // 3 bulan ke depan

        $bookedDates = Booking::select('event_date')
            ->whereBetween('event_date', [$startDate, $endDate]) // Batasi range
            ->where('status', '!=', 'cancelled') // Abaikan yang cancel
            ->where(function ($query) {
                // Kriteria slot terisi: status valid DAN (ada bukti bayar ATAU sudah verified)
                $query->whereIn('status', ['confirmed', 'in_progress', 'pending', 'completed'])
                    ->where(function ($q) {
                    $q->whereNotNull('payment_proof')
                        ->orWhereNotNull('dp_verified_at');
                });
            })
            ->groupBy('event_date')
            ->havingRaw('COUNT(*) >= 5') // 👈 KUNCI: Hanya ambil jika jumlahnya >= 5
            ->pluck('event_date')
            ->map(function ($date) {
                return \Carbon\Carbon::parse($date)->format('Y-m-d');
            })
            ->toArray();

        return view('package-detail', compact('package', 'otherPackages', 'bookedDates'));
    }
}