<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Profile;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('profile');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('no_pendaftaran', 'like', "%{$search}%")
                  ->orWhereHas('profile', function($p) use ($search) {
                      $p->where('nama_lengkap', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->has('role') && $request->role != 'all') {
            $query->where('role', $request->role);
        }

        // Per page filter
        $perPage = $request->get('per_page', 10);
        if ($perPage == 'all') {
            $users = $query->orderBy('created_at', 'desc')->get();
            // Convert to paginator for compatibility
            $users = new \Illuminate\Pagination\LengthAwarePaginator(
                $users,
                $users->count(),
                $users->count(),
                1,
                ['path' => $request->url(), 'query' => $request->query()]
            );
        } else {
            $users = $query->orderBy('created_at', 'desc')->paginate($perPage)->appends($request->query());
        }

        return view('admin.users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_pendaftaran' => 'required|min:5|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,user',
            'email' => 'nullable|email'
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'no_pendaftaran' => $request->no_pendaftaran,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'is_active' => true
            ]);

            if ($request->role == 'user') {
                Profile::create([
                    'user_id' => $user->id,
                    'nama_lengkap' => $request->nama_lengkap ?? $user->no_pendaftaran,
                    'email_aktif' => $request->email ?? '-',
                    'is_complete' => false,
                    // Add default dummy values for required fields
                    'tempat_lahir' => '-', 
                    'tanggal_lahir' => '2000-01-01', // Default dummy date
                    'jenis_kelamin' => 'L', 
                    'alamat' => '-',
                    'no_hp_aktif' => '-',
                    'asal_sekolah' => '-',
                    'tahun_kelulusan' => date('Y')
                ]);
            }

            ActivityLog::create([
                'user_id' => auth_id(),
                'username' => auth_user()['no_pendaftaran'],
                'activity_type' => 'create_user',
                'description' => 'Created user: ' . $user->no_pendaftaran,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            DB::commit();
            return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal menambah user: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'no_pendaftaran' => 'required|min:5|unique:users,no_pendaftaran,' . $id,
            'role' => 'required|in:admin,user',
            'password' => 'nullable|min:6'
        ]);

        $data = [
            'no_pendaftaran' => $request->no_pendaftaran,
            'role' => $request->role,
            'is_active' => $request->has('is_active')
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        
        return back()->with('success', 'Data user berhasil diperbarui.');
    }

    public function destroy($id)
    {
        if ($id == auth_id()) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        $user = User::findOrFail($id);
        $user->delete();

        return back()->with('success', 'User berhasil dihapus.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx,xls'
        ]);

        try {
            // Use Maatwebsite Excel Import
            // Make sure to run: composer require maatwebsite/excel
            \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\UsersImport, $request->file('file'));
            
            return back()->with('success', 'User berhasil diimport.');

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
             $failures = $e->failures();
             $messages = "";
             foreach ($failures as $failure) {
                 $messages .= "Row " . $failure->row() . ": " . implode(', ', $failure->errors()) . ". ";
             }
             return back()->with('error', 'Gagal validasi import: ' . $messages);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }
}
