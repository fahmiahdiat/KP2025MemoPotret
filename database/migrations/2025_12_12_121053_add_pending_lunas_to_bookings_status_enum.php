<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
         // Ubah tipe kolom status untuk menambah nilai enum baru
        DB::statement("ALTER TABLE bookings 
            MODIFY COLUMN status 
            ENUM('pending', 'confirmed', 'in_progress', 'results_uploaded', 'completed', 'cancelled', 'pending_lunas') 
            NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke enum lama (tanpa pending_lunas)
        DB::statement("ALTER TABLE bookings 
            MODIFY COLUMN status 
            ENUM('pending', 'confirmed', 'in_progress', 'results_uploaded', 'completed', 'cancelled') 
            NOT NULL DEFAULT 'pending'");
    }
};
