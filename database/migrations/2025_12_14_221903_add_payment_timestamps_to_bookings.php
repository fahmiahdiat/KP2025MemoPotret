<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->timestamp('remaining_uploaded_at')->nullable()->after('remaining_payment_notes');
            $table->timestamp('remaining_verified_at')->nullable()->after('remaining_uploaded_at');
            $table->timestamp('results_updated_at')->nullable()->after('results_uploaded_at');
        });
    }

    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'remaining_uploaded_at',
                'remaining_verified_at',
                'results_updated_at'
            ]);
        });
    }
};