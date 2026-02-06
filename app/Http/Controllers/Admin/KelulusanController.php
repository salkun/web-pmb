<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelulusan;
use App\Models\KartuUjian;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KelulusanController extends Controller
{
    public function index(Request $request)
    {
        // Get list of students who have exam cards (Participants)
        $peserta = KartuUjian::with(['user.profile', 'user.kelulusan', 'pengaturanUjian'])
                             ->whereHas('user') // Ensure user exists
                             ->whereHas('pengaturanUjian') // Ensure pengaturan ujian exists
                             ->join('pengaturan_ujian', 'kartu_ujian.pengaturan_ujian_id', '=', 'pengaturan_ujian.id')
                             ->select('kartu_ujian.*') // Avoid column collision
                             ->orderBy('pengaturan_ujian.tanggal_ujian', 'desc')
                             ->orderBy('kartu_ujian.nomor_peserta', 'asc');

        if ($request->has('search')) {
            $peserta->where('kartu_ujian.nomor_peserta', 'like', '%' . $request->search . '%');
        }

        $peserta = $peserta->paginate(20);

        return view('admin.kelulusan.index', compact('peserta'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'pengaturan_ujian_id' => 'required|exists:pengaturan_ujian,id',
            'nilai' => 'required|numeric|min:0|max:100',
            'status' => 'required|in:lulus,tidak_lulus',
            'catatan' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            Kelulusan::updateOrCreate(
                [
                    'user_id' => $request->user_id,
                    'pengaturan_ujian_id' => $request->pengaturan_ujian_id
                ],
                [
                    'nilai' => $request->nilai,
                    'status' => $request->status,
                    'catatan' => $request->catatan,
                    'tanggal_pengumuman' => now(), // Or specific date
                    'is_published' => true, // Auto publish for simplicty or make separate toggle
                    'diumumkan_oleh' => auth_id()
                ]
            );

            ActivityLog::create([
                'user_id' => auth_id(),
                'username' => auth_user()['no_pendaftaran'],
                'activity_type' => 'input_kelulusan',
                'description' => 'Input kelulusan for UserID: ' . $request->user_id . ' Status: ' . $request->status,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            DB::commit();
            return back()->with('success', 'Data kelulusan berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal menyimpan kelulusan: ' . $e->getMessage());
        }
    }
}
