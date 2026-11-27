<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('tutor_pendamping')->nullable()->after('problem_description');
            $table->text('catatan')->nullable()->after('tutor_pendamping');
            $table->timestamp('confirmed_at')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['tutor_pendamping', 'catatan', 'confirmed_at']);
        });
    }
};
