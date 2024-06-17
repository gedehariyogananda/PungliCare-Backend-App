<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $semuaLaporan = Laporan::count();
        $sudahDiatasi = Laporan::where('status_laporan', 'sudah-teratasi')->count();
        $perluDiatasi = Laporan::where('status_laporan', 'perlu-teratasi')->count();

        return view('dashboard_page.dashboard', compact('semuaLaporan', 'sudahDiatasi', 'perluDiatasi'));
    }

    
}
