<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class UsersImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        // 1. Create User
        $user = User::create([
            'username'  => $row['username'],
            'password'  => Hash::make($row['password']),
            'role'      => strtolower($row['role']) ?: 'user',
            'is_active' => true,
        ]);

        // 2. Create Profile if role is user
        if ($user->role == 'user') {
            Profile::create([
                'user_id'         => $user->id,
                'nama_lengkap'    => $row['nama_lengkap'] ?? $user->username,
                'email_aktif'     => $row['email'] ?? '-',
                'is_complete'     => false,
                // Default values
                'tempat_lahir'    => '-',
                'tanggal_lahir'   => '2000-01-01',
                'jenis_kelamin'   => 'L',
                'alamat'          => '-',
                'no_hp_aktif'     => '-',
                'asal_sekolah'    => '-',
                'tahun_kelulusan' => date('Y')
            ]);
        }

        return $user;
    }

    public function rules(): array
    {
        return [
            'username' => 'required|unique:users,username',
            'password' => 'required|min:6',
            'role'     => 'required|in:admin,user',
            // 'nama_lengkap' => 'required'
        ];
    }
}
