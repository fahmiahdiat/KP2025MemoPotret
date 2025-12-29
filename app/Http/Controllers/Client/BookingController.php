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

    public function createStep2(Request $request)
    {
        $validated = $request->validate([
            'package_id' => 'required|exists:packages,id',
            'event_date' => 'required|date|after_or_equal:today',
            'event_time' => 'required|date_format:H:i',
        ]);

        $bookedCount = Booking::where('event_date', $validated['event_date'])
            ->where(function ($query) {
                $query->whereIn('status', ['confirmed', 'in_progress', 'pending'])
                    ->where(function ($q) {
                        $q->whereNotNull('payment_proof')
                            ->orWhereNotNull('dp_verified_at');
                    });
            })
            ->where('status', '!=', 'cancelled')
            ->count();

        if ($bookedCount >= 5) {
            return redirect()->route('package.show', $validated['package_id'])
                ->with('error', '❌ Tanggal ini sudah penuh. Silakan pilih tanggal lain.')
                ->withInput();
        }

        $package = Package::findOrFail($validated['package_id']);

        // 🔑 PREVIEW BOOKING CODE
        $previewBookingCode = 'MEMO-' . strtoupper(Str::random(6));

        session()->put('booking_step1', [
            'package_id' => $validated['package_id'],
            'event_date' => $validated['event_date'],
            'event_time' => $validated['event_time'],
            'package_name' => $package->name,
            'package_price' => $package->price,
            'dp_amount' => $package->price * 0.5,
            'remaining_amount' => $package->price * 0.5,
            'available_slots' => 5 - $bookedCount,
            'booking_code' => $previewBookingCode,
        ]);

        return view('client.booking.step2', compact('package', 'previewBookingCode'));
    }

    public function storeStep2(Request $request)
    {
        $step1Data = session('booking_step1');

        if (!$step1Data) {
            return redirect()->route('client.dashboard')
                ->with('error', 'Sesi booking kadaluarsa. Silakan mulai kembali.');
        }

        $request->validate([
            'event_location' => 'required|string|max:500',
            'notes' => 'nullable|string|max:1000',
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'payment_notes' => 'nullable|string|max:500',

        ]);

        $bookedCount = Booking::where('event_date', $step1Data['event_date'])
            ->where(function ($query) {
                $query->whereIn('status', ['confirmed', 'in_progress', 'pending'])
                    ->where(function ($q) {
                        $q->whereNotNull('payment_proof')
                            ->orWhereNotNull('dp_verified_at');
                    });
            })
            ->where('status', '!=', 'cancelled')
            ->count();

        if ($bookedCount >= 5) {
            return redirect()->route('package.show', $step1Data['package_id'])
                ->with('error', 'Tanggal ini sudah terisi. Silakan pilih tanggal lain.');
        }

        $package = Package::findOrFail($step1Data['package_id']);

        $paymentProofPath = $request->file('payment_proof')
            ->store('payment-proofs', 'public');

        $booking = Booking::create([
            'booking_code' => $step1Data['booking_code'],
            'user_id' => auth()->id(),
            'package_id' => $step1Data['package_id'],
            'event_date' => $step1Data['event_date'],
            'event_time' => $step1Data['event_time'],
            'event_location' => $request->event_location,
            'notes' => $request->notes,
            'total_amount' => $package->price,
            'dp_amount' => $package->price * 0.5,
            'remaining_amount' => $package->price * 0.5,
            'status' => 'pending',
            'payment_proof' => $paymentProofPath,
            'payment_notes' => $request->payment_notes,
            'dp_uploaded_at' => now(),
        ]);

        session()->forget('booking_step1');

        return redirect()
            ->route('client.bookings.show', $booking)
            ->with('success', '✅ Booking berhasil dibuat! Admin akan verifikasi DP.');
    }


    public function show(Booking $booking)
    {
        // Authorization check
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        $booking->load('package');

        // Decode features jika string JSON
        if ($booking->package->features && is_string($booking->package->features)) {
            $booking->package->features = json_decode($booking->package->features, true) ?: [];
        }

        $daysBeforeEvent = $this->calculateDaysBeforeEvent($booking->event_date);
        $dpAmount = $booking->total_amount * 0.5;
        $canCancel = $daysBeforeEvent >= 30 && in_array($booking->status, ['pending', 'confirmed']);

        // DEFINISI TIMELINE STEPS
        $timelineSteps = [
            [
                'status_key' => 'pending',
                'title' => 'Booking Dibuat',
                'description' => 'Menunggu pembayaran DP 50%',
                'date' => $booking->created_at->format('d/m H:i'),
                'active_when' => ['pending', 'confirmed', 'in_progress', 'results_uploaded', 'pending_lunas', 'completed']
            ],
            [
                'status_key' => 'confirmed',
                'title' => 'DP Terverifikasi',
                'description' => 'Jadwal terkunci',
                'date' => $booking->dp_verified_at ? $booking->dp_verified_at->format('d/m H:i') : '-',
                'active_when' => ['confirmed', 'in_progress', 'results_uploaded', 'pending_lunas', 'completed']
            ],
            [
                'status_key' => 'in_progress',
                'title' => 'Acara Berlangsung',
                'description' => 'Sesi pemotretan',
                'date' => $booking->in_progress_at ? $booking->in_progress_at->format('d/m H:i') : '-',
                'active_when' => ['in_progress', 'results_uploaded', 'pending_lunas', 'completed']
            ],
            [
                'status_key' => 'results_uploaded',
                'title' => 'Hasil Diupload',
                'description' => 'Admin mengupload foto',
                'date' => $booking->results_uploaded_at ? $booking->results_uploaded_at->format('d/m H:i') : '-',
                'active_when' => ['results_uploaded', 'pending_lunas', 'completed']
            ],
            [
                'status_key' => 'pending_lunas', // STEP BARU: Pelunasan
                'title' => 'Pelunasan',
                'description' => 'Menunggu verifikasi lunas',
                'date' => $booking->remaining_uploaded_at ? $booking->remaining_uploaded_at->format('d/m H:i') : '-', // Gunakan remaining_uploaded_at
                'active_when' => ['pending_lunas', 'completed']
            ],
            [
                'status_key' => 'completed',
                'title' => 'Selesai',
                'description' => 'Siap didownload',
                'date' => $booking->completed_at ? $booking->completed_at->format('d/m H:i') : '-',
                'active_when' => ['completed']
            ]
        ];

        // LOGIKA PENENTUAN STATUS AKTIF (Bukan pakai index lagi, tapi in_array)
        // Kita kirim $timelineSteps yang sudah diproses status aktifnya ke view
        foreach ($timelineSteps as &$step) {
            $step['is_active'] = in_array($booking->status, $step['active_when']);

            // Khusus step "Pelunasan", jika status 'results_uploaded' tapi user sudah bayar lunas (completed), step ini juga harus aktif/terlewati
            if ($step['status_key'] == 'pending_lunas' && $booking->status == 'completed') {
                $step['is_active'] = true;
            }
        }

        return view('client.booking.show', compact(
            'booking',
            'daysBeforeEvent',
            'dpAmount',
            'canCancel',
            'timelineSteps'
        ));
    }


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
        return response()->json([
            'success' => false,
            'message' => 'Akses ditolak.'
        ], 403);
    }

    // Check if booking is already cancelled
    if ($booking->status === 'cancelled') {
        return response()->json([
            'success' => false,
            'message' => 'Booking sudah dibatalkan sebelumnya.'
        ], 400);
    }

    // Validation
    $request->validate([
        'cancel_reason' => 'required|string|max:255',
        'cancel_details' => 'required|string|max:1000',
    ]);

    // Check if booking can be cancelled (MAKSIMAL H-30 rule)
    $now = Carbon::now();
    $eventDate = Carbon::parse($booking->event_date);

    // Jika tanggal acara sudah lewat
    if ($eventDate->lt($now)) {
        return response()->json([
            'success' => false,
            'message' => 'Tidak bisa membatalkan acara yang sudah lewat.'
        ], 400);
    }

    // Hitung SELISIH HARI yang benar
    $daysBeforeEvent = $now->diffInDays($eventDate, false); // false = tidak absolute

    // TIDAK BISA cancel jika < 30 hari
    if ($daysBeforeEvent < 30) {
        return response()->json([
            'success' => false,
            'message' => '❌ Pembatalan hanya dapat dilakukan MAKSIMAL H-30 hari sebelum acara. ' .
                        'Acara Anda ' . $daysBeforeEvent . ' hari lagi.'
        ], 400);
    }

    // Update booking status - DP TIDAK DIKEMBALIKAN
    $booking->update([
        'status' => 'cancelled',
        'cancellation_reason' => $request->cancel_reason,
        'cancellation_details' => $request->cancel_details,
        'cancelled_at' => Carbon::now(),
        'admin_notes' => 'CLIENT_CANCEL: ' . $request->cancel_reason,
    ]);

    // TODO: Kirim notifikasi ke admin dan user

    return response()->json([
        'success' => true,
        'message' => 'Booking berhasil dibatalkan. DP 50% TIDAK DIKEMBALIKAN.',
        'redirect' => route('client.dashboard')
    ]);
}


}