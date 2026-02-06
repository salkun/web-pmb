<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function index()
    {
        $user_id = auth_id();
        $profile = Profile::where('user_id', $user_id)->first();
        
        // If profile doesn't exist, create empty one (should have been created at register, but safe guard)
        if (!$profile) {
            $profile = Profile::create(['user_id' => $user_id]);
        }
        
        return view('user.profile', compact('profile'));
    }

    public function update(Request $request)
    {
        $profile = Profile::where('user_id', auth_id())->firstOrFail();

        $request->validate([
            'nama_lengkap' => 'required|string|max:150',
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'asal_sekolah' => 'required|string|max:150',
            'tahun_kelulusan' => 'required|integer|digits:4',
            'alamat' => 'required|string',
            'email_aktif' => 'required|email|max:100',
            'no_hp_aktif' => 'required|string|min:10|max:15',
            'foto_profil' => 'nullable|file|mimes:jpg,jpeg,png|max:5120'
        ], [
            'required' => ':attribute wajib diisi',
            'email' => ':attribute tidak valid',
            'max' => ':attribute maksimal :max karakter',
            'min' => ':attribute minimal :min karakter',
            'mimes' => 'Format foto harus jpg, jpeg, atau png',
            'file' => 'Upload harus berupa file'
        ]);

        DB::beginTransaction();
         try {
            $data = $request->except(['foto_profil', '_token']);
            
            // Handle File Upload
            if ($request->hasFile('foto_profil')) {
                // Delete old photo if exists
                if ($profile->foto_profil && Storage::exists('public/' . $profile->foto_profil)) {
                     Storage::delete('public/' . $profile->foto_profil);
                }

                $path = $request->file('foto_profil')->store('foto_profil', 'public');
                $data['foto_profil'] = $path;
            }

            // Check completeness
            $is_complete = true;
            $required_fields = ['nama_lengkap', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'asal_sekolah', 'tahun_kelulusan', 'alamat', 'email_aktif', 'no_hp_aktif'];
            
            // Photo is also required for completeness? Let's assume yes based on prompt
            if (!$profile->foto_profil && !isset($data['foto_profil'])) {
                $is_complete = false;
            }

            foreach($required_fields as $field) {
                if (empty($data[$field])) $is_complete = false;
            }

            $data['is_complete'] = $is_complete;
            if ($is_complete && !$profile->is_complete) {
                $data['completed_at'] = now();
            }

            $profile->update($data);
            
            // Update Session
            $currentSession = session('user');
            $currentSession['nama_lengkap'] = $profile->nama_lengkap;
            $currentSession['foto_profil'] = $profile->foto_profil;
            session(['user' => $currentSession]);
            
            // Get fresh user data to ensure no_pendaftaran is available even if old session
            $user = \App\Models\User::find(auth_id());
            $user_identifier = $user ? $user->no_pendaftaran : 'unknown';

            ActivityLog::create([
                'user_id' => auth_id(),
                'username' => $user_identifier,
                'activity_type' => 'update_profile',
                'description' => 'User updated profile data',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Profil berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollback();
             // Log error for debugging if needed, but show user message
            return redirect()->back()->with('error', 'Gagal memperbarui profil: ' . $e->getMessage() . ' on line ' . $e->getLine());
        }
    }
}
