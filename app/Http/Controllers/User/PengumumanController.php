<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Kelulusan;
use App\Models\KartuUjian;
use Illuminate\Http\Request;

class PengumumanController extends Controller
{
    public function index()
    {
        $user_id = auth_id();
        $user = \App\Models\User::with('profile')->find($user_id);
        $kartu = KartuUjian::where('user_id', $user_id)->first();
        $kelulusan = Kelulusan::with('pengaturanUjian')->where('user_id', $user_id)->first();

        return view('user.pengumuman', compact('user', 'kartu', 'kelulusan'));
    }
}
