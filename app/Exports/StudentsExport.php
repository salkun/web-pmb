<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StudentsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $role;
    protected $search;
    protected $status;

    public function __construct($role = 'user', $search = null, $status = null)
    {
        $this->role = $role;
        $this->search = $search;
        $this->status = $status;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $query = User::with('profile')->where('role', $this->role);

        if ($this->search) {
            $search = $this->search;
            $query->where(function($q) use ($search) {
                $q->where('no_pendaftaran', 'like', "%{$search}%")
                  ->orWhereHas('profile', function($p) use ($search) {
                      $p->where('nama_lengkap', 'like', "%{$search}%")
                        ->orWhere('email_aktif', 'like', "%{$search}%")
                        ->orWhere('asal_sekolah', 'like', "%{$search}%");
                  });
            });
        }

        if ($this->status && $this->status != 'all') {
            $val = $this->status == 'complete' ? 1 : 0;
            $query->whereHas('profile', function($p) use ($val) {
                $p->where('is_complete', $val);
            });
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'No Pendaftaran',
            'Nama Lengkap',
            'Jenis Kelamin',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Asal Sekolah',
            'Tahun Kelulusan',
            'Alamat',
            'Email',
            'No HP',
            'Program Studi',
            'Status Data'
        ];
    }

    public function map($user): array
    {
        $profile = $user->profile;
        return [
            $user->id,
            $user->no_pendaftaran,
            $profile->nama_lengkap ?? '-',
            $profile->jenis_kelamin ?? '-',
            $profile->tempat_lahir ?? '-',
            $profile->tanggal_lahir ? $profile->tanggal_lahir->format('d/m/Y') : '-',
            $profile->asal_sekolah ?? '-',
            $profile->tahun_kelulusan ?? '-',
            $profile->alamat ?? '-',
            $profile->email_aktif ?? '-',
            $profile->no_hp_aktif ?? '-',
            $profile->program_studi ?? '-',
            ($profile->is_complete ?? false) ? 'Lengkap' : 'Belum Lengkap'
        ];
    }
}
