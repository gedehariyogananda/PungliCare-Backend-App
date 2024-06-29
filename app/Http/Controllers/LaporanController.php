<?php

namespace App\Http\Controllers;

use App\Exports\LaporanExport;
use App\Models\BuktiComment;
use App\Models\Laporan;
use App\Models\CommentLaporan;
use App\Models\ReportLaporan;
use App\Models\VoteLaporan;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        if ($request->query('action') == 'export') {
            return $this->exportToCSV($request);
        }
        $query = Laporan::with('BuktiLaporan', 'ReportLaporan', 'VoteLaporan');

        if ($request->has('status') && $request->query('status') != '') {
            $query->where('status_laporan', $request->query('status'));
        }

        if ($request->has('start_date') && $request->query('start_date') != '') {
            $query->where('created_at', '>=', $request->query('start_date'));
        }

        if ($request->has('end_date') && $request->query('end_date') != '') {
            $query->where('created_at', '<=', $request->query('end_date'));
        }

        $laporans = $query->get();

        $semuaData = $laporans->map(function ($laporan) {

            // $latitude = (float) str_replace(',', '.', $laporan->lat);
            // $longitude = (float) str_replace(',', '.', $laporan->long);

            // $alamat = $this->getAddressFromCoordinates($latitude, $longitude);
            $imagePath = asset('storage/' . $laporan->buktiLaporan[0]->bukti_laporan);

            return [
                'id' => $laporan->id,
                'judul_laporan' => $laporan->judul_laporan,
                'deskripsi_laporan' => $laporan->deskripsi_laporan,
                'image_laporan' => $imagePath,
                'status_laporan' => $laporan->status_laporan,
                'alamat' => $laporan->alamat_laporan,
                'jumlahPendukung' => $laporan->VoteLaporan ? $laporan->VoteLaporan->count() : 0,
            ];
        });

        return view('laporan_page.index', compact('semuaData'));
    }

    // Fungsi untuk mendapatkan alamat dari koordinat
    // private function getAddressFromCoordinates($latitude, $longitude)
    // {
    //     // API endpoint dari OpenStreetMap Nominatim
    //     $apiUrl = "https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat={$latitude}&lon={$longitude}";

    //     // Membuat koneksi cURL
    //     $ch = curl_init();
    //     curl_setopt($ch, CURLOPT_URL, $apiUrl);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');

    //     // Eksekusi permintaan cURL
    //     $response = curl_exec($ch);
    //     curl_close($ch);

    //     // Decode respons JSON
    //     $data = json_decode($response, true);

    //     // Ambil alamat atau bagian yang sesuai dengan kebutuhan Anda
    //     $alamat = $data['display_name'] ?? '';

    //     return $alamat;
    // }

    public function detail($id)
    {
        // $laporan = Laporan::findOrFail($id)->with(['ReportLaporan', 'BuktiLaporan', 'NotifUser', 'VoteLaporan', 'CommentLaporan', 'User'])->first();

        $laporan = Laporan::with(['ReportLaporan', 'BuktiLaporan', 'NotifUser', 'VoteLaporan', 'CommentLaporan', 'User'])->where('id', $id)->first();
        // $laporan->alamat = $this->getAddressFromCoordinates((float) str_replace(',', '.', $laporan->lat), (float) str_replace(',', '.', $laporan->long));

        return view('laporan_page.detail', compact('laporan'));
    }

    public function update($id, Request $request)
    {

        $laporan = Laporan::findOrFail($id);

        $validated = Validator::make($request->all(), [
            'status_laporan' => 'required|in:sedang-diatasi,sudah-teratasi'
        ]);

        if ($validated->fails()) {
            return redirect()->back()->withErrors($validated)->withInput();
        }

        $laporan->status_laporan = $request->input('status_laporan');

        $laporan->save();

        if ($laporan->status_laporan == 'sudah-teratasi') {
            $laporan->update([
                'tanggal_teratasi' => now()
            ]);
        }

        return redirect()->route('laporan.detail', $id)->with('success', 'Status laporan berhasil diupdate.');
    }

    public function delete($id)
    {
        $laporan = Laporan::findOrFail($id);

        $laporan->delete();

        return redirect()->route('laporan')->with('success', 'Laporan berhasil dihapus.');
    }

    public function comment($id)
    {
        $comments = CommentLaporan::where('laporan_id', $id)->with(['laporan', 'user', 'BuktiComment'])->get();
        $laporanId = $id;
        return view("laporan_page.comment", compact('comments', 'laporanId'));
    }

    public function createComment($id, Request $request)
    {
        // return response()->json($request->query('action'));
        $validated = Validator::make($request->all(), [
            'comment_laporan' => 'required',
            'bukti_comments.*' => 'file|mimes:jpg,jpeg,png,bmp,gif,svg,webp,mp4,avi,mov,wmv,mkv|max:20480'
        ]);

        if ($validated->fails()) {
            return redirect()->back()->withErrors($validated)->withInput();
        }

        $comment = new CommentLaporan();
        $comment->laporan_id = $id;
        $comment->comment_laporan = $request->comment_laporan;
        $comment->user_id = auth()->id();
        $comment->save();

        if ($request->hasFile('bukti_comments')) {
            foreach ($request->file('bukti_comments') as $file) {
                // Store the file and get its path
                $path = $file->store('bukti_comments', 'public');

                // Create a new BuktiComment entry
                $buktiComment = new BuktiComment();
                $buktiComment->comment_laporan_id = $comment->id;
                $buktiComment->bukti_comment = $path;
                $buktiComment->save();
            }
        }

        return redirect()->back()->with('success', 'Sucess menambahkan komentar');
    }

    public function reports($id)
    {
        $reports = ReportLaporan::with(['laporan', 'user'])->where('laporan_id', $id)->get();

        return view('laporan_page.report', compact('reports'));
    }

    public function pendukung($id)
    {
        $pendukungs = VoteLaporan::with(['laporan', 'user'])->where('laporan_id', $id)->get();
        // dd($pendukungs);
        // $pendukung = $laporan->VoteLaporan->map(function ($vote) {
        //     return [
        //         'id' => $vote->id,
        //         'user_id' => $vote->user_id,
        //         'user_name' => $vote->user->nama,
        //         'user_email' => $vote->user->email
        //     ];
        // });

        return view('laporan_page.pendukung', compact('pendukungs'));
    }

    public function exportToCSV(Request $request)
    {
        $status = $request->input('status');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        // return response()->json($start_date);
        return Excel::download(new LaporanExport($status, $start_date, $end_date), 'laporan.xlsx');
    }
}
