<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengaturanUjian;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UjianController extends Controller
{
    public function index()
    {
        $ujian = PengaturanUjian::orderBy('tahun_akademik', 'desc')
                                ->orderBy('gelombang', 'desc')
                                ->paginate(10);
        return view('admin.ujian.index', compact('ujian'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahun_akademik' => 'required|string|max:9', // e.g., 2025/2026
            'gelombang' => 'required|integer',
            'tanggal_ujian' => 'required|date',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required|after:waktu_mulai',
            'tempat_ujian' => 'required|string',
            'alamat_lengkap' => 'required|string',
            'kuota' => 'nullable|integer',
        ]);

        // Check uniqueness
        $exists = PengaturanUjian::where('tahun_akademik', $request->tahun_akademik)
                                 ->where('gelombang', $request->gelombang)
                                 ->exists();
        if ($exists) {
            return back()->with('error', 'Jadwal untuk Tahun Akademik dan Gelombang tersebut sudah ada.');
        }

        PengaturanUjian::create($request->all());

        ActivityLog::create([
            'user_id' => auth_id(),
            'username' => auth_user()['no_pendaftaran'],
            'activity_type' => 'create_ujian',
            'description' => 'Created Ujian Schedule: ' . $request->tahun_akademik . ' Gelombang ' . $request->gelombang,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return back()->with('success', 'Pengaturan ujian berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $ujian = PengaturanUjian::findOrFail($id);
        
        $request->validate([
            'tahun_akademik' => 'required|string|max:9',
            'gelombang' => 'required|integer',
            'tanggal_ujian' => 'required|date',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required|after:waktu_mulai',
            'tempat_ujian' => 'required|string',
            'alamat_lengkap' => 'required|string',
            'kuota' => 'nullable|integer',
        ]);

        $ujian->update($request->all());

        ActivityLog::create([
            'user_id' => auth_id(),
            'username' => auth_user()['no_pendaftaran'],
            'activity_type' => 'update_ujian',
            'description' => 'Updated Ujian Schedule ID: ' . $ujian->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return back()->with('success', 'Pengaturan ujian berhasil diperbarui.');
    }

    public function setActive($id)
    {
        $ujian = PengaturanUjian::findOrFail($id);

        DB::beginTransaction();
        try {
            // Set all to inactive
            PengaturanUjian::query()->update(['is_active' => false]);
            
            // Set selected to active
            $ujian->update(['is_active' => true]);

            ActivityLog::create([
                'user_id' => auth_id(),
                'username' => auth_user()['no_pendaftaran'],
                'activity_type' => 'activate_ujian',
                'description' => 'Activated Ujian Schedule ID: ' . $ujian->id,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);

            DB::commit();
            return back()->with('success', 'Jadwal ujian berhasil diaktifkan.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal mengaktifkan jadwal: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $ujian = PengaturanUjian::findOrFail($id);
        
        if ($ujian->is_active) {
            return back()->with('error', 'Tidak dapat menghapus jadwal yang sedang aktif.');
        }

        $ujian->delete();

        ActivityLog::create([
            'user_id' => auth_id(),
            'username' => auth_user()['no_pendaftaran'],
            'activity_type' => 'delete_ujian',
            'description' => 'Deleted Ujian Schedule ID: ' . $id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);

        return back()->with('success', 'Jadwal ujian berhasil dihapus.');
    }
}
