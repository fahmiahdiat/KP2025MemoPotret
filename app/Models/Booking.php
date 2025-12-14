<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_code',
        'user_id',
        'package_id',
        'event_date',
        'event_time',
        'event_location',
        'notes',
        'total_amount',
        'dp_amount',
        'remaining_amount',
        'status',
        'payment_proof',
        'payment_notes',
        'remaining_payment_proof',
        'remaining_payment_notes',
        'drive_link',
        'admin_notes',
        'completed_at',
        'cancellation_reason',
        'cancellation_details',
        'cancelled_at',
        'dp_uploaded_at',        // Waktu client upload DP
        'dp_verified_at',        // Waktu admin verifikasi DP
        'remaining_uploaded_at', // Waktu client upload pelunasan
        'remaining_verified_at', // Waktu admin verifikasi pelunasan
        'results_uploaded_at',   // Waktu admin upload hasil pertama
        'results_updated_at',    // Waktu admin edit/update link hasil
        'in_progress_at',
        'pending_lunas_at',
    ];

    protected $casts = [
        'event_date' => 'date',
        'total_amount' => 'decimal:2',
        'dp_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'dp_uploaded_at' => 'datetime',
        'dp_verified_at' => 'datetime',
        'remaining_uploaded_at' => 'datetime',
        'remaining_verified_at' => 'datetime',
        'results_uploaded_at' => 'datetime',
        'results_updated_at' => 'datetime',
        'in_progress_at' => 'datetime',
        'pending_lunas_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    // Accessor untuk cancelled_at
    public function getCancelledAtAttribute($value)
    {
        return $value ? Carbon::parse($value) : null;
    }

    // Accessor untuk completed_at
    public function getCompletedAtAttribute($value)
    {
        return $value ? Carbon::parse($value) : null;
    }

    // Status Methods
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'badge bg-warning',
            'confirmed' => 'badge bg-primary',
            'in_progress' => 'badge bg-info',
            'results_uploaded' => 'badge bg-purple',
            'completed' => 'badge bg-success',
            'cancelled' => 'badge bg-danger',
            'pending_lunas' => 'badge bg-orange'
        ];
        return '<span class="' . ($badges[$this->status] ?? 'badge bg-secondary') . '">'
            . ucfirst(str_replace('_', ' ', $this->status))
            . '</span>';
    }

    public function getTimelineAttribute()
    {
        $timeline = [
            [
                'status' => 'pending',
                'icon' => '⏳',
                'title' => 'Menunggu DP',
                'description' => 'Booking dibuat, menunggu pembayaran DP',
                'is_auto' => true,
                'date' => $this->created_at ? $this->created_at->format('d/m H:i') : null
            ],
            [
                'status' => 'confirmed',
                'icon' => '✅',
                'title' => 'DP Terverifikasi',
                'description' => 'Admin verifikasi DP secara manual',
                'is_auto' => false,
                'date' => $this->dp_verified_at ? $this->dp_verified_at->format('d/m H:i') : null
            ],
            [
                'status' => 'in_progress',
                'icon' => '🎬',
                'title' => 'Dalam Proses',
                'description' => 'Otomatis setelah DP terverifikasi',
                'is_auto' => true,
                'date' => $this->in_progress_at ? $this->in_progress_at->format('d/m H:i') : null
            ],
            [
                'status' => 'results_uploaded',
                'icon' => '📤',
                'title' => 'Hasil Diupload',
                'description' => 'Admin mengupload hasil foto',
                'is_auto' => false,
                'date' => $this->results_uploaded_at ? $this->results_uploaded_at->format('d/m H:i') : null
            ],
            [
                'status' => 'completed',
                'icon' => '✨',
                'title' => 'Selesai',
                'description' => 'Pelunasan diverifikasi, client bisa download',
                'is_auto' => false,
                'date' => $this->completed_at ? $this->completed_at->format('d/m H:i') : null
            ],
            [
                'status' => 'pending_lunas',
                'icon' => '💰',
                'title' => 'Menunggu Verifikasi Pelunasan',
                'description' => 'Client sudah upload bukti pelunasan, menunggu verifikasi admin',
                'is_auto' => false,
                'date' => $this->pending_lunas_at ? $this->pending_lunas_at->format('d/m H:i') : null
            ],
        ];

        // Add cancelled status if applicable
        if ($this->status == 'cancelled') {
            $timeline[] = [
                'status' => 'cancelled',
                'icon' => '❌',
                'title' => 'Dibatalkan',
                'description' => 'Booking dibatalkan',
                'is_auto' => false,
                'is_cancelled' => true,
                'date' => $this->cancelled_at ? $this->cancelled_at->format('d/m H:i') : null
            ];
        }

        return $timeline;
    }

    /**
     * Get current timeline step index
     */
    public function getCurrentTimelineIndexAttribute()
    {
        $timelineStatuses = ['pending', 'confirmed', 'in_progress', 'results_uploaded', 'completed', 'cancelled'];
        $currentIndex = array_search($this->status, $timelineStatuses);

        return $currentIndex !== false ? $currentIndex : -1;
    }

    // Helper method untuk cek apakah client bisa download
    public function canDownloadResults()
    {
        return $this->drive_link && $this->status === 'completed';
    }

    // Check who cancelled
    public function cancelledByAdmin()
    {
        return $this->cancellation_reason == 'admin_cancelled' ||
            $this->cancellation_reason == 'invalid_payment';
    }

    public function cancelledByClient()
    {
        return $this->cancellation_reason == 'client_cancelled';
    }

    // Format cancellation details
    public function getFormattedCancellationDetailsAttribute()
    {
        if (!$this->cancellation_details) {
            return null;
        }

        if ($this->cancelledByClient()) {
            return "Client: " . $this->cancellation_details;
        } else {
            return "Admin: " . $this->cancellation_details;
        }
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isConfirmed()
    {
        return $this->status === 'confirmed';
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    /**
     * Accessor untuk remaining_amount jika null
     */
    public function getRemainingAmountAttribute($value)
    {
        if ($value === null) {
            // Jika belum ada DP, sisa tagihan = total_amount
            if (!$this->dp_amount || $this->dp_amount == 0) {
                return $this->total_amount;
            }
            
            // Jika sudah ada DP, hitung sisa
            return max(0, $this->total_amount - $this->dp_amount);
        }
        return $value;
    }

    public function getPaymentStatusTextAttribute()
    {
        if ($this->remaining_amount == 0) return '✅ LUNAS';
        if ($this->remaining_amount > 0 && $this->remaining_payment_proof) return '⏳ Menunggu Verifikasi';
        
        $hariSetelahEvent = now()->diffInDays($this->event_date, false);
        
        if ($hariSetelahEvent < -7) return '⚠️ Telat bayar (lewat H+7)';
        if ($hariSetelahEvent < 0) return '🔥 Bayar sekarang';
        if ($hariSetelahEvent <= 7) return '🔥 Segera bayar';
        
        return 'Bayar H-7';
    }

    public function getPaymentStatusClassAttribute()
    {
        if ($this->remaining_amount == 0) return 'bg-green-100 text-green-800';
        if ($this->remaining_amount > 0 && $this->remaining_payment_proof) return 'bg-yellow-100 text-yellow-800';
        
        $hariSetelahEvent = now()->diffInDays($this->event_date, false);
        
        if ($hariSetelahEvent < -7) return 'bg-red-100 text-red-800';
        if ($hariSetelahEvent < 0) return 'bg-red-100 text-red-800';
        if ($hariSetelahEvent <= 7) return 'bg-red-100 text-red-800';
        
        return 'bg-blue-100 text-blue-800';
    }

    /**
     * Boot method untuk set nilai default
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($booking) {
        if ($booking->remaining_amount === null) {
            // ✅ Default: sisa tagihan = total_amount (karena belum DP)
            $booking->remaining_amount = $booking->total_amount;
        }

        // Generate booking code jika belum ada
        if (!$booking->booking_code) {
            $booking->booking_code = 'BK-' . strtoupper(uniqid());
        }
    });

    static::updating(function ($booking) {
        // Jika status completed, set remaining_amount ke 0
        if ($booking->status === 'completed' && $booking->remaining_amount > 0) {
            $booking->remaining_amount = 0;
        }
    });
    }
}