<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Package;
use App\Models\Booking;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Owner
        User::create([
            'name' => 'Owner Memo Potret',
            'email' => 'owner@memopotret.com',
            'phone' => '081234567890',
            'password' => Hash::make('password123'),
            'role' => 'owner',
            'is_active' => true,
        ]);

        // Create Admin
        User::create([
            'name' => 'Admin Memo',
            'email' => 'admin@memopotret.com',
            'phone' => '081234567891',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Create Sample Client
        $client = User::create([
            'name' => 'John Doe',
            'email' => 'client@example.com',
            'phone' => '081234567892',
            'password' => Hash::make('password123'),
            'role' => 'client',
            'is_active' => true,
        ]);

        $client = User::create([
            'name' => 'Fahmi Ahdiat',
            'email' => 'fahmi@fahmi.com',
            'phone' => '08972943198',
            'password' => Hash::make('password123'),
            'role' => 'client',
            'is_active' => true,
        ]);
        

        // Create Packages
        $bronze = Package::create([
            'name' => 'Paket Bronze',
            'price' => 3000000,
            'duration_hours' => 8,
            'description' => 'Paket dasar untuk kebutuhan fotografi sederhana',
            'features' => json_encode([
                '1 Fotografer profesional',
                '1 Asisten fotografer',
                '8 Jam kerja',
                'Soft file via Google Drive',
                'Editing dasar',
                'Album digital',
            ]),
            'is_active' => true,
        ]);

        $silver = Package::create([
            'name' => 'Paket Silver',
            'price' => 5000000,
            'duration_hours' => 10,
            'description' => 'Paket lengkap dengan video cinematic',
            'features' => json_encode([
                '1 Fotografer profesional',
                '1 Asisten fotografer',
                '10 Jam kerja',
                'Soft file via Google Drive',
                'Editing premium',
                'Album digital & cetak',
                '1 Video cinematic (1-2 menit)',
                'Dokumentasi video penuh',
            ]),
            'is_active' => true,
        ]);

        $gold = Package::create([
            'name' => 'Paket Gold',
            'price' => 8000000,
            'duration_hours' => 12,
            'description' => 'Paket premium untuk momen spesial',
            'features' => json_encode([
                '2 Fotografer profesional',
                '2 Asisten fotografer',
                '12 Jam kerja',
                'Soft file via Google Drive',
                'Editing premium+',
                'Album digital & cetak premium',
                '2 Video cinematic (2-3 menit)',
                'Dokumentasi video lengkap',
                'Drone footage (opsional)',
                'Cetak foto ukuran besar',
            ]),
            'is_active' => true,
        ]);

        // Create Sample Booking
        Booking::create([
            'booking_code' => 'MEMO-001',
            'user_id' => $client->id,
            'package_id' => $silver->id,
            'event_date' => Carbon::now()->addDays(30),
            'event_time' => '09:00:00',
            'event_location' => 'Hotel Grand, Jakarta',
            'status' => 'confirmed',
            'total_amount' => $silver->price,
            'dp_amount' => $silver->price * 0.5,
            'remaining_amount' => $silver->price * 0.5,
        ]);

        // Create more sample clients
        User::factory(10)->create(['role' => 'client']);
    }
}