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
        Schema::table('bookings', function (Blueprint $table) {
            $table->timestamp('check_in')->nullable()->after('booking_end'); // Kolom cek-in
            $table->timestamp('check_out')->nullable()->after('check_in');   // Kolom cek-out
            $table->boolean('is_checked_in')->default(false)->after('check_out'); // Status cek-in
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['check_in', 'check_out', 'is_checked_in']);
        });
    }
};
