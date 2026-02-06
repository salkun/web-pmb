<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\ActivityLog;

class ResetPasswordController extends Controller
{
    public function showRequestForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['no_pendaftaran' => 'required']);

        $user = User::where('no_pendaftaran', $request->no_pendaftaran)->first();

        if (!$user) {
            return back()->with('error', 'No Pendaftaran tidak ditemukan.');
        }

        $token = Str::random(60);
        $user->update([
            'reset_token' => $token,
            'reset_token_expired' => now()->addHour()
        ]);

        // Karena manual dan mungkin local, kita tampilkan linknya langsung di flash message
        $link = route('password.reset', ['token' => $token]);

        return back()->with('success_link', $link); 
    }

    public function showResetForm($token)
    {
        $user = User::where('reset_token', $token)
                    ->where('reset_token_expired', '>', now())
                    ->first();

        if (!$user) {
            return redirect()->route('password.request')->with('error', 'Token reset password tidak valid atau sudah kadaluarsa.');
        }

        return view('auth.reset-password', compact('token'));
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'password' => 'required|min:8|confirmed'
        ]);

        $user = User::where('reset_token', $request->token)
                    ->where('reset_token_expired', '>', now())
                    ->first();

        if (!$user) {
             return redirect()->route('password.request')->with('error', 'Token tidak valid.');
        }

        $user->update([
            'password' => Hash::make($request->password),
            'reset_token' => null,
            'reset_token_expired' => null
        ]);

        ActivityLog::create([
            'user_id' => $user->id,
            'username' => $user->no_pendaftaran,
            'activity_type' => 'reset_password',
            'description' => 'Password reset successfully',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return redirect()->route('login')->with('success', 'Password berhasil direset. Silakan login.');
    }
}
