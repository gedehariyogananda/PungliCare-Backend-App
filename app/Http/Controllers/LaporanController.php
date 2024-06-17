<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index()
    {
        $laporans = Laporan::with('BuktiLaporan', 'ReportLaporan', 'VoteLaporan')->get();
        $semuaData = $laporans->map(function ($laporan) {

            $latitude = (float) str_replace(',', '.', $laporan->lat);
            $longitude = (float) str_replace(',', '.', $laporan->long);

            $alamat = $this->getAddressFromCoordinates($latitude, $longitude);
            $imagePath = asset('storage/' . $laporan->buktiLaporan[0]->bukti_laporan);

            return [
                'judul_laporan' => $laporan->judul_laporan,
                'deskripsi_laporan' => $laporan->deskripsi_laporan,
                'image_laporan' => $imagePath,
                'status_laporan' => $laporan->status_laporan,
                'alamat' => $alamat,
                'jumlahPendukung' => $laporan->VoteLaporan ? $laporan->VoteLaporan->count() : 0,
            ];
        });

        return view('laporan_page.index', compact('semuaData'));
    }

    // Fungsi untuk mendapatkan alamat dari koordinat
    private function getAddressFromCoordinates($latitude, $longitude)
    {
        // API endpoint dari OpenStreetMap Nominatim
        $apiUrl = "https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat={$latitude}&lon={$longitude}";

        // Membuat koneksi cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');

        // Eksekusi permintaan cURL
        $response = curl_exec($ch);
        curl_close($ch);

        // Decode respons JSON
        $data = json_decode($response, true);

        // Ambil alamat atau bagian yang sesuai dengan kebutuhan Anda
        $alamat = $data['display_name'] ?? '';

        return $alamat;
    }
}
