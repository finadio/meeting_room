<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserTableSeeder extends Seeder
{
    /**
     * Jalankan seeder database.
     */
    public function run()
    {
        // Buat pengguna admin
        User::create([
            'name' => 'Nischal Acharya',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin'),
            'user_type' => 'admin',
            'verified' => 1, // Diubah dari 'true' menjadi 1
        ]);
    }
}
