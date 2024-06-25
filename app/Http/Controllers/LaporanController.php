<?php

namespace App\Http\Controllers;

use App\Models\BuktiComment;
use App\Models\Laporan;
use App\Models\CommentLaporan;
use App\Models\ReportLaporan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

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
                'id' => $laporan->id,
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

    public function detail($id)
    {
        $laporan = Laporan::findOrFail($id)->with(['ReportLaporan', 'BuktiLaporan', 'NotifUser', 'VoteLaporan', 'CommentLaporan', 'User'])->first();

        $laporan->alamat = $this->getAddressFromCoordinates((float) str_replace(',', '.', $laporan->lat), (float) str_replace(',', '.', $laporan->long));

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

        return redirect()->route('laporan.detail', $id)->with('success', 'Status laporan berhasil diupdate.');
    }

    public function delete($id)
    {
        $laporan = Laporan::findOrFail($id);

        $laporan->delete();

        return redirect()->route('laporan')->with('success', 'Laporan berhasil dihapus.');
    }

    public function comment($id){
        $comments = CommentLaporan::where('laporan_id',$id)->with(['laporan','user','BuktiComment'])->get();
        $laporanId = $id;
        return view("laporan_page.comment", compact('comments','laporanId'));
    }

    public function createComment($id, Request $request){
        $validated = Validator::make($request->all(),[
            'comment_laporan' => 'required',
            'bukti_comments.*' => 'file|mimes:jpg,jpeg,png,bmp,gif,svg,webp,mp4,avi,mov,wmv,mkv|max:20480'
        ]);

        if($validated->fails()){
            return redirect()->back()->withErrors($validated)->withInput();
        }

        $comment = new CommentLaporan();
        $comment->laporan_id = $id;
        $comment->comment_laporan = $request->comment_laporan;
        $comment->user_id =auth()->id();
        $comment->save();

        if($request->hasFile('bukti_comments')){
            foreach($request->file('bukti_comments') as $file){
                $path = $file->store('bukti_comments', 'public');
                $buktiComment = new BuktiComment();
                $buktiComment->comment_laporan_id = $comment->id;
                $buktiComment->bukti_comment = $path;
                $buktiComment->save();
            }
        }

        return redirect()->back()->with('success', 'Sccess menambahkan komentar');

    }

    public function reports($id){
        $reports = ReportLaporan::with(['laporan', 'user'])->where('laporan_id', $id)->get();

        return view('laporan_page.report', compact('reports'));
    }
}
