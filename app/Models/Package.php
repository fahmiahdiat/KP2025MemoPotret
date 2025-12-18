<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'duration_hours',
        'description',
        'thumbnail',
        'features',
        'is_active'
    ];

    protected $casts = [
        'features' => 'array', // ✅ INI PENTING!
        'is_active' => 'boolean',
        'price' => 'decimal:2'
    ];

  
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}