<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use App\Models\NotifUser;
use App\Services\ConvertAlamatService;
use Illuminate\Http\Request;

class HomePageController extends Controller
{
    protected $convertAlamat;

    public function __construct(ConvertAlamatService $convertAlamat)
    {
        $this->convertAlamat = $convertAlamat;
        $this->middleware('auth:api');
    }

    public function laporanTerdekat($late, $longe)
    {
        $lat = floatval($late);
        $long = floatval($longe);
        $radius = 6371;

        $laporanTerdekatInit = Laporan::selectRaw(
            "id,alamat_laporan, judul_laporan, deskripsi_laporan, lat, `long` as longitude, status_laporan,
            ($radius * acos(cos(radians(?)) * cos(radians(`lat`)) * cos(radians(`long`) - radians(?)) + sin(radians(?)) * sin(radians(`lat`)))) AS distance",
            [$lat, $long, $lat]
        )
            ->where('status_laporan', 'perlu-dukungan')
            ->having("distance", "<", 5)
            ->orderBy("distance", "asc")
            ->limit(4)
            ->with(['BuktiLaporan:id,laporan_id,bukti_laporan'])
            ->get();

        $mappedData = $laporanTerdekatInit->map(function ($item) {
            return [
                'id' => $item->id,
                'judul_laporan' => $item->judul_laporan,
                'deskripsi_laporan' => $item->deskripsi_laporan,
                'alamat_laporan' => $item->alamat_laporan,
                'status_laporan' => $item->status_laporan,
                'bukti_laporan' => $item->BuktiLaporan->isNotEmpty() ? $item->BuktiLaporan->first()->bukti_laporan : null,
                'pendukung' => $item->VoteLaporan ? $item->VoteLaporan->count() : 0,
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Laporan terdekat berhasil diambil',
            'data' => $mappedData
        ]);
    }


    public function name()
    {
        return response()->json([
            'success' => true,
            'message' => 'Nama user berhasil diambil',
            'data' => [
                'id' => auth()->user()->id,
                'name' => auth()->user()->nama
            ]
        ]);
    }

    public function notifUser()
    {
        $userInit = auth()->user();

        $initNotif = NotifUser::with('laporan')->where('user_id', $userInit->id)->where('is_read', false)->get();

        $countNotif = $initNotif->count();

        $initNotifAll = NotifUser::with('laporan')->where('user_id', $userInit->id)->get();

        if ($countNotif == 0) {

            $mappedData = $initNotifAll->map(function ($item) {
                return [
                    'id' => $item->id,
                    'laporan_id' => $item->laporan_id,
                    'judul_laporan' => $item->laporan ? $item->laporan->judul_laporan : null,
                    'deskripsi_laporan' => $item->laporan ? $item->laporan->deskripsi_laporan : null,
                    'status_laporan' => $item->status_laporan,
                    'tanggal_notif' => $item->created_at->format('Y-m-d H:i:s'),
                    'is_selected' => false
                ];
            });
            return response()->json([
                'success' => true,
                'message' => 'Tidak ada notifikasi, tampil kabeh notif',
                'data' => [
                    'jumlah_notif' => $countNotif,
                    'isi_notif' => $mappedData,
                ]
            ]);
        } else {
            $mappedData = $initNotifAll->map(function ($item) {
                return [
                    'id' => $item->id,
                    'laporan_id' => $item->laporan_id,
                    'judul_laporan' => $item->laporan ? $item->laporan->judul_laporan : null,
                    'deskripsi_laporan' => $item->laporan ? $item->laporan->deskripsi_laporan : null,
                    'status_laporan' => $item->status_laporan,
                    'tanggal_notif' => $item->created_at->format('Y-m-d H:i:s'),
                    'is_selected' => $item->is_read ? false : true
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Notifikasi berhasil diambil, if arep metu teko notif (navigator back or pushNamed nek spesify laporane), maka tembak api update syeh',
                'data' => [
                    'jumlah_notif' => $countNotif,
                    'isi_notif' => $mappedData,
                ]
            ]);
        }
    }

    public function notifUserPatch()
    {
        $userInit = auth()->user();

        $initNotif = NotifUser::with('laporan')->where('user_id', $userInit->id)->where('is_read', false)->get();

        $countNotif = $initNotif->count();

        $initNotifAll = NotifUser::with('laporan')->where('user_id', $userInit->id)->get();

        $initNotif->each(function ($item) {
            $item->is_read = true;
            $item->save();
        });

        $mappedData = $initNotifAll->map(function ($item) {
            return [
                'id' => $item->id,
                'laporan_id' => $item->laporan_id,
                'judul_laporan' => $item->laporan ? $item->laporan->judul_laporan : null,
                'deskripsi_laporan' => $item->laporan ? $item->laporan->deskripsi_laporan : null,
                'status_laporan' => $item->status_laporan,
                'tanggal_notif' => $item->created_at->format('Y-m-d H:i:s'),
                'is_selected' => false
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi berhasil diupdate',
            'data' => [
                'jumlah_notif' => $countNotif,
                'isi_notif' => $mappedData,
            ]
        ]);
    }
}
