<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Profile;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\StudentsExport;
use Maatwebsite\Excel\Facades\Excel;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('profile')->where('role', 'user');

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('no_pendaftaran', 'like', "%{$search}%")
                  ->orWhereHas('profile', function($p) use ($search) {
                      $p->where('nama_lengkap', 'like', "%{$search}%")
                        ->orWhere('email_aktif', 'like', "%{$search}%")
                        ->orWhere('asal_sekolah', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->has('status') && $request->status != 'all') {
            $status = $request->status == 'complete' ? 1 : 0;
            $query->whereHas('profile', function($p) use ($status) {
                $p->where('is_complete', $status);
            });
        }

        // Pagination/Filter 10, 50, 100
        $perPage = $request->get('limit', 10);
        if (!in_array($perPage, [10, 50, 100])) {
            $perPage = 10;
        }

        $students = $query->orderBy('created_at', 'desc')->paginate($perPage)->appends($request->query());

        return view('admin.students.index', compact('students'));
    }

    public function show($id)
    {
        $user = User::with('profile')->where('role', 'user')->findOrFail($id);
        return view('admin.students.show', compact('user'));
    }

    public function edit($id)
    {
        $user = User::with('profile')->where('role', 'user')->findOrFail($id);
        return view('admin.students.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::where('role', 'user')->findOrFail($id);
        $profile = $user->profile;

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
            'program_studi' => 'nullable|string|max:100'
        ]);

        DB::beginTransaction();
        try {
            $profile->update([
                'nama_lengkap' => $request->nama_lengkap,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'asal_sekolah' => $request->asal_sekolah,
                'tahun_kelulusan' => $request->tahun_kelulusan,
                'alamat' => $request->alamat,
                'email_aktif' => $request->email_aktif,
                'no_hp_aktif' => $request->no_hp_aktif,
                'program_studi' => $request->program_studi,
            ]);

            // Re-check completeness
            $required_fields = ['nama_lengkap', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'asal_sekolah', 'tahun_kelulusan', 'alamat', 'email_aktif', 'no_hp_aktif'];
            $is_complete = true;
            foreach($required_fields as $field) {
                if (empty($profile->$field)) $is_complete = false;
            }
            $profile->update(['is_complete' => $is_complete]);

            ActivityLog::create([
                'user_id' => auth_id(),
                'username' => auth_user()['no_pendaftaran'],
                'activity_type' => 'admin_update_profile',
                'description' => 'Admin updated profile for: ' . $user->no_pendaftaran,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            DB::commit();
            return redirect()->route('admin.students.index')->with('success', 'Data profil berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal memperbarui profil: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $user = User::where('role', 'user')->findOrFail($id);
        
        DB::beginTransaction();
        try {
            // Delete associated profile (handled by cascade if set, but let's be explicit)
            if ($user->profile) {
                $user->profile->delete();
            }
            
            $reg_no = $user->no_pendaftaran;
            $user->delete();

            ActivityLog::create([
                'user_id' => auth_id(),
                'username' => auth_user()['no_pendaftaran'],
                'activity_type' => 'admin_delete_student',
                'description' => 'Admin deleted student and profile: ' . $reg_no,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);

            DB::commit();
            return redirect()->route('admin.students.index')->with('success', 'Data pendaftar berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function export(Request $request)
    {
        $search = $request->search;
        $status = $request->status;
        return Excel::download(new StudentsExport('user', $search, $status), 'data_pendaftar_'.date('Y-m-d').'.xlsx');
    }
}
