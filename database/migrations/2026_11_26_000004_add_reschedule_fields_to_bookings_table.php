<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->date('reschedule_date')->nullable()->after('confirmed_at');
            $table->string('reschedule_start_time')->nullable()->after('reschedule_date');
            $table->string('reschedule_end_time')->nullable()->after('reschedule_start_time');
            $table->unsignedInteger('reschedule_duration')->nullable()->after('reschedule_end_time');
            $table->text('reschedule_alasan')->nullable()->after('reschedule_duration');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'reschedule_date',
                'reschedule_start_time',
                'reschedule_end_time',
                'reschedule_duration',
                'reschedule_alasan',
            ]);
        });
    }
};
