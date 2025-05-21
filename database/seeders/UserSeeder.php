<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat atau update pengguna admin
        User::updateOrCreate(
            [
                'username' => 'admin', // Cek berdasarkan username
            ],
            [
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('admin'),
                'role' => 1, // Pastikan ini adalah role_id yang benar
                // 'avatar' => 'admin.jpg',
                'status' => '0', // Pastikan ini sesuai dengan tipe data di database
                // 'schedule' => 1, // Pastikan ini adalah schedule_id yang benar
                // 'delete' => '0', // Jika ini adalah boolean, sesuaikan dengan tipe data di database
                'token' => Str::random(10),
            ]
        );

        // Buat pengguna pegawai
        User::updateOrCreate(
            [
                'username' => '00001', // Cek berdasarkan username
            ],
            [
                'name' => 'Faldi Reza',
                'email' => 'apisedit09@gmail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('Faldi123@'),
                'role' => 2, // Pastikan ini adalah role_id yang benar
                // 'avatar' => 'faldi.jpg',
                'status' => '1',
                // 'schedule' => , // Pastikan ini adalah schedule_id yang benar
                // 'delete' => '0',
                'token' => Str::random(10),
            ]
        );
    }
}
