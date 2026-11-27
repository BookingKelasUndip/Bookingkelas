<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Akun admin tetap disediakan; akun demo mahasiswa sudah dihapus.
        User::updateOrCreate(
            ['email' => 'admin@undip.ac.id'],
            [
                'name' => 'Admin Demo',
                'nim' => null,
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ],
        );
    }
}
