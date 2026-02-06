<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user_id = auth_id();
        $user = \App\Models\User::with(['profile', 'berkas', 'kartuUjian', 'kelulusan'])->find($user_id);
        
        // Progress Calculation
        $progress = 0;
        $status_label = 'Mulai Pendaftaran';
        
        if ($user->profile && $user->profile->is_complete) {
            $progress += 25;
            $status_label = 'Profil Lengkap';
        }
        
        $total_required = \App\Models\JenisBerkas::where('is_required', true)->where('is_active', true)->count();
        $user_verified_berkas = $user->berkas->where('status', 'verified')->count();
        
        if ($total_required > 0) {
            $berkas_progress = min(25, ($user_verified_berkas / $total_required) * 25);
            $progress += $berkas_progress;
            if ($user_verified_berkas >= $total_required) {
                $status_label = 'Berkas Terverifikasi';
            }
        }
        
        if ($user->kartuUjian) {
            $progress += 25;
            $status_label = 'Siap Ujian';
        }
        
        if ($user->kelulusan && $user->kelulusan->is_published) {
            $progress += 25;
            $status_label = 'Hasil Diumumkan';
        }

        return view('user.dashboard', compact('user', 'progress', 'status_label', 'user_verified_berkas', 'total_required'));
    }
}
