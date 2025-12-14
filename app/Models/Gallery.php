<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'image_path',
        'thumbnail_path',
        'is_selected',
        'notes'
    ];

    protected $casts = [
        'is_selected' => 'boolean'
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}