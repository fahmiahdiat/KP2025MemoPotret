<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function create()
    {
        $packages = Package::where('is_active', true)->get();

        // Get booked dates for calendar
        $bookedDates = Booking::whereIn('status', ['confirmed', 'in_progress', 'pending'])
            ->where('event_date', '>=', Carbon::today())
            ->pluck('event_date')
            ->map(function ($date) {
                return Carbon::parse($date)->format('Y-m-d');
            })
            ->unique()
            ->toArray();

        return view('client.booking.create', compact('packages', 'bookedDates'));
    }

    public function store(Request $request)
{
    $request->validate([
        'package_id' => 'required|exists:packages,id',
        'event_date' => 'required|date|after_or_equal:today',
        'event_time' => 'required|date_format:H:i',
        'event_location' => 'required|string|max:500',
        'notes' => 'nullable|string|max:1000',
    ]);

    // Check if date is available
    $isDateBooked = Booking::where('event_date', $request->event_date)
        ->whereIn('status', ['confirmed', 'in_progress', 'pending'])
        ->exists();

    if ($isDateBooked) {
        return back()->withErrors(['event_date' => 'Tanggal ini sudah dipesan. Silakan pilih tanggal lain.']);
    }

    $package = Package::findOrFail($request->package_id);

    // PERBAIKAN DISINI: Set remaining_amount = total_amount (karena belum ada DP)
    $booking = Booking::create([
        'booking_code' => 'MEMO-' . Str::random(6),
        'user_id' => auth()->id(),
        'package_id' => $package->id,
        'event_date' => $request->event_date,
        'event_time' => $request->event_time,
        'event_location' => $request->event_location,
        'notes' => $request->notes,
        'total_amount' => $package->price,
        'remaining_amount' => $package->price, // ✅ SET SISA = TOTAL (karena belum DP)
        'status' => 'pending',
    ]);

    return redirect()->route('client.bookings.show', $booking)
        ->with('success', 'Booking berhasil dibuat! Silakan lakukan pembayaran DP 50%.');
}

    public function show(Booking $booking)
    {
        // Ensure user can only see their own booking
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        $booking->load('package');

        // Pastikan features adalah array
        if ($booking->package->features && is_string($booking->package->features)) {
            $booking->package->features = json_decode($booking->package->features, true) ?: [];
        }

        // HITUNG HARI DI CONTROLLER (bukan di Blade)
        $daysBeforeEvent = $this->calculateDaysBeforeEvent($booking->event_date);

        // Hitung DP amount
        $dpAmount = $booking->total_amount * 0.5;

        // Tentukan apakah bisa cancel
        $canCancel = $daysBeforeEvent >= 30 && $booking->status == 'pending';

              $timelineSteps = [
        [
            'status' => 'pending',
            'icon' => '📝',
            'title' => 'Booking Dibuat',
            'description' => 'Menunggu pembayaran DP 50%',
            'date' => $booking->created_at->format('d/m H:i'),
            'status_key' => 'pending'
        ],
        [
            'status' => 'confirmed',
            'icon' => '✅',
            'title' => 'DP Terverifikasi',
            'description' => 'Booking dikonfirmasi, jadwal terkunci',
            'date' => $booking->dp_verified_at ? $booking->dp_verified_at->format('d/m H:i') : '-',
            'status_key' => 'confirmed'
        ],
        [
            'status' => 'in_progress',
            'icon' => '🎬',
            'title' => 'Acara Berlangsung',
            'description' => 'Tim kami melakukan pemotretan',
            'date' => $booking->in_progress_at ? $booking->in_progress_at->format('d/m H:i') : '-',
            'status_key' => 'in_progress'
        ],
        [
            'status' => 'results_uploaded',
            'icon' => '📤',
            'title' => 'Hasil Diupload',
            'description' => 'Admin sudah mengupload hasil foto',
            'date' => $booking->results_uploaded_at ? $booking->results_uploaded_at->format('d/m H:i') : '-',
            'status_key' => 'results_uploaded'
        ],
        [
            'status' => 'pending_lunas',
            'icon' => '💰',
            'title' => 'Menunggu Verifikasi Pelunasan',
            'description' => 'Admin verifikasi pembayaran lunas',
            'date' => $booking->pending_lunas_at ? $booking->pending_lunas_at->format('d/m H:i') : '-',
            'status_key' => 'pending_lunas'
        ],
        [
            'status' => 'completed',
            'icon' => '✨',
            'title' => 'Selesai',
            'description' => 'Pelunasan diverifikasi, hasil bisa didownload',
            'date' => $booking->completed_at ? $booking->completed_at->format('d/m H:i') : '-',
            'status_key' => 'completed'
        ]
    ];

    // Hitung status index untuk timeline
    $statusOrder = ['pending', 'confirmed', 'in_progress', 'results_uploaded', 'completed', 'cancelled'];
    $currentStatusIndex = array_search($booking->status, $statusOrder);

        return view('client.booking.show', compact(
        'booking',
        'daysBeforeEvent',
        'dpAmount',
        'canCancel',
        'timelineSteps',
        'currentStatusIndex'
    ));
    }

    /**
     * Helper method untuk menghitung hari sebelum acara
     */
    private function calculateDaysBeforeEvent($eventDate)
    {
        $event = \Carbon\Carbon::parse($eventDate)->startOfDay();
        $today = \Carbon\Carbon::today()->startOfDay();

        return $today->diffInDays($event, false); // false = bisa negatif
    }

    public function uploadPayment(Request $request, Booking $booking)
{
    // Pastikan booking milik user yang login
    if ($booking->user_id !== auth()->id()) {
        return back()->with('error', 'Akses ditolak.');
    }

    $request->validate([
        'payment_proof' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        'payment_notes' => 'nullable|string|max:500',
    ]);

    try {
        if ($request->hasFile('payment_proof')) {
            $path = $request->file('payment_proof')->store('payment-proofs', 'public');
            
            $booking->update([
                'payment_proof' => $path,
                'payment_notes' => $request->payment_notes,
                'dp_uploaded_at' => now(), // ✅ TAMBAH INI
                'status' => 'pending',
            ]);

            return back()->with('success', '✅ Bukti DP berhasil diupload! Admin akan verifikasi.');
        }
    } catch (\Exception $e) {
        return back()->with('error', '❌ Gagal mengupload bukti: ' . $e->getMessage());
    }

    return back()->with('error', '❌ Gagal mengupload bukti.');
}

public function uploadRemainingPayment(Request $request, Booking $booking)
{
    // Authorization check
    if ($booking->user_id !== auth()->id()) {
        abort(403);
    }
  
    // Allow untuk booking yang sudah ada hasilnya atau masih confirmed
    $allowedStatuses = ['confirmed', 'results_uploaded', 'in_progress'];
    if (!in_array($booking->status, $allowedStatuses) || $booking->remaining_amount <= 0) {
        return back()->with('error', 'Tidak dapat mengupload bukti pelunasan.');
    }

    // Validation
    $request->validate([
        'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        'remaining_payment_notes' => 'nullable|string|max:500',
    ]);

    try {
        // Store payment proof
        $path = $request->file('payment_proof')->store('remaining-payment-proofs', 'public');

        // Update booking - PERBAIKAN DISINI
        $booking->update([
            'remaining_payment_proof' => $path,
            'remaining_payment_notes' => $request->remaining_payment_notes,
            'remaining_uploaded_at' => now(), // ✅ GANTI 'remaining_payment_uploaded_at' MENJADI 'remaining_uploaded_at'
            'status' => 'pending_lunas',
        ]);

        // Log untuk debugging
        \Log::info('Remaining payment uploaded', [
            'booking_id' => $booking->id,
            'notes' => $request->remaining_payment_notes,
            'file_path' => $path,
            'status' => 'pending_lunas'
        ]);

        return back()->with('success', '✅ Bukti pelunasan berhasil diupload. Menunggu verifikasi admin.');

    } catch (\Exception $e) {
        \Log::error('Error uploading remaining payment: ' . $e->getMessage());
        return back()->with('error', '❌ Gagal mengupload bukti pelunasan: ' . $e->getMessage());
    }
}

    public function cancel(Request $request, Booking $booking)
    {
        // Authorization check
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        // Validation
        $request->validate([
            'cancel_reason' => 'required|string|max:255',
            'cancel_details' => 'required|string|max:1000',
        ]);

        // Check if booking is already cancelled
        if ($booking->status === 'cancelled') {
            return back()->with('error', 'Booking sudah dibatalkan sebelumnya.');
        }

        // Check if booking can be cancelled (MAKSIMAL H-30 rule)
        // PERBAIKAN: Hitung hari dengan benar, pastikan acara MASIH DI MASA DEPAN
        $now = Carbon::now();
        $eventDate = Carbon::parse($booking->event_date);

        // Jika tanggal acara sudah lewat
        if ($eventDate->lt($now)) {
            return back()->with('error', 'Tidak bisa membatalkan acara yang sudah lewat.');
        }

        // Hitung SELISIH HARI yang benar
        $daysBeforeEvent = $now->diffInDays($eventDate, false); // false = tidak absolute

        // DEBUG LOG
        \Log::info('Cancel Booking Debug', [
            'booking_id' => $booking->id,
            'event_date' => $eventDate->format('Y-m-d'),
            'now' => $now->format('Y-m-d'),
            'daysBeforeEvent' => $daysBeforeEvent,
            'status' => $booking->status
        ]);

        // TIDAK BISA cancel jika < 30 hari
        // PERBAIKAN: $daysBeforeEvent harus >= 30 (artinya masih 30 hari atau lebih sebelum acara)
        if ($daysBeforeEvent < 30) {
            return back()->with(
                'error',
                '❌ Pembatalan hanya dapat dilakukan MAKSIMAL H-30 hari sebelum acara. ' .
                'Acara Anda ' . $daysBeforeEvent . ' hari lagi.'
            );
        }

        // Update booking status - DP TIDAK DIKEMBALIKAN
        $booking->update([
            'status' => 'cancelled',
            'cancellation_reason' => $request->cancel_reason,
            'cancellation_details' => $request->cancel_details,
            'cancelled_at' => Carbon::now(),
            'admin_notes' => 'Dibatalkan oleh client (DP hangus): ' . $request->cancel_reason
        ]);

        // TODO: Kirim notifikasi ke admin dan user

        return redirect()->route('client.dashboard')
            ->with('warning', 'Booking berhasil dibatalkan. DP 50% TIDAK DIKEMBALIKAN.');
    }
}