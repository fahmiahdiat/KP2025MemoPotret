<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['user', 'package'])
            ->latest()
            ->paginate(20);

        return view('admin.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['user', 'package']);
        return view('admin.bookings.show', compact('booking'));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,in_progress,completed,cancelled',
            'notes' => 'nullable|string'
        ]);

        $booking->update([
            'status' => $request->status,
            'admin_notes' => $request->notes ?: $booking->admin_notes
        ]);

        if ($request->status == 'completed') {
            $booking->update([
                'remaining_amount' => 0,
                'completed_at' => now()
            ]);
        }

        if ($request->status == 'cancelled') {
            $booking->update([
                'cancelled_at' => now()
            ]);
        }

        return back()->with('success', 'Status booking berhasil diperbarui.');
    }

    public function cancel(Request $request, Booking $booking)
    {
        try {
            if ($booking->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya booking dengan status pending yang bisa dibatalkan'
                ], 400);
            }

            $booking->update([
                'status' => 'cancelled',
                'cancellation_reason' => $request->input('cancel_reason', 'invalid_payment'),
                'cancellation_details' => $request->input('cancel_details', 'Dibatalkan oleh admin'),
                'cancelled_at' => now(),
                'admin_notes' => 'Dibatalkan oleh admin: ' . ($request->input('cancel_details', 'DP tidak valid'))
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Booking berhasil dibatalkan',
                'data' => [
                    'status' => $booking->status,
                    'booking_code' => $booking->booking_code
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Error cancelling booking: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal membatalkan booking: ' . $e->getMessage()
            ], 500);
        }
    }

    public function verifyPayment(Booking $booking)
    {
        try {
            if ($booking->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking sudah tidak dalam status pending'
                ], 400);
            }

            $dpAmount = $booking->total_amount * 0.5;

            // ✅ PERBAIKAN: Hitung sisa tagihan setelah DP diverifikasi
            $remainingAmount = $booking->total_amount - $dpAmount;

            $booking->update([
                'dp_amount' => $dpAmount,
                'remaining_amount' => $remainingAmount, // ✅ UPDATE SISA TAGIHAN
                'status' => 'in_progress',
                'dp_verified_at' => now(),
                'in_progress_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'DP berhasil diverifikasi. Booking dalam proses.',
                'data' => [
                    'status' => $booking->status,
                    'dp_amount' => $dpAmount,
                    'remaining_amount' => $remainingAmount // ✅ TAMBAHKAN DI RESPONSE
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error verifying payment: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal verifikasi DP: ' . $e->getMessage()
            ], 500);
        }
    }

    public function verifyFullPayment(Request $request, Booking $booking)
{
    try {
        if (!in_array($booking->status, ['results_uploaded', 'pending_lunas'])) {
            return response()->json([
                'success' => false,
                'message' => 'Status tidak sesuai'
            ], 400);
        }

        // ✅ PERBAIKAN: Set sisa tagihan ke 0
        $updateData = [
            'remaining_amount' => 0, // ✅ SET KE 0
            'status' => 'completed',
            'remaining_verified_at' => now(),
            'completed_at' => now()
        ];

        if (!$booking->pending_lunas_at && $booking->remaining_payment_proof) {
            $updateData['pending_lunas_at'] = now();
        }

        $booking->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Pelunasan diverifikasi. Status: SELESAI.',
            'data' => [
                'status' => $booking->status,
                'remaining_amount' => 0
            ]
        ]);

    } catch (\Exception $e) {
        \Log::error('Error verifying full payment: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Gagal verifikasi: ' . $e->getMessage()
        ], 500);
    }
}

    public function uploadResults(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'drive_link' => 'required|url|max:500',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            $updateData = [
                'drive_link' => $validated['drive_link'],
                'admin_notes' => $validated['notes'] ?? null,
            ];

            // CEK APAKAH UPLOAD PERTAMA ATAU EDIT
            $isFirstUpload = !$booking->drive_link;
            
            if ($isFirstUpload) {
                // UPLOAD PERTAMA
                $updateData['results_uploaded_at'] = now();
                
                // Tentukan status baru
                if ($booking->remaining_amount == 0) {
                    $updateData['status'] = 'completed';
                    $updateData['completed_at'] = now();
                } else if ($booking->remaining_payment_proof) {
                    $updateData['status'] = 'pending_lunas';
                } else {
                    $updateData['status'] = 'results_uploaded';
                }
            } else {
                // EDIT/UPDATE LINK
                $updateData['results_updated_at'] = now();
                // Status tetap tidak berubah
                // results_uploaded_at TETAP
                \Log::info('Edit link drive untuk booking #' . $booking->id . 
                          '. Upload pertama: ' . $booking->results_uploaded_at);
            }

            $booking->update($updateData);

            $message = $isFirstUpload ? 
                '✅ Hasil foto berhasil diupload!' : 
                '✅ Link hasil berhasil diupdate!';

            return redirect()->route('admin.bookings.show', $booking)
                ->with('success', $message);

        } catch (\Exception $e) {
            \Log::error('Error uploading results: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', '❌ Gagal: ' . $e->getMessage());
        }
    }

    // Method untuk menandai sebagai lunas (bisa dari pelunasan client)
    public function markAsPaid(Booking $booking)
    {
        try {
            if ($booking->remaining_amount <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada sisa pembayaran'
                ], 400);
            }

            $booking->update([
                'remaining_amount' => 0,
                'status' => 'completed',
                'remaining_verified_at' => now(), // Waktu verifikasi pelunasan
                'completed_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => '✅ Booking telah dilunasi! Client bisa download hasil.',
                'data' => $booking
            ]);

        } catch (\Exception $e) {
            \Log::error('Error marking as paid: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal: ' . $e->getMessage()
            ], 500);
        }
    }
}