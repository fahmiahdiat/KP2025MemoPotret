<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Package;
use App\Models\Booking;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class ReportSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus data booking lama jika ada
        // Booking::truncate(); // Hati-hati dengan truncate, gunakan delete() saja
        
        // Atau lebih aman: Hapus booking yang dibuat oleh seeder sebelumnya
        // Booking::where('booking_code', 'like', 'MEMO-%')->delete();

        // Buat data untuk testing laporan

        // 1. Buat beberapa client (jika belum ada)
        $client1 = User::firstOrCreate(
            ['email' => 'client1@example.com'],
            [
                'name' => 'Ahmad Fauzi',
                'phone' => '081234567801',
                'password' => Hash::make('password123'),
                'role' => 'client',
                'is_active' => true,
                'created_at' => Carbon::now()->subMonths(6),
            ]
        );

        $client2 = User::firstOrCreate(
            ['email' => 'client2@example.com'],
            [
                'name' => 'Siti Nurhaliza',
                'phone' => '081234567802',
                'password' => Hash::make('password123'),
                'role' => 'client',
                'is_active' => true,
                'created_at' => Carbon::now()->subMonths(3),
            ]
        );

        $client3 = User::firstOrCreate(
            ['email' => 'client3@example.com'],
            [
                'name' => 'Budi Santoso',
                'phone' => '081234567803',
                'password' => Hash::make('password123'),
                'role' => 'client',
                'is_active' => true,
                'created_at' => Carbon::now()->subMonths(1),
            ]
        );

        $client4 = User::firstOrCreate(
            ['email' => 'client4@example.com'],
            [
                'name' => 'Maya Sari',
                'phone' => '081234567804',
                'password' => Hash::make('password123'),
                'role' => 'client',
                'is_active' => true,
                'created_at' => Carbon::now()->subDays(15),
            ]
        );

        // 2. Ambil atau buat package yang sudah ada
        $bronze = Package::firstOrCreate(
            ['name' => 'Paket Bronze'],
            [
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
            ]
        );

        $silver = Package::firstOrCreate(
            ['name' => 'Paket Silver'],
            [
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
            ]
        );

        $gold = Package::firstOrCreate(
            ['name' => 'Paket Gold'],
            [
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
            ]
        );

        $this->command->info('Paket ditemukan/dibuat:');
        $this->command->info('- ' . $bronze->name . ' (ID: ' . $bronze->id . ')');
        $this->command->info('- ' . $silver->name . ' (ID: ' . $silver->id . ')');
        $this->command->info('- ' . $gold->name . ' (ID: ' . $gold->id . ')');

        // 3. Buat booking untuk berbagai status dan bulan
        
        // === BULAN SEKARANG ===
        
        // Booking bulan ini - Completed
        Booking::create([
            'booking_code' => 'MEMO-' . strtoupper(uniqid()),
            'user_id' => $client1->id,
            'package_id' => $silver->id,
            'event_date' => Carbon::now()->subDays(5),
            'event_time' => '10:00:00',
            'event_location' => 'Hotel Majapahit, Surabaya',
            'notes' => 'Wedding photography',
            'total_amount' => $silver->price,
            'dp_amount' => $silver->price * 0.5,
            'remaining_amount' => 0,
            'status' => 'completed',
            'payment_proof' => 'payment-proofs/sample.jpg',
            'dp_uploaded_at' => Carbon::now()->subDays(30),
            'dp_verified_at' => Carbon::now()->subDays(29),
            'remaining_payment_proof' => 'remaining-proofs/sample.jpg',
            'remaining_uploaded_at' => Carbon::now()->subDays(10),
            'remaining_verified_at' => Carbon::now()->subDays(9),
            'results_uploaded_at' => Carbon::now()->subDays(8),
            'drive_link' => 'https://drive.google.com/drive/folders/123',
            'completed_at' => Carbon::now()->subDays(7),
            'created_at' => Carbon::now()->startOfMonth()->addDays(1),
        ]);

        // Booking bulan ini - Confirmed (DP terbayar)
        Booking::create([
            'booking_code' => 'MEMO-' . strtoupper(uniqid()),
            'user_id' => $client2->id,
            'package_id' => $gold->id,
            'event_date' => Carbon::now()->addDays(15),
            'event_time' => '14:00:00',
            'event_location' => 'Gedung Serba Guna, Jakarta',
            'notes' => 'Corporate event',
            'total_amount' => $gold->price,
            'dp_amount' => $gold->price * 0.5,
            'remaining_amount' => $gold->price * 0.5,
            'status' => 'confirmed',
            'payment_proof' => 'payment-proofs/sample2.jpg',
            'dp_uploaded_at' => Carbon::now()->subDays(7),
            'dp_verified_at' => Carbon::now()->subDays(6),
            'created_at' => Carbon::now()->startOfMonth()->addDays(3),
        ]);

        // Booking bulan ini - Pending (menunggu DP)
        Booking::create([
            'booking_code' => 'MEMO-' . strtoupper(uniqid()),
            'user_id' => $client3->id,
            'package_id' => $bronze->id,
            'event_date' => Carbon::now()->addDays(20),
            'event_time' => '09:00:00',
            'event_location' => 'Rumah Klien, Bandung',
            'notes' => 'Family photoshoot',
            'total_amount' => $bronze->price,
            'dp_amount' => $bronze->price * 0.5,
            'remaining_amount' => $bronze->price * 0.5,
            'status' => 'pending',
            'payment_proof' => 'payment-proofs/sample3.jpg',
            'dp_uploaded_at' => Carbon::now()->subDays(2),
            'created_at' => Carbon::now()->startOfMonth()->addDays(5),
        ]);

        // === BULAN SEBELUMNYA ===
        
        // Booking 1 bulan lalu - Completed
        Booking::create([
            'booking_code' => 'MEMO-' . strtoupper(uniqid()),
            'user_id' => $client1->id, // Client yang sama, jadi returning client
            'package_id' => $silver->id,
            'event_date' => Carbon::now()->subMonths(1)->addDays(5),
            'event_time' => '13:00:00',
            'event_location' => 'Garden Party, Bogor',
            'notes' => 'Birthday party',
            'total_amount' => $silver->price,
            'dp_amount' => $silver->price * 0.5,
            'remaining_amount' => 0,
            'status' => 'completed',
            'payment_proof' => 'payment-proofs/sample4.jpg',
            'dp_uploaded_at' => Carbon::now()->subMonths(2),
            'dp_verified_at' => Carbon::now()->subMonths(2)->addDays(1),
            'remaining_payment_proof' => 'remaining-proofs/sample4.jpg',
            'remaining_uploaded_at' => Carbon::now()->subMonths(1)->addDays(1),
            'remaining_verified_at' => Carbon::now()->subMonths(1)->addDays(2),
            'results_uploaded_at' => Carbon::now()->subMonths(1)->addDays(3),
            'drive_link' => 'https://drive.google.com/drive/folders/456',
            'completed_at' => Carbon::now()->subMonths(1)->addDays(4),
            'created_at' => Carbon::now()->subMonths(1)->startOfMonth()->addDays(2),
        ]);

        // Booking 1 bulan lalu - In Progress
        Booking::create([
            'booking_code' => 'MEMO-' . strtoupper(uniqid()),
            'user_id' => $client4->id,
            'package_id' => $bronze->id,
            'event_date' => Carbon::now()->subDays(2),
            'event_time' => '16:00:00',
            'event_location' => 'Studio Memo Potret',
            'notes' => 'Personal branding',
            'total_amount' => $bronze->price,
            'dp_amount' => $bronze->price * 0.5,
            'remaining_amount' => $bronze->price * 0.5,
            'status' => 'in_progress',
            'payment_proof' => 'payment-proofs/sample5.jpg',
            'dp_uploaded_at' => Carbon::now()->subMonths(1)->addDays(10),
            'dp_verified_at' => Carbon::now()->subMonths(1)->addDays(11),
            'in_progress_at' => Carbon::now()->subDays(2)->setTime(16, 0, 0),
            'created_at' => Carbon::now()->subMonths(1)->startOfMonth()->addDays(8),
        ]);

        // === BULAN 2 BULAN LALU ===
        
        // Booking 2 bulan lalu - Results Uploaded (menunggu pelunasan)
        Booking::create([
            'booking_code' => 'MEMO-' . strtoupper(uniqid()),
            'user_id' => $client2->id,
            'package_id' => $gold->id,
            'event_date' => Carbon::now()->subMonths(2)->addDays(10),
            'event_time' => '11:00:00',
            'event_location' => 'Luxury Resort, Bali',
            'notes' => 'Pre-wedding photoshoot',
            'total_amount' => $gold->price,
            'dp_amount' => $gold->price * 0.5,
            'remaining_amount' => $gold->price * 0.5,
            'status' => 'results_uploaded',
            'payment_proof' => 'payment-proofs/sample6.jpg',
            'dp_uploaded_at' => Carbon::now()->subMonths(2)->addDays(15),
            'dp_verified_at' => Carbon::now()->subMonths(2)->addDays(16),
            'results_uploaded_at' => Carbon::now()->subMonths(1)->addDays(5),
            'drive_link' => 'https://drive.google.com/drive/folders/789',
            'created_at' => Carbon::now()->subMonths(2)->startOfMonth()->addDays(5),
        ]);

        // Booking 2 bulan lalu - Pending Lunas (sudah upload bukti pelunasan)
        Booking::create([
            'booking_code' => 'MEMO-' . strtoupper(uniqid()),
            'user_id' => $client3->id,
            'package_id' => $silver->id,
            'event_date' => Carbon::now()->subMonths(2)->addDays(15),
            'event_time' => '15:00:00',
            'event_location' => 'Restoran Keluarga, Yogyakarta',
            'notes' => 'Family gathering',
            'total_amount' => $silver->price,
            'dp_amount' => $silver->price * 0.5,
            'remaining_amount' => $silver->price * 0.5,
            'status' => 'pending_lunas',
            'payment_proof' => 'payment-proofs/sample7.jpg',
            'dp_uploaded_at' => Carbon::now()->subMonths(2)->addDays(20),
            'dp_verified_at' => Carbon::now()->subMonths(2)->addDays(21),
            'remaining_payment_proof' => 'remaining-proofs/sample7.jpg',
            'remaining_uploaded_at' => Carbon::now()->subMonths(1)->addDays(2),
            'results_uploaded_at' => Carbon::now()->subMonths(1)->addDays(1),
            'drive_link' => 'https://drive.google.com/drive/folders/101',
            'pending_lunas_at' => Carbon::now()->subMonths(1)->addDays(2),
            'created_at' => Carbon::now()->subMonths(2)->startOfMonth()->addDays(10),
        ]);

        // === BULAN 3 BULAN LALU ===
        
        // Booking 3 bulan lalu - Cancelled
        Booking::create([
            'booking_code' => 'MEMO-' . strtoupper(uniqid()),
            'user_id' => $client4->id,
            'package_id' => $bronze->id,
            'event_date' => Carbon::now()->subMonths(3)->addDays(20),
            'event_time' => '09:00:00',
            'event_location' => 'Taman Kota, Semarang',
            'notes' => 'Graduation photos',
            'total_amount' => $bronze->price,
            'dp_amount' => $bronze->price * 0.5,
            'remaining_amount' => $bronze->price * 0.5,
            'status' => 'cancelled',
            'cancellation_reason' => 'client_cancelled',
            'cancellation_details' => 'Jadwal berubah, tidak bisa hadir',
            'cancelled_at' => Carbon::now()->subMonths(3)->addDays(5),
            'created_at' => Carbon::now()->subMonths(3)->startOfMonth()->addDays(1),
        ]);

        // Booking 3 bulan lalu - Completed
        Booking::create([
            'booking_code' => 'MEMO-' . strtoupper(uniqid()),
            'user_id' => $client1->id,
            'package_id' => $gold->id,
            'event_date' => Carbon::now()->subMonths(3)->addDays(25),
            'event_time' => '17:00:00',
            'event_location' => 'Villa Private, Puncak',
            'notes' => 'Anniversary celebration',
            'total_amount' => $gold->price,
            'dp_amount' => $gold->price * 0.5,
            'remaining_amount' => 0,
            'status' => 'completed',
            'payment_proof' => 'payment-proofs/sample8.jpg',
            'dp_uploaded_at' => Carbon::now()->subMonths(3)->addDays(10),
            'dp_verified_at' => Carbon::now()->subMonths(3)->addDays(11),
            'remaining_payment_proof' => 'remaining-proofs/sample8.jpg',
            'remaining_uploaded_at' => Carbon::now()->subMonths(3)->addDays(20),
            'remaining_verified_at' => Carbon::now()->subMonths(3)->addDays(21),
            'results_uploaded_at' => Carbon::now()->subMonths(3)->addDays(22),
            'drive_link' => 'https://drive.google.com/drive/folders/202',
            'completed_at' => Carbon::now()->subMonths(3)->addDays(23),
            'created_at' => Carbon::now()->subMonths(3)->startOfMonth()->addDays(3),
        ]);

        // === DATA UNTUK RENTANG WAKTU YANG LEBIH LAMA ===
        
        // Buat beberapa booking untuk 6-12 bulan terakhir
        $packages = [$bronze, $silver, $gold];
        $clients = [$client1, $client2, $client3, $client4];
        $statuses = ['completed', 'confirmed', 'pending', 'cancelled', 'in_progress', 'results_uploaded', 'pending_lunas'];
        
        for ($i = 0; $i < 10; $i++) {
            $package = $packages[array_rand($packages)];
            $client = $clients[array_rand($clients)];
            $status = $statuses[array_rand($statuses)];
            
            // Random date dalam 12 bulan terakhir
            $createdAt = Carbon::now()->subMonths(rand(4, 12))->subDays(rand(0, 30));
            $eventDate = $createdAt->copy()->addDays(rand(10, 60));
            
            $bookingData = [
                'booking_code' => 'MEMO-' . strtoupper(uniqid()),
                'user_id' => $client->id,
                'package_id' => $package->id,
                'event_date' => $eventDate,
                'event_time' => sprintf('%02d:00:00', rand(8, 17)),
                'event_location' => $this->getRandomLocation(),
                'notes' => $this->getRandomNote(),
                'total_amount' => $package->price,
                'dp_amount' => $package->price * 0.5,
                'remaining_amount' => $status === 'completed' ? 0 : ($package->price * 0.5),
                'status' => $status,
                'payment_proof' => 'payment-proofs/sample' . ($i + 9) . '.jpg',
                'dp_uploaded_at' => $createdAt->copy()->addDays(rand(1, 3)),
                'created_at' => $createdAt,
            ];
            
            // Tambahkan data tambahan berdasarkan status
            if (in_array($status, ['confirmed', 'in_progress', 'results_uploaded', 'pending_lunas', 'completed'])) {
                $bookingData['dp_verified_at'] = $bookingData['dp_uploaded_at']->copy()->addHours(rand(1, 24));
            }
            
            if (in_array($status, ['in_progress'])) {
                $bookingData['in_progress_at'] = $eventDate->copy()->setTime(
                    intval(substr($bookingData['event_time'], 0, 2)),
                    0,
                    0
                );
            }
            
            if (in_array($status, ['results_uploaded', 'pending_lunas', 'completed'])) {
                $bookingData['results_uploaded_at'] = $eventDate->copy()->addDays(rand(3, 7));
                $bookingData['drive_link'] = 'https://drive.google.com/drive/folders/' . uniqid();
            }
            
            if (in_array($status, ['pending_lunas', 'completed'])) {
                $bookingData['remaining_payment_proof'] = 'remaining-proofs/sample' . ($i + 9) . '.jpg';
                $bookingData['remaining_uploaded_at'] = $eventDate->copy()->addDays(rand(1, 5));
                $bookingData['pending_lunas_at'] = $bookingData['remaining_uploaded_at'];
            }
            
            if ($status === 'completed') {
                $bookingData['remaining_verified_at'] = $bookingData['remaining_uploaded_at']->copy()->addHours(rand(1, 24));
                $bookingData['completed_at'] = $bookingData['remaining_verified_at'];
                $bookingData['remaining_amount'] = 0;
            }
            
            if ($status === 'cancelled') {
                $bookingData['cancellation_reason'] = ['client_cancelled', 'admin_cancelled', 'invalid_payment'][array_rand([0, 1, 2])];
                $bookingData['cancellation_details'] = $bookingData['cancellation_reason'] === 'client_cancelled' 
                    ? 'Jadwal berubah' 
                    : 'Pembayaran tidak valid';
                $bookingData['cancelled_at'] = $createdAt->copy()->addDays(rand(1, 5));
            }
            
            Booking::create($bookingData);
        }

        $this->command->info('✅ Data laporan berhasil di-generate!');
        $this->command->info('📊 Total Booking: ' . Booking::count());
        $this->command->info('💰 Pendapatan bulan ini: Rp ' . number_format(
            Booking::whereMonth('created_at', now()->month)
                ->where('status', '!=', 'cancelled')
                ->sum('total_amount'),
            0, ',', '.'
        ));
        $this->command->info('👥 Total Client: ' . User::where('role', 'client')->count());
        $this->command->info('📦 Total Package: ' . Package::count());
    }
    
    private function getRandomLocation(): string
    {
        $locations = [
            'Hotel Grand Indonesia, Jakarta',
            'Studio Memo Potret, Surabaya',
            'Gedung Serbaguna, Bandung',
            'Restoran Bunga, Yogyakarta',
            'Taman Kota, Semarang',
            'Villa Private, Bali',
            'Pantai Indah, Lombok',
            'Kafe Minimalis, Medan',
            'Gedung Pernikahan, Makassar',
            'Rumah Klien, Palembang',
        ];
        
        return $locations[array_rand($locations)];
    }
    
    private function getRandomNote(): string
    {
        $notes = [
            'Wedding photography with family',
            'Corporate annual meeting',
            'Product launch event',
            'Birthday party celebration',
            'Graduation photoshoot',
            'Family portrait session',
            'Personal branding photos',
            'Pre-wedding photoshoot',
            'Maternity photoshoot',
            'Newborn baby photos',
            'Business profile photos',
            'Food photography session',
            'Real estate property photos',
            'Fashion lookbook photos',
            'Music album cover shoot',
        ];
        
        return $notes[array_rand($notes)];
    }
}