<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BerkasMahasiswa;
use App\Models\JenisBerkas;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BerkasController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        $students_query = \App\Models\User::where('role', 'user')
            ->whereHas('berkas');

        if ($search) {
            $students_query->where(function($q) use ($search) {
                $q->where('no_pendaftaran', 'like', "%$search%")
                  ->orWhereHas('profile', function($pq) use ($search) {
                      $pq->where('nama_lengkap', 'like', "%$search%");
                  });
            });
        }

        $students = $students_query->with(['profile'])
            ->withCount([
                'berkas as pending_count' => function($q) { $q->where('status', 'pending'); },
                'berkas as verified_count' => function($q) { $q->where('status', 'verified'); },
                'berkas as rejected_count' => function($q) { $q->where('status', 'rejected'); },
                'berkas as total_count'
            ])
            ->orderBy('pending_count', 'desc')
            ->paginate(15);

        return view('admin.berkas.index', compact('students', 'search'));
    }

    public function show($id)
    {
        $student = \App\Models\User::with(['profile', 'berkas.jenisBerkas', 'berkas.verifier'])->findOrFail($id);
        $berkas = $student->berkas()->orderBy('uploaded_at', 'desc')->get();
        
        return view('admin.berkas.show', compact('student', 'berkas'));
    }

    public function verify(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:verified,rejected',
            'catatan_admin' => 'nullable|required_if:status,rejected'
        ]);

        $berkas = BerkasMahasiswa::findOrFail($id);

        DB::beginTransaction();
        try {
            $berkas->update([
                'status' => $request->status,
                'catatan_admin' => $request->catatan_admin,
                'verified_at' => now(),
                'verified_by' => auth_id()
            ]);

            ActivityLog::create([
                'user_id' => auth_id(),
                'username' => auth_user()['no_pendaftaran'],
                'activity_type' => 'verify_berkas',
                'description' => 'Admin verified berkas ID: ' . $berkas->id . ' as ' . $request->status,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            DB::commit();
            
            return redirect()->route('admin.berkas.show', $berkas->user_id)->with('success', 'Status berkas berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal verifikasi berkas: ' . $e->getMessage());
        }
    }
}
