<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('bookings', function (Blueprint $table) {
        $table->string('remaining_payment_proof')->nullable()->after('payment_proof');
        $table->text('remaining_payment_notes')->nullable()->after('remaining_payment_proof');
    });
}

public function down()
{
    Schema::table('bookings', function (Blueprint $table) {
        $table->dropColumn(['remaining_payment_proof', 'remaining_payment_notes']);
    });
}
};
