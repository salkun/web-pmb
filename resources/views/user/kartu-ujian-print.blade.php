<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kartu Ujian - {{ $kartu->nomor_peserta }}</title>
    <style>
        * {
            font-family: Arial, sans-serif;
        }
        body {
            font-family: Arial, sans-serif;
            color: #000;
            font-size: 14px;
            margin: 0;
            padding: 20px;
            background-color: #fff;
        }
        @page {
            size: A4 portrait;
            margin: 1.5cm;
        }
        .wrapper {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            background: white;
        }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }
        
        /* Header */
        .header { margin-bottom: 25px; }
        .header h3 { font-size: 16px; margin: 3px 0; font-weight: bold; }
        .header h2 { font-size: 18px; margin: 3px 0; font-weight: bold; }
        .header h1 { font-size: 22px; margin: 15px 0 10px; font-weight: bold; }

        /* Two Column Layout */
        .info-section {
            display: flex; /* Fallback */
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .info-col { flex: 1; }
        .photo-col { width: 150px; margin-left: 20px; }

        /* Info Table */
        .info-table { width: 100%; border-collapse: collapse; }
        .info-table td {
            padding: 6px 0;
            vertical-align: top;
            font-size: 14px;
        }
        .label-col { width: 160px; }
        .sep-col { width: 15px; text-align: center; }
        .val-col { }

        /* Photo Frame */
        .photo-frame {
            width: 151px;
            height: 226px;
            border: 1px solid #ccc;
            background-color: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .photo-frame img { width: 100%; height: 100%; object-fit: cover; }

        /* Schedule Section */
        .schedule-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
            text-transform: uppercase;
            border-bottom: 2px solid #000;
            display: inline-block;
            padding-bottom: 2px;
        }
        .schedule-table { margin-bottom: 15px; }
        .schedule-table td { padding: 4px 0; font-size: 14px; }
        
        /* Rules Section */
        .rules-title {
            font-weight: bold;
            margin-top: 15px;
            margin-bottom: 8px;
            font-size: 14px;
        }
        .rules-list { margin: 0; padding-left: 20px; }
        .rules-list li { margin-bottom: 4px; font-size: 14px; }

        /* Print Controls */
        @media print {
            .no-print { display: none; }
            body { padding: 0; margin: 0; }
            .wrapper { max-width: 100%; width: 100%; }
        }
        .btn-print {
            padding: 10px 20px;
            background-color: #2563eb;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .btn-print:hover { background-color: #1d4ed8; }
    </style>
</head>
<body @if(!isset($is_pdf)) onload="window.print()" @endif>

    @php
        // Function to safely get image as base64 for PDF
        $getImageData = function($path) {
            if ($path && file_exists($path) && is_file($path)) {
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                return 'data:image/' . $type . ';base64,' . base64_encode($data);
            }
            return null;
        };

        if (isset($is_pdf)) {
            $root = $_SERVER['DOCUMENT_ROOT'];
            
            // Photo Path Discovery
            $foto_path = null;
            if ($profile->foto_profil) {
                $possible_foto_paths = [
                    $root . '/public/storage/' . $profile->foto_profil,
                    $root . '/storage/' . $profile->foto_profil,
                    base_path('../public/storage/' . $profile->foto_profil),
                    public_path('storage/' . $profile->foto_profil)
                ];
                foreach ($possible_foto_paths as $p) {
                    if ($data = $getImageData($p)) {
                        $foto_path = $data;
                        break;
                    }
                }
            }

            // Logo Path Discovery
            $logo_path = null;
            $possible_logo_paths = [
                $root . '/public/logo.png',
                $root . '/logo.png',
                public_path('logo.png')
            ];
            foreach ($possible_logo_paths as $p) {
                if ($data = $getImageData($p)) {
                    $logo_path = $data;
                    break;
                }
            }
        } else {
            $logo_path = asset('logo.png');
            $foto_path = $profile->foto_profil ? asset('storage/' . $profile->foto_profil) : null;
        }
    @endphp

    @if(!isset($is_pdf))
    <div class="no-print text-center">
        <button class="btn-print" onclick="window.print()">
            <span style="font-size: 16px;">🖨️ CETAK KARTU UJIAN</span>
        </button>
        <p style="color: #666; font-size: 12px; margin-top: 5px;">Gunakan kertas A4 dan orientasi Portrait</p>
    </div>
    @endif

    <div class="wrapper">
        <!-- Header -->
        <div class="header text-center">
            <h3>Kartu Test Potensi Akademik</h3>
            <h2>Akademi Teknik Radiologi Dr. Adji Saptogino</h2>
            <h1>KARTU UJIAN</h1>
        </div>

        <!-- Student Info & Photo -->
        <!-- Student Info & Photo -->
        <table style="width: 100%; margin-bottom: 30px; border-collapse: collapse;">
            <tr>
                <!-- Left Column: Info -->
                <td valign="top">
                    <table class="info-table">
                        <tr>
                            <td class="label-col">No. Id Pendaftaran</td>
                            <td class="sep-col">:</td>
                            <td class="val-col">{{ $kartu->nomor_peserta }}</td>
                        </tr>
                        <tr>
                            <td class="label-col">Tahun Akademik</td>
                            <td class="sep-col">:</td>
                            <td class="val-col">{{ $kartu->pengaturanUjian->tahun_akademik }}</td>
                        </tr>
                        <tr>
                            <td class="label-col">Nama</td>
                            <td class="sep-col">:</td>
                            <td class="val-col">{{ $profile->nama_lengkap }}</td>
                        </tr>
                        <tr>
                            <td class="label-col">Program Studi</td>
                            <td class="sep-col">:</td>
                            <td class="val-col">{{ $profile->program_studi }} Pencitraan</td>
                        </tr>
                    </table>
                </td>

                <!-- Right Column: Photo -->
                <td valign="top" align="right" style="width: 160px; padding-left: 20px;">
                    <div class="photo-frame">
                        @if($profile->foto_profil)
                            <img src="{{ $foto_path }}" alt="Pas Foto">
                        @else
                            <span style="color: #999; font-size: 12px;">FOTO 4x6</span>
                        @endif
                    </div>
                </td>
            </tr>
        </table>

        <!-- Schedule Section -->
        <div>
            <div class="schedule-title">JADWAL UJIAN</div>
            
            <div style="font-weight: bold; margin-bottom: 10px;">Tes Kemampuan Akademik</div>
            
            <table class="schedule-table">
                <tr>
                    <td style="width: 80px;">Tanggal</td>
                    <td style="width: 20px;" class="text-center">:</td>
                    <td>{{ \Carbon\Carbon::parse($kartu->pengaturanUjian->tanggal_ujian)->translatedFormat('l, d F Y') }}</td>
                </tr>
                <tr>
                    <td>Pukul</td>
                    <td class="text-center">:</td>
                    <td>{{ \Carbon\Carbon::parse($kartu->pengaturanUjian->waktu_mulai)->format('H.i') }} – {{ \Carbon\Carbon::parse($kartu->pengaturanUjian->waktu_selesai)->format('H.i') }} WIB</td>
                </tr>
                <tr>
                    <td style="vertical-align: top;">Tempat</td>
                    <td class="text-center" style="vertical-align: top;">:</td>
                    <td style="line-height: 1.5;">
                        Kampus ATRODAS (Luring/Offline)<br>
                        Laptop/HP masing” (Daring/Online)
                    </td>
                </tr>
            </table>
        </div>

        <!-- Instructions / Rules -->
        <div>
            <div class="rules-title">Tes Kemampuan Akademik dilaksanakan secara Online dengan ketentuan sebagai berikut:</div>
            <ol class="rules-list">
                <li>Peserta mencetak kartu ujian</li>
                <li>Peserta memperlihatkan kartu ujian sebelum ujian mulai</li>
                <li>Peserta menyiapkan Laptop/HP untuk mengerjakan soal</li>
                <li>Pastikan Akses Internet yang Baik/Stabil di tempat masing-masing</li>
                <li>Peserta sudah siap 30 Menit sebelum ujian dimulai</li>
                <li>Waktu yang digunakan adalah Waktu Indonesia Barat (WIB)</li>
                <li>Monitoring/Absensi Peserta ujian melalui Whatsapp (wajib mengaktifkan Audio dan Kamera HP selama ujian berlangsung)</li>
            </ol>

            <div class="rules-title">Tes Kemampuan Akademik dilaksanakan secara Offline dengan ketentuan sebagai berikut:</div>
            <ol class="rules-list">
                <li>Peserta mencetak kartu ujian</li>
                <li>Peserta sudah siap 30 Menit sebelum ujian dimulai</li>
                <li>Peserta memperlihatkan kartu ujian sebelum ujian mulai</li>
                <li>Waktu yang digunakan adalah Waktu Indonesia Barat (WIB)</li>
            </ol>
        </div>
    </div>

</body>
</html>
