<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Profile;
use App\Models\ActivityLog;

class RegisterController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'no_pendaftaran' => 'required|min:5|max:50|alpha_dash|unique:users',
            'password' => 'required|min:8|confirmed',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'no_pendaftaran' => $request->no_pendaftaran,
                'password' => Hash::make($request->password),
                'role' => 'user',
                'is_active' => true,
            ]);

            // Create empty profile
            Profile::create([
                'user_id' => $user->id,
                'nama_lengkap' => $request->no_pendaftaran, // Default name
                'jenis_kelamin' => 'L', // Default to avoid null error, need user update
                'program_studi' => 'Teknik Radiologi Pencitraan D4',
                'tempat_lahir' => '-',
                'tanggal_lahir' => now(),
                'asal_sekolah' => '-',
                'tahun_kelulusan' => date('Y'),
                'alamat' => '-',
                'email_aktif' => '-',
                'no_hp_aktif' => '-',
                'is_complete' => false
            ]);

            ActivityLog::create([
                'user_id' => $user->id,
                'username' => $user->no_pendaftaran,
                'activity_type' => 'register',
                'description' => 'User registered successfully',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            DB::commit();

            // Auto login
             session([
                'user' => [
                    'id' => $user->id,
                    'no_pendaftaran' => $user->no_pendaftaran,
                    'role' => $user->role,
                    'nama_lengkap' => $user->no_pendaftaran, // Initial name as per Profile creation
                    'foto_profil' => null,
                ]
            ]);

            return redirect()->route('user.profile')->with('success', 'Registrasi berhasil. Silakan lengkapi profil Anda.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan saat registrasi: ' . $e->getMessage())->withInput();
        }
    }
}
