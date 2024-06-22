<?php

namespace Database\Seeders;

use App\Models\BuktiComment;
use App\Models\BuktiLaporan;
use App\Models\CommentLaporan;
use App\Models\Laporan;
use App\Models\NotifUser;
use App\Models\ReportLaporan;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\VoteLaporan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'nama' => 'kapolsek sukolilo',
            'email' => 'kapolsek@gmail.com',
            'nomor_telepon' => '083133737660',
            'phone_number_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'roles' => 'kapolsek',
            'profile_photo_path' => 'profile/poldajatim.png',
        ]);

        User::create([
            'nama' => 'ari',
            'email' => 'ari@gmail.com',
            'nomor_telepon' => '083133737661',
            'phone_number_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'roles' => 'masyarakat-umum',
        ]);

        User::create([
            'nama' => 'iqbal',
            'email' => 'iqbal@gmail.com',
            'nomor_telepon' => '083133737662',
            'phone_number_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'roles' => 'masyarakat-umum',
        ]);

        User::create([
            'nama' => 'fikri',
            'email' => 'fikri@gmail.com',
            'nomor_telepon' => '083133737663',
            'phone_number_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'roles' => 'masyarakat-umum',
        ]);

        User::create([
            'nama' => 'dika',
            'email' => 'dika@gmail.com',
            'nomor_telepon' => '083133737664',
            'phone_number_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'roles' => 'masyarakat-umum',
        ]);

        Laporan::create([
            'user_id' => 2,
            'lat' => "-7.2820549",
            'long' => "112.7834058",
            'judul_laporan' => "Parkir Ilegal Di Indomaret Sukolilo",
            'deskripsi_laporan' => "Parkir Ilegal Di Indomaret Sukolilo sangat membuat saya risih, diminta 50.000 anjinggg",
            'status_laporan' => "perlu-dukungan",
            'alamat_laporan' => "Kertajaya Indah Regency 2, RW 02, Gebang Putih, Sukolilo, Surabaya, Jawa Timur, Jawa, 60111, Indonesia"
        ]);

        Laporan::create([
            'user_id' => 3,
            'lat' => "-7.2798067",
            'long' => "112.7894837",
            'judul_laporan' => "Parkir Ilegal Di Alfamart Sukolilo",
            'deskripsi_laporan' => "Parkir Ilegal Di Indomaret Alfamart sangat membuat saya risih, diminta 50.000 anjinggg",
            'status_laporan' => "perlu-dukungan",
            'alamat_laporan' => "Kertajaya Indah Regency, RW 01, Gebang Putih, Sukolilo, Surabaya, Jawa Timur, Jawa, 60111, Indonesia"
        ]);

        Laporan::create([
            'user_id' => 3,
            'lat' => "-7.2926971",
            'long' => "112.8006417",
            'judul_laporan' => "Parkir Ilegal Di sukolilo regency Sukolilo",
            'deskripsi_laporan' => "Parkir Ilegal Di sukolilo regency, ",
            'status_laporan' => "perlu-dukungan",
            'alamat_laporan' => "SMK Negeri 10 Surabaya, Jalan Keputih Gang III E, RW 02, Keputih, Sukolilo, Surabaya, Jawa Timur, Jawa, 60111, Indonesia"
        ]);

        NotifUser::create([
            'user_id' => 2,
            'laporan_id' => 1,
            'status_laporan' => 'perlu-dukungan'
        ]);

        NotifUser::create([
            'user_id' => 3,
            'laporan_id' => 2,
            'status_laporan' => 'perlu-dukungan'
        ]);

        NotifUser::create([
            'user_id' => 3,
            'laporan_id' => 3,
            'status_laporan' => 'perlu-dukungan'
        ]);


        BuktiLaporan::create([
            'laporan_id' => 1,
            'bukti_laporan' => "bukti-laporan/laporan1.1.png",
        ]);

        BuktiLaporan::create([
            'laporan_id' => 1,
            'bukti_laporan' => "bukti-laporan/laporan1.2.mp4",
        ]);

        BuktiLaporan::create([
            'laporan_id' => 2,
            'bukti_laporan' => "bukti-laporan/laporan2.1.jpg",
        ]);

        BuktiLaporan::create([
            'laporan_id' => 2,
            'bukti_laporan' => "bukti-laporan/laporan2.2.mp4",
        ]);

        BuktiLaporan::create([
            'laporan_id' => 3,
            'bukti_laporan' => "bukti-laporan/laporan2.1.jpg",
        ]);

        BuktiLaporan::create([
            'laporan_id' => 3,
            'bukti_laporan' => "bukti-laporan/laporan2.2.mp4",
        ]);

        VoteLaporan::create([
            'user_id' => 3,
            'laporan_id' => 1,
        ]);

        VoteLaporan::create([
            'user_id' => 4,
            'laporan_id' => 2,
        ]);

        VoteLaporan::create([
            'user_id' => 5,
            'laporan_id' => 2,
        ]);

        CommentLaporan::create([
            'user_id' => 5,
            'laporan_id' => 1,
            'comment_laporan' => "Saya setuju dengan laporan ini",
        ]);

        CommentLaporan::create([
            'user_id' => 4,
            'laporan_id' => 1,
            'comment_laporan' => "Sangat merugikan sekali",
        ]);

        CommentLaporan::create([
            'user_id' => 4,
            'laporan_id' => 2,
            'comment_laporan' => "Mohon segera tindak lanjutin pak polsek",
        ]);

        CommentLaporan::create([
            'user_id' => 1,
            'laporan_id' => 1,
            'comment_laporan' => "Update kasus terkini, telah berhasil kami tangkap dan sudah dihukumi penjara 2 tahun. Dengan ini, kasus ini dapat kami sampaikan sudah teratasi. terima kasih",
        ]);

        BuktiComment::create([
            'comment_laporan_id' => 4,
            'bukti_comment' => "bukti-comment/bukticomment1.2.png",
        ]);

        BuktiComment::create([
            'comment_laporan_id' => 4,
            'bukti_comment' => "bukti-comment/bukticomment1.1.jpg",
        ]);


        //----------------------user 1-51 lalu vote ke laporan 1 semua masing2 user id -------------------// 
        for ($i = 1; $i <= 48; $i++) {
            User::create([
                'nama' => 'user' . $i,
                'email' => 'user' . $i . '@gmail.com',
                'nomor_telepon' => '0831337376' . $i,
                'phone_number_verified_at' => now(),
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'roles' => 'masyarakat-umum',
            ]);

            VoteLaporan::create([
                'user_id' => $i + 5,
                'laporan_id' => 1,
            ]);
        }

        // --------------- already done dummyy ---------------------------------- //
    }
}
