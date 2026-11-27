<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Booking tanpa pemilik adalah sisa akun demo lama yang sudah dihapus.
        DB::table('bookings')->whereNull('user_id')->delete();
    }

    public function down(): void
    {
        // Data demo sengaja tidak dikembalikan.
    }
};
