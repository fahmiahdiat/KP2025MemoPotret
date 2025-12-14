<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->timestamp('completed_at')->nullable()->after('admin_notes');
            $table->string('drive_password')->nullable()->after('drive_link');
            $table->string('cancellation_reason')->nullable()->after('completed_at');
            $table->text('cancellation_details')->nullable()->after('cancellation_reason');
            $table->timestamp('cancelled_at')->nullable()->after('cancellation_details');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'completed_at',
                'drive_password',
                'cancellation_reason',
                'cancellation_details',
                'cancelled_at'
            ]);
        });
    }
};