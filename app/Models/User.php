<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'is_active', // ✅ TAMBAHKAN
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean', // ✅ TAMBAHKAN
    ];

    // Role Check Methods
    public function isClient(): bool
    {
        return $this->role === 'client' && $this->is_active;
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin' && $this->is_active;
    }

    public function isOwner(): bool
    {
        return $this->role === 'owner' && $this->is_active;
    }

    // Scope untuk user aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Relationships
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}