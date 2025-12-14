<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->timestamp('dp_verified_at')->nullable()->after('payment_notes');
            $table->timestamp('in_progress_at')->nullable()->after('dp_verified_at');
            $table->timestamp('results_uploaded_at')->nullable()->after('in_progress_at');
            $table->timestamp('pending_lunas_at')->nullable()->after('results_uploaded_at');
            // completed_at sudah ada
        });
    }

    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'dp_verified_at',
                'in_progress_at',
                'results_uploaded_at',
                'pending_lunas_at'
            ]);
        });
    }
};