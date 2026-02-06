<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\ActivityLog;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'no_pendaftaran' => 'required',
            'password' => 'required',
        ]);

            $user = User::with('profile')->where('no_pendaftaran', $request->no_pendaftaran)->first();

            if ($user && Hash::check($request->password, $user->password)) {
                if (!$user->is_active) {
                    return back()->with('error', 'Akun Anda nonaktif. Silakan hubungi admin.');
                }

                session([
                    'user' => [
                        'id' => $user->id,
                        'no_pendaftaran' => $user->no_pendaftaran,
                        'role' => $user->role,
                        'nama_lengkap' => $user->profile->nama_lengkap ?? null,
                        'foto_profil' => $user->profile->foto_profil ?? null,
                    ]
                ]);

            $user->update(['last_login' => now()]);
            
            ActivityLog::create([
                'user_id' => $user->id,
                'username' => $user->no_pendaftaran,
                'activity_type' => 'login',
                'description' => 'User logged in successfully',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            // Cek kelengkapan profile untuk user
            if ($user->role === 'user') {
                return redirect()->route('user.dashboard');
            }
        }

        return back()->with('error', 'No Pendaftaran atau password salah.');
    }
}
