<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Profile;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Admin
        User::create([
            'no_pendaftaran' => 'admin',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // User 1
        $user1 = User::create([
            'no_pendaftaran' => '20250001',
            'password' => Hash::make('password'),
            'role' => 'user',
            'is_active' => true,
        ]);
        
        // Dummy Profile for User 1 (Incomplete)
        Profile::create([
            'user_id' => $user1->id,
            'nama_lengkap' => 'User Satu',
            'jenis_kelamin' => 'L',
            'program_studi' => 'Teknologi Radiologi D4',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2000-01-01',
            'asal_sekolah' => 'SMA Negeri 1 Jakarta',
            'tahun_kelulusan' => 2018,
            'alamat' => 'Jl. Jend. Sudirman No. 1',
            'email_aktif' => 'user1@example.com',
            'no_hp_aktif' => '081234567890',
            'is_complete' => false
        ]);

        // User 2
         $user2 = User::create([
            'no_pendaftaran' => '20250002',
            'password' => Hash::make('password'),
            'role' => 'user',
            'is_active' => true,
        ]);

         // Dummy Profile for User 2 (Complete)
        Profile::create([
            'user_id' => $user2->id,
            'nama_lengkap' => 'User Dua',
            'jenis_kelamin' => 'P',
            'program_studi' => 'Teknologi Radiologi D4',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '2001-02-02',
            'asal_sekolah' => 'SMA Negeri 2 Bandung',
            'tahun_kelulusan' => 2019,
            'alamat' => 'Jl. Asia Afrika No. 2',
            'email_aktif' => 'user2@example.com',
            'no_hp_aktif' => '081234567891',
            'is_complete' => true,
            'completed_at' => now()
        ]);
    }
}
