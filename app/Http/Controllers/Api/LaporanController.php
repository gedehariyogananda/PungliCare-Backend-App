<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BuktiLaporan;
use App\Models\CommentLaporan;
use App\Models\Laporan;
use App\Models\ReportLaporan;
use App\Models\VoteLaporan;
use App\Services\ConvertAlamatService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class LaporanController extends Controller
{

    protected $convertAlamat;
    protected $laporan;

    public function __construct(ConvertAlamatService $convertAlamat)
    {
        $this->convertAlamat = $convertAlamat;
        $this->laporan = Laporan::with('BuktiLaporan', 'ReportLaporan', 'NotifUser', 'VoteLaporan', 'CommentLaporan');
        $this->middleware('auth:api');
    }

    public function laporanReport($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pesan_report' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }


        try {
            $initLog = auth()->user()->id;
            $laporan = $this->laporan->where('id', $id)->first();

            if (!$laporan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Laporan tidak ditemukan'
                ], 404);
            }

            $reportLaporan = ReportLaporan::where('laporan_id', $id)->where('user_id', $initLog)->first();
            if ($reportLaporan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah melaporkan laporan ini'
                ], 400);
            }

            if ($laporan->user_id == $initLog) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak bisa melaporkan laporan sendiri'
                ], 400);
            }

            $successInsert = ReportLaporan::create([
                'laporan_id' => $id,
                'user_id' => $initLog,
                'pesan_report' => $request->pesan_report,
            ]);

            if ($successInsert) {
                return response()->json([
                    'success' => true,
                    'message' => 'Berhasil melaporkan laporan'
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getLaporan($id)
    {
        $laporan = $this->laporan->where('id', $id)->first();

        if (!$laporan) {
            return response()->json([
                'success' => false,
                'message' => 'Laporan tidak ditemukan'
            ], 404);
        }

        $is_laporan_sendiri = $laporan->user_id == auth()->user()->id;
        $buktiLaporan = $laporan->BuktiLaporan->map(function ($bukti) {
            // $is_video = strpos($bukti->bukti_laporan, '.mp4') ? true : false;
            $videoExtensions = ['mp4', 'avi', 'mkv', 'mov', 'wmv', 'flv', 'webm', 'mpeg', 'mpg', '3gp', 'ogg'];
            $fileExtension = strtolower(pathinfo($bukti->bukti_laporan, PATHINFO_EXTENSION));
            $is_video = in_array($fileExtension, $videoExtensions);

            return [
                'bukti_laporan' => $bukti->bukti_laporan,
                'is_video' => $is_video
            ];
        });

        $comments = $laporan->CommentLaporan->map(function ($comment) {

            $is_komentar_kapolsek = $comment->user_id == 1;

            return [
                'id' => $comment->id,
                'user_id' => $comment->user_id,
                'nama' => $comment->user->nama,
                'foto_profil' => $comment->user ? $comment->user->profile_photo_path : null,
                'comment' => $comment->comment_laporan,
                'tanggal_comment' => $comment->created_at->format('d M Y H:i:s'),
                'is_comment_dia_sendiri' => $comment->user_id == auth()->user()->id,
                'is_editted_comment' => $comment->updated_at != $comment->created_at,
                'kapolsek_bukti' => $comment->BuktiComment->isEmpty() ? null : $comment->BuktiComment->map(function ($bukti_kapolsek) {
                    return [
                        'bukti_kapolsek' => $bukti_kapolsek->bukti_comment,
                    ];
                }),
                'is_komentar_kapolsek' => $is_komentar_kapolsek
            ];
        });

        $comments = $comments->sortByDesc('is_komentar_kapolsek')->values()->all();

        $jumlah_pendukung = $laporan->VoteLaporan->count();
        $is_memberi_dukungan = $laporan->VoteLaporan->where('user_id', auth()->user()->id)->first();

        if ($is_memberi_dukungan) {
            return response()->json([
                'success' => true,
                'message' => 'Data laporan Berhasil diambil, (1)if komentar kapolsek true, maka sematno. (2)FITUR REPORT LAPORAN TIDAK ADA SYEH. (3)is_memberi dukungan == true, hidden button memberi dukungan. (4)is_comment_sendiri true iso hapus edit comment e dee',
                'data' => [
                    'id' => $laporan->id,
                    'judul_laporan' => $laporan->judul_laporan,
                    'deskripsi_laporan' => $laporan->deskripsi_laporan,
                    'alamat_laporan' => $laporan->alamat_laporan ? $laporan->alamat_laporan : null,
                    'status_laporan' => $laporan->status_laporan,
                    'pendukung' => $jumlah_pendukung,
                    'is_laporan_sendiri' => $is_laporan_sendiri,
                    'is_memberi_dukungan' => true,
                    'bukti_laporan' => $buktiLaporan,
                    'comments' => $comments,
                ]
            ]);
        } else {
            return response()->json([
                'success' => true,
                'message' => 'Data laporan Berhasil diambil, (1)if komentar kapolsek true, maka sematno.(2) FITUR REPORT LAPORAN TIDAK ADA SYEH. (3)Ngecek sek syeh,if is_laporan_sendiri == true maka fix hidden button memberi dukungan e. if is_laporan_sendiri false, dicek maneh if is_memberi_dukungan == false, baru murup button memberi dukungan e. tp lk true yo hidden. (4)is_comment_sendiri true iso hapus edit comment e dee',
                'data' => [
                    'id' => $laporan->id,
                    'judul_laporan' => $laporan->judul_laporan,
                    'deskripsi_laporan' => $laporan->deskripsi_laporan,
                    'alamat_laporan' => $laporan->alamat_laporan ? $laporan->alamat_laporan : null,
                    'status_laporan' => $laporan->status_laporan,
                    'pendukung' => $jumlah_pendukung,
                    'is_laporan_sendiri' => $is_laporan_sendiri,
                    'is_memberi_dukungan' => false,
                    'bukti_laporan' => $buktiLaporan,
                    'comments' => $comments,
                ]
            ]);
        }
    }

    public function beriDukungan($id, $lat, $long)
    {
        $initLog = auth()->user()->id;

        $laporan = $this->laporan->where('id', $id)->first();

        if (!$laporan) {
            return response()->json([
                'success' => false,
                'message' => 'Laporan tidak ditemukan'
            ], 404);
        }

        // pengecekan lat long user, jika jarak 5kilo jauh dari lat long laporan, maka tidak bisa memberi dukungan
        $radius = 6371;

        $laporanTerdekatInit = Laporan::selectRaw(
            "*, 
        ($radius * acos(cos(radians(?)) * cos(radians(`lat`)) * cos(radians(`long`) - radians(?)) + sin(radians(?)) * sin(radians(`lat`)))) AS distance",
            [$lat, $long, $lat]
        )
            ->having("distance", "<", 5)
            ->orderBy("distance", "asc")
            ->with('BuktiLaporan', 'ReportLaporan', 'NotifUser', 'VoteLaporan')
            ->where('status_laporan', 'perlu-dukungan')
            ->first();

        if (!$laporanTerdekatInit) {
            return response()->json([
                'success' => false,
                'message' => 'gabisa mendukung karena jauh'
            ], 404);
        }

        $is_laporan_sendiri = $laporan->user_id == $initLog;

        if ($is_laporan_sendiri) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak bisa memberi dukungan pada laporan sendiri'
            ], 400);
        }

        $is_memberi_dukungan = $laporan->VoteLaporan->where('user_id', $initLog)->first();

        if ($is_memberi_dukungan) {

            $initCountLaporan = VoteLaporan::where('laporan_id', $id)->count();
            if ($initCountLaporan == 50) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal membatalkan dukungan, karena sudah mencapai 50 dukungan sudah diproses admin masuk ke perlu-diatasi'
                ], 400);
            }

            $laporan->VoteLaporan()->where('user_id', $initLog)->delete();

            return response()->json([
                'success' => true,
                'message' => 'Berhasil membatalkan dukungan'
            ]);
        }

        $successInsert = $laporan->VoteLaporan()->create([
            'user_id' => $initLog,
        ]);


        if ($successInsert) {

            // if vote udah 50 otomatis update perlu-diatasi
            $selectCountVoteLaporan = VoteLaporan::where('laporan_id', $id)->count();
            if ($selectCountVoteLaporan >= 50) {
                $laporan->update([
                    'status_laporan' => 'perlu-diatasi'
                ]);

                // notif user
                $laporan->NotifUser()->create([
                    'user_id' => $laporan->user_id,
                    'status_laporan' => 'perlu-diatasi'
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Berhasil memberi dukungan'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Gagal memberi dukungan'
        ], 400);
    }

    public function commentLaporan($id)
    {
        $initLog = auth()->user()->id;

        $laporan = $this->laporan->where('id', $id)->first();

        $validator = Validator::make(request()->all(), [
            'comment_laporan' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        try {
            if (!$laporan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Laporan tidak ditemukan'
                ], 404);
            }

            $successInsert = $laporan->CommentLaporan()->create([
                'user_id' => $initLog,
                'comment_laporan' => request()->comment_laporan,
            ]);

            if ($successInsert) {
                return response()->json([
                    'success' => true,
                    'message' => 'Berhasil memberikan komentar pada laporan'
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function deleteCommentLaporan($id)
    {
        $initLog = auth()->user()->id;

        $comment = CommentLaporan::where('id', $id)->first();

        if (!$comment) {
            return response()->json([
                'success' => false,
                'message' => 'Komentar tidak ditemukan'
            ], 404);
        }

        if ($comment->user_id != $initLog) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak bisa menghapus komentar orang lain'
            ], 400);
        }

        $successDelete = $comment->delete();

        if ($successDelete) {
            return response()->json([
                'success' => true,
                'message' => 'Berhasil menghapus komentar'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Gagal menghapus komentar'
        ], 400);
    }

    public function updateCommentLaporan($id)
    {

        $initLog = auth()->user()->id;

        $comment = CommentLaporan::where('id', $id)->first();

        if (!$comment) {
            return response()->json([
                'success' => false,
                'message' => 'Komentar tidak ditemukan'
            ], 404);
        }

        if ($comment->user_id != $initLog) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak bisa mengubah komentar orang lain'
            ], 400);
        }

        $validator = Validator::make(request()->all(), [
            'comment_laporan' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        try {
            $successUpdate = $comment->update([
                'comment_laporan' => request()->comment_laporan,
            ]);

            if ($successUpdate) {
                return response()->json([
                    'success' => true,
                    'message' => 'Berhasil mengubah komentar'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah komentar'
            ], 400);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function laporanUmum($latitude, $longitude, $status_laporan)
    {
        $lat = floatval($latitude);
        $long = floatval($longitude);

        $radius = 6371;

        if ($status_laporan == 'semua-data-terdekat') {
            $laporanTerdekatInit = Laporan::selectRaw(
                "id,alamat_laporan, judul_laporan, deskripsi_laporan, lat, `long` as longitude, status_laporan,
                ($radius * acos(cos(radians(?)) * cos(radians(`lat`)) * cos(radians(`long`) - radians(?)) + sin(radians(?)) * sin(radians(`lat`)))) AS distance",
                [$lat, $long, $lat]
            )
                ->having("distance", "<", 5)
                ->orderBy("distance", "asc")
                ->with(['BuktiLaporan:id,laporan_id,bukti_laporan'])
                ->get();
        } else {
            $laporanTerdekatInit = Laporan::selectRaw(
                "id,alamat_laporan, judul_laporan, deskripsi_laporan, lat, `long` as longitude, status_laporan,
                    ($radius * acos(cos(radians(?)) * cos(radians(`lat`)) * cos(radians(`long`) - radians(?)) + sin(radians(?)) * sin(radians(`lat`)))) AS distance",
                [$lat, $long, $lat]
            )
                ->where('status_laporan', $status_laporan)
                ->having("distance", "<", 5)
                ->orderBy("distance", "asc")
                ->with(['BuktiLaporan:id,laporan_id,bukti_laporan'])
                ->where('status_laporan', $status_laporan)
                ->get();
        }

        if ($laporanTerdekatInit->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada laporan terdekat'
            ], 404);
        }

        $mappedData = $laporanTerdekatInit->map(function ($item) {
            return [
                'id' => $item->id,
                'judul_laporan' => $item->judul_laporan,
                'deskripsi_laporan' => $item->deskripsi_laporan,
                'alamat_laporan' => $item->alamat_laporan,
                'status_laporan' => $item->status_laporan,
                'bukti_laporan' => $item->BuktiLaporan ? $item->BuktiLaporan[0]->bukti_laporan : null,
                'pendukung' => $item->VoteLaporan ? $item->VoteLaporan->count() : 0,
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Laporan terdekat berhasil diambil. noted : (1) syeh gae dropdown isi string isi2 e yoiku : perlu-dukungan,perlu-diatasi,sedang-diatasi,sudah-teratasi,semua-data-terdekat',
            'data' => $mappedData
        ]);
    }

    public function laporanAnda($status_laporan)
    {
        $initLog = auth()->user()->id;

        if ($status_laporan == 'semua-data') {
            $laporanAnda = $this->laporan->where('user_id', $initLog)->get();
        } else {
            $laporanAnda = $this->laporan->where('user_id', $initLog)->where('status_laporan', $status_laporan)->get();
        }

        if ($laporanAnda->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada laporan'
            ], 404);
        }

        $mappedData = $laporanAnda->map(function ($item) {
            return [
                'id' => $item->id,
                'judul_laporan' => $item->judul_laporan,
                'deskripsi_laporan' => $item->deskripsi_laporan,
                'alamat_laporan' => $item->alamat_laporan ? $item->alamat_laporan : null,
                'status_laporan' => $item->status_laporan,
                'bukti_laporan' => $item->BuktiLaporan ? $item->BuktiLaporan[0]->bukti_laporan : null,
                'pendukung' => $item->VoteLaporan ? $item->VoteLaporan->count() : 0,
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Laporan anda berhasil diambil. noted : (1) syeh gae dropdown isi string isi2 e yoiku : perlu-dukungan,perlu-diatasi,sedang-diatasi,sudah-teratasi,semua-data',
            'data' => $mappedData
        ]);
    }

    public function allLaporan()
    {

        $laporans = $this->laporan->get();

        if ($laporans->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada laporan'
            ], 404);
        }

        $mappedData = $laporans->map(function ($laporan) {

            $latitude = (float) str_replace(',', '.', $laporan->lat);
            $longitude = (float) str_replace(',', '.', $laporan->long);

            $imagePath = asset('storage/' . $laporan->buktiLaporan[0]->bukti_laporan);

            return [
                'id' => $laporan->id,
                'lat' => $latitude,
                'long' => $longitude,
                'judul_laporan' => $laporan->judul_laporan,
                'deskripsi_laporan' => $laporan->deskripsi_laporan,
                'image_laporan' => $imagePath,
                'status_laporan' => $laporan->status_laporan,
                'alamat_laporan' => $laporan->alamat_laporan ? $laporan->alamat_laporan : null,
                'pendukung' => $laporan->VoteLaporan ? $laporan->VoteLaporan->count() : 0,
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Laporan semua di dunia berhasil diambil cuy',
            'data' => $mappedData
        ]);
    }

    public function buatLaporan($latitude, $longitude, Request $request)
    {
        $initLog = auth()->user()->id;

        // Validasi input
        $validator = Validator::make($request->all(), [
            'judul_laporan' => 'required|string',
            'deskripsi_laporan' => 'required|string',
            'bukti_laporan' => 'required|array',
            'bukti_laporan.*.bukti_laporan' => 'required|file',
            'alamat_laporan' => 'required|string',
            'lat' => 'required|numeric',
            'long' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        try {
            // Menghitung jarak antara dua koordinat
            $latFrom = deg2rad(floatval($latitude));
            $longFrom = deg2rad(floatval($longitude));
            $latTo = deg2rad(floatval($request->lat));
            $longTo = deg2rad(floatval($request->long));

            $earthRadius = 6371;

            $latDelta = $latTo - $latFrom;
            $longDelta = $longTo - $longFrom;

            $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                cos($latFrom) * cos($latTo) * pow(sin($longDelta / 2), 2)));
            $distance = $earthRadius * $angle;

            // Validasi jarak 5 kilometer
            if ($distance > 5) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jarak yang anda masukkan di laporan terlalu jauh dari lokasi anda'
                ], 400);
            }

            // Membuat laporan baru
            $laporan = Laporan::create([
                'user_id' => $initLog,
                'judul_laporan' => $request->judul_laporan,
                'deskripsi_laporan' => $request->deskripsi_laporan,
                'alamat_laporan' => $request->alamat_laporan,
                'lat' => $request->lat,
                'long' => $request->long,
            ]);

            $destinationPath = 'bukti-laporan/';
            foreach ($request->bukti_laporan as $bukti) {
                $file = $bukti['bukti_laporan'];
                $extension = $file->getClientOriginalExtension();
                $buktiLaporanName = time() . '-' . rand(1, 10) . '.' . $extension;

                if (strpos($file->getMimeType(), 'image') !== false) {
                    $compressedImage = Image::make($file->getRealPath());
                    if ($compressedImage->width() > 800) {
                        $compressedImage->resize(800, null, function ($constraint) {
                            $constraint->aspectRatio();
                        });
                    }

                    $extension = $file->getClientOriginalExtension();
                    if (in_array($extension, ['jpeg', 'jpg'])) {
                        $compressedImage->encode('jpg', 75);
                    } elseif (in_array($extension, ['png'])) {
                        $compressedImage->encode('png', 75);
                    } else {
                        $compressedImage->encode('jpg', 75);
                    }

                    $compressedImage->save(storage_path('app/public/' . $destinationPath . $buktiLaporanName));
                } elseif (strpos($file->getMimeType(), 'video') !== false) {
                    $tempPath = $file->storeAs('public/' . $destinationPath, 'temp-' . $buktiLaporanName);

                    FFMpeg::fromDisk('public')
                        ->open($destinationPath . 'temp-' . $buktiLaporanName)
                        ->export()
                        ->toDisk('public')
                        ->inFormat(new \FFMpeg\Format\Video\X264('libmp3lame', 'libx264'))
                        ->save($destinationPath . $buktiLaporanName);

                    Storage::disk('public')->delete($destinationPath . 'temp-' . $buktiLaporanName);
                }

                BuktiLaporan::create([
                    'laporan_id' => $laporan->id,
                    'bukti_laporan' => $destinationPath . $buktiLaporanName,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Berhasil membuat laporan'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function convertAlamat($latitude, $longitude)
    {
        $latitude = floatval($latitude);
        $longitude = floatval($longitude);

        $alamat = $this->convertAlamat->getAddressFromCoordinates($latitude, $longitude);

        return response()->json([
            'success' => true,
            'message' => 'lat long berhasil diproses dadi alamat',
            'data' => $alamat
        ]);
    }
}
