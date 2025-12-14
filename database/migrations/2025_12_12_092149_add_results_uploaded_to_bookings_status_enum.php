<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Untuk MySQL
        DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM('pending', 'confirmed', 'in_progress', 'results_uploaded', 'completed', 'cancelled', 'pending_lunas') DEFAULT 'pending'");
        
        // Atau untuk PostgreSQL
        // DB::statement("ALTER TABLE bookings ALTER COLUMN status TYPE VARCHAR(255)");
        // DB::statement("ALTER TABLE bookings ADD CONSTRAINT check_status CHECK (status IN ('pending', 'confirmed', 'in_progress', 'results_uploaded', 'completed', 'cancelled', 'pending_lunas'))");
    }

    public function down(): void
    {
        // Kembalikan ke semula
        DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM('pending', 'confirmed', 'in_progress', 'completed', 'cancelled') DEFAULT 'pending'");
    }
};