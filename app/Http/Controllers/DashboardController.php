<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $semuaLaporan = Laporan::count();
        $sudahDiatasi = Laporan::where('status_laporan', 'sudah-teratasi')->count();
        $perluDiatasi = Laporan::where('status_laporan', 'perlu-diatasi')->count();
        $sedangDiatasi = Laporan::where('status_laporan', 'sedang-diatasi')->count();
        $perluDukungan = Laporan::where('status_laporan', 'perlu-dukungan')->count();

        $laporan = Laporan::with('BuktiLaporan')->where('status_laporan', 'perlu-diatasi')->get();
        // $laporan=$laporan->BuktiLaporan;
        
        $laporanPerBulan = Laporan::selectRaw('COUNT(*) as jumlah_laporan, MONTH(created_at) as bulan')
            ->groupBy('bulan')
            ->get()
            ->keyBy('bulan');

        // Buat koleksi bulan dengan inisialisasi 0 untuk jumlah laporan
        $months = collect([
            ['bulan' => 1, 'nama_bulan' => 'Januari', 'jumlah_laporan' => 0],
            ['bulan' => 2, 'nama_bulan' => 'Februari', 'jumlah_laporan' => 0],
            ['bulan' => 3, 'nama_bulan' => 'Maret', 'jumlah_laporan' => 0],
            ['bulan' => 4, 'nama_bulan' => 'April', 'jumlah_laporan' => 0],
            ['bulan' => 5, 'nama_bulan' => 'Mei', 'jumlah_laporan' => 0],
            ['bulan' => 6, 'nama_bulan' => 'Juni', 'jumlah_laporan' => 0],
            ['bulan' => 7, 'nama_bulan' => 'Juli', 'jumlah_laporan' => 0],
            ['bulan' => 8, 'nama_bulan' => 'Agustus', 'jumlah_laporan' => 0],
            ['bulan' => 9, 'nama_bulan' => 'September', 'jumlah_laporan' => 0],
            ['bulan' => 10, 'nama_bulan' => 'Oktober', 'jumlah_laporan' => 0],
            ['bulan' => 11, 'nama_bulan' => 'November', 'jumlah_laporan' => 0],
            ['bulan' => 12, 'nama_bulan' => 'Desember', 'jumlah_laporan' => 0],
        ]);

        // Merge hasil query dengan koleksi bulan
        $months = $months->map(function ($month) use ($laporanPerBulan) {
            if (isset($laporanPerBulan[$month['bulan']])) {
                $month['jumlah_laporan'] = $laporanPerBulan[$month['bulan']]->jumlah_laporan;
            } else {
                $month['jumlah_laporan'] = 0; // Jika tidak ada laporan, set jumlah_laporan menjadi 0
            }
            return $month;
        })->filter(function ($month) {
            return $month['bulan'] !== null && $month['bulan'] !== '';
        });

        // return response()->json($laporan);
        return view('dashboard_page.dashboard', compact('semuaLaporan', 'sudahDiatasi', 'perluDiatasi', 'months', 'perluDukungan', 'sedangDiatasi','laporan'));
    }


}
