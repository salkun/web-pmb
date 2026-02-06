<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JenisBerkas;
use App\Models\BerkasMahasiswa;
use App\Models\Profile;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class BerkasController extends Controller
{
    public function index()
    {
        $user_id = auth_id();
        
        // Cek kelengkapan profil
        $profile = Profile::where('user_id', $user_id)->first();
        if (!$profile || !$profile->is_complete) {
            return redirect()->route('user.profile')->with('error', 'Silakan lengkapi profil Anda terlebih dahulu sebelum upload berkas.');
        }

        $jenis_berkas = JenisBerkas::where('is_active', true)->orderBy('urutan')->get();
        $berkas = BerkasMahasiswa::where('user_id', $user_id)->get()->keyBy('jenis_berkas_id');
        
        // Calculate progress
        $wajib_verified = 0;
        $wajib_total = $jenis_berkas->where('is_required', true)->count();
        
        foreach ($jenis_berkas as $jb) {
            if ($jb->is_required) {
                if (isset($berkas[$jb->id]) && $berkas[$jb->id]->status == 'verified') {
                    $wajib_verified++;
                }
            }
        }
        
        $progress = $wajib_total > 0 ? ($wajib_verified / $wajib_total) * 100 : 0;

        return view('user.berkas', compact('jenis_berkas', 'berkas', 'progress'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'jenis_berkas_id' => 'required|exists:jenis_berkas,id',
            'file' => 'required|file|max:5120|mimes:pdf,jpg,jpeg,png' // Max 5MB
        ]);

        $jb = JenisBerkas::findOrFail($request->jenis_berkas_id);
        $user_id = auth_id();

        DB::beginTransaction();
        try {
            $file = $request->file('file');
            $filename = time() . '_' . $jb->kode . '_' . $user_id . '.' . $file->getClientOriginalExtension();
            
            // Store file
            $path = $file->storeAs('berkas/' . $user_id, $filename, 'public'); // storage/app/public/berkas/{user_id}/...

            // Update or Create record
            BerkasMahasiswa::updateOrCreate(
                [
                    'user_id' => $user_id,
                    'jenis_berkas_id' => $jb->id
                ],
                [
                    'file_path' => $path,
                    'file_name' => $filename,
                    'file_original_name' => $file->getClientOriginalName(),
                    'file_size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'status' => 'pending',
                    'catatan_admin' => null, // Reset catatan if re-upload
                    'uploaded_at' => now()
                ]
            );

            ActivityLog::create([
                'user_id' => $user_id,
                'username' => auth_user()['no_pendaftaran'],
                'activity_type' => 'upload_berkas',
                'description' => 'User uploaded file: ' . $jb->nama_berkas,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Berkas berhasil diupload.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal upload berkas: ' . $e->getMessage());
        }
    }
}
