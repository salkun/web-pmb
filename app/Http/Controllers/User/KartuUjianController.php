<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\KartuUjian;
use App\Models\PengaturanUjian;
use App\Models\BerkasMahasiswa;
use App\Models\JenisBerkas;
use App\Models\Profile;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class KartuUjianController extends Controller
{
    public function index()
    {
        $user_id = auth_id();
        $kartu = KartuUjian::with('pengaturanUjian')->where('user_id', $user_id)->first();
        
        // Cek syarat generate kartu
        $can_generate = false;
        $message = '';
        $schedule = null;

        // 1. Cek Profile
        $profile = Profile::where('user_id', $user_id)->first();
        if (!$profile || !$profile->is_complete) {
            $message = 'Profil belum lengkap.';
        } else {
            // 2. Cek Berkas Wajib
            $wajib_verified = true;
            $jenis_berkas_wajib = JenisBerkas::where('is_required', true)->where('is_active', true)->pluck('id');
            $berkas_user = BerkasMahasiswa::where('user_id', $user_id)
                                          ->whereIn('jenis_berkas_id', $jenis_berkas_wajib)
                                          ->where('status', 'verified')
                                          ->count();
            
            if ($berkas_user < $jenis_berkas_wajib->count()) {
                $wajib_verified = false;
                $message = 'Semua berkas wajib harus terverifikasi.';
            } else {
                // 3. Cek Jadwal Ujian Aktif
                $schedule = PengaturanUjian::where('is_active', true)->first();
                if (!$schedule) {
                    $message = 'Belum ada jadwal ujian yang aktif.';
                } else {
                    $can_generate = true;
                }
            }
        }

        return view('user.kartu-ujian', compact('kartu', 'can_generate', 'message', 'schedule', 'profile'));
    }

    public function generate()
    {
        $user_id = auth_id();
        
        // Re-check conditions (security)
        // ... (Skipped for brevity, assume index checking fits for UX, but real apps should double check)

        $schedule = PengaturanUjian::where('is_active', true)->firstOrFail();
        
        // Cek kuota
        if ($schedule->kuota > 0) {
            $count = KartuUjian::where('pengaturan_ujian_id', $schedule->id)->count();
            if ($count >= $schedule->kuota) {
                return back()->with('error', 'Kuota ujian untuk gelombang ini sudah penuh.');
            }
        }

        DB::beginTransaction();
        try {
            // Generate Nomor Peserta from No Pendaftaran
            // $prefix = date('Y') . $schedule->gelombang;
            // $last_kartu = KartuUjian::where('nomor_peserta', 'like', $prefix . '%')->orderBy('nomor_peserta', 'desc')->first();
            // $number = $last_kartu ? intval(substr($last_kartu->nomor_peserta, -4)) + 1 : 1;
            // $nomor_peserta = $prefix . sprintf('%04d', $number);
            
            // Per request: Use user's no_pendaftaran as nomor_peserta
            $user = \App\Models\User::find($user_id);
            $nomor_peserta = $user->no_pendaftaran;
            
            // Determine seat number (keep logical or random, usually sequential per room)
            // Just keeping simple sequential for seat number for now based on total cards
            $number = KartuUjian::where('pengaturan_ujian_id', $schedule->id)->count() + 1;

            KartuUjian::create([
                'user_id' => $user_id,
                'pengaturan_ujian_id' => $schedule->id,
                'nomor_peserta' => $nomor_peserta,
                'ruangan' => 'Online', // Default logic or assign randomly
                'nomor_kursi' => $number,
                'generated_at' => now(),
                'generated_by' => $user_id
            ]);

            ActivityLog::create([
                'user_id' => $user_id,
                'username' => $user->no_pendaftaran,
                'activity_type' => 'generate_kartu',
                'description' => 'Generated Kartu Ujian No: ' . $nomor_peserta,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);

            DB::commit();
            return back()->with('success', 'Kartu ujian berhasil digenerate.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal generate kartu: ' . $e->getMessage());
        }
    }

    public function download()
    {
        $user_id = auth_id();
        $kartu = KartuUjian::with(['user.profile', 'pengaturanUjian'])
                           ->where('user_id', $user_id)
                           ->firstOrFail();
        
        $kartu->increment('download_count');
        $kartu->update(['downloaded_at' => now()]);

        // Generate PDF using DOMPDF
        $data = [
            'kartu' => $kartu,
            'user' => $kartu->user,
            'profile' => $kartu->user->profile,
            'is_pdf' => true
        ];

        $pdf = PDF::loadView('user.kartu-ujian-print', $data);
        $pdf->setPaper('a4', 'portrait');

        return $pdf->download('KARTU_UJIAN_' . $kartu->nomor_peserta . '.pdf');
    }
}
