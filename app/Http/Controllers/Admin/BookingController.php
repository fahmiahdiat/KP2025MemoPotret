<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'package'])
            ->when($request->filled('q'), function ($q) use ($request) {
                $search = $request->q;
                $q->where(function ($query) use ($search) {
                    $query->where('booking_code', 'like', "%{$search}%")
                        ->orWhereHas('package', function ($packageQuery) use ($search) {
                            $packageQuery->where('name', 'like', "%{$search}%");
                        })
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->when($request->filled('status'), function ($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->when($request->filled('start_date'), function ($q) use ($request) {
                $q->where('event_date', '>=', $request->start_date);
            })
            ->when($request->filled('end_date'), function ($q) use ($request) {
                $q->where('event_date', '<=', $request->end_date);
            })
            ->when($request->filled('slot_status'), function ($q) use ($request) {
                // Dapatkan semua tanggal event yang unik
                $eventDates = Booking::select('event_date')
                    ->where('status', '!=', 'cancelled')
                    ->where(function ($query) {
                    $query->whereNotNull('payment_proof')
                        ->orWhereNotNull('dp_verified_at');
                })
                    ->groupBy('event_date')
                    ->havingRaw('COUNT(*) >= ?', [$request->slot_status === 'almost_full' ? 4 : 5])
                    ->pluck('event_date')
                    ->toArray();

                if ($request->slot_status === 'available') {
                    // Tanggal dengan slot tersedia (kurang dari 5 booking)
                    $fullDates = Booking::select('event_date')
                        ->where('status', '!=', 'cancelled')
                        ->where(function ($query) {
                        $query->whereNotNull('payment_proof')
                            ->orWhereNotNull('dp_verified_at');
                    })
                        ->groupBy('event_date')
                        ->havingRaw('COUNT(*) >= 5')
                        ->pluck('event_date')
                        ->toArray();

                    $q->whereNotIn('event_date', $fullDates);
                } elseif ($request->slot_status === 'full') {
                    // Tanggal sudah penuh
                    $q->whereIn('event_date', $eventDates);
                } elseif ($request->slot_status === 'almost_full') {
                    // Tanggal hampir penuh (4 booking)
                    $q->whereIn('event_date', $eventDates);
                }
            });

        // Apply sorting
        switch ($request->get('sort', 'latest')) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'event_date_asc':
                $query->orderBy('event_date', 'asc')->orderBy('event_time', 'asc');
                break;
            case 'event_date_desc':
                $query->orderBy('event_date', 'desc')->orderBy('event_time', 'asc');
                break;
            case 'price_high':
                $query->orderBy('total_amount', 'desc');
                break;
            case 'price_low':
                $query->orderBy('total_amount', 'asc');
                break;
            case 'latest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $bookings = $query->paginate(15)->withQueryString();

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
                'admin_notes' => 'ADMIN_CANCEL: ' . ($request->input('cancel_details', 'DP tidak valid')), // ✅ ADMIN PREFIX
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

            // PERBAIKAN: Cek apakah tanggal masih ada slot
            $bookedCount = Booking::where('event_date', $booking->event_date)
                ->whereIn('status', ['confirmed', 'in_progress', 'pending'])
                ->whereNotNull('dp_verified_at')
                ->where('id', '!=', $booking->id) // Kecualikan booking ini
                ->count();

            if ($bookedCount >= 5) {
                return response()->json([
                    'success' => false,
                    'message' => '❌ Tidak bisa verifikasi DP. Tanggal ini sudah penuh (5/5).'
                ], 400);
            }

            $dpAmount = $booking->total_amount * 0.5;
            $remainingAmount = $booking->total_amount - $dpAmount;

            $booking->update([
                'dp_amount' => $dpAmount,
                'remaining_amount' => $remainingAmount,
                'status' => 'confirmed',
                'dp_verified_at' => now(),
            ]);

            $newBookedCount = $bookedCount + 1;
            $isFull = $newBookedCount >= 5;

            return response()->json([
                'success' => true,
                'message' => $isFull
                    ? '✅ DP diverifikasi. PERHATIAN: Tanggal ini sekarang sudah PENUH (5/5)!'
                    : '✅ DP diverifikasi. Slot tersisa: ' . (5 - $newBookedCount),
                'data' => [
                    'status' => $booking->status,
                    'dp_verified_at' => $booking->dp_verified_at,
                    'slot_info' => [
                        'booked' => $newBookedCount,
                        'available' => max(0, 5 - $newBookedCount),
                        'is_full' => $isFull
                    ]
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

            $updateData = [
                'remaining_amount' => 0,
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