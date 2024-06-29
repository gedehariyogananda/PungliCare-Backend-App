<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use Illuminate\Http\Request;

class PemantauanController extends Controller
{
    public function index()
    {
        $semuaDataLaporan = Laporan::all();
        $initialMarkers = $semuaDataLaporan->map(function ($laporan) {

            $latitude = (float) str_replace(',', '.', $laporan->lat);
            $longitude = (float) str_replace(',', '.', $laporan->long);

            // $alamat = $this->getAddressFromCoordinates($latitude, $longitude);
            $imagePath = asset('storage/' . $laporan->buktiLaporan[0]->bukti_laporan);

            return [
                'position' => [
                    'lat' => $latitude,
                    'lng' => $longitude,
                ],
                'draggable' => false,
                'judul_laporan' => $laporan->judul_laporan,
                'deskripsi_laporan' => $laporan->deskripsi_laporan,
                'image_laporan' => $imagePath,
                'status_laporan' => $laporan->status_laporan,
                'alamat' => $laporan->alamat_laporan,
                'icon_url' => $this->getMarkerIconUrl($laporan->status_laporan),
                'jumlahPendukung' => $laporan->VoteLaporan ? $laporan->VoteLaporan->count() : 0,
            ];
        });


        return view('pemantauan_page.index', compact('initialMarkers'));
    }


    private function getMarkerIconUrl($status_laporan)
    {
        switch ($status_laporan) {
            case 'perlu-dukungan':
                return '/images/pin-kuning.png';
            case 'perlu-diatasi':
                return '/images/pin-merah.png';
            case 'sedang-diatasi':
                return '/images/pin-biru.png';
            case 'sudah-teratasi':
                return '/images/pin-hijau.png';
            default:
                return '/images/default-pin.png';
        }
    }
}
