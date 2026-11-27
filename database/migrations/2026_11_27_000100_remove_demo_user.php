<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('users')
            ->where('email', 'mahasiswa@students.undip.ac.id')
            ->delete();
    }

    public function down(): void
    {
        // Akun demo mahasiswa sengaja tidak dikembalikan.
    }
};
