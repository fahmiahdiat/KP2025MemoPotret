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
        'features',
        'is_active'
    ];

    protected $casts = [
        'features' => 'array', // ✅ INI PENTING!
        'is_active' => 'boolean',
        'price' => 'decimal:2'
    ];

    // Accessor untuk memastikan features selalu array
    public function getFeaturesAttribute($value)
    {
        if (is_string($value)) {
            return json_decode($value, true) ?? [];
        }
        
        return $value ?? [];
    }

    // Mutator untuk menyimpan sebagai JSON
    public function setFeaturesAttribute($value)
    {
        $this->attributes['features'] = json_encode($value);
    }

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