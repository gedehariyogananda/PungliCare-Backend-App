-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 17 Jun 2024 pada 03.23
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `punglicare`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `bukti_comments`
--

CREATE TABLE `bukti_comments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `comment_laporan_id` bigint(20) UNSIGNED NOT NULL,
  `bukti_comment` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `bukti_comments`
--

INSERT INTO `bukti_comments` (`id`, `comment_laporan_id`, `bukti_comment`, `created_at`, `updated_at`) VALUES
(1, 4, 'bukti-comment/bukticomment1.2.png', '2024-06-16 05:09:53', '2024-06-16 05:09:53'),
(2, 4, 'bukti-comment/bukticomment1.1.jpg', '2024-06-16 05:09:53', '2024-06-16 05:09:53');

-- --------------------------------------------------------

--
-- Struktur dari tabel `bukti_laporans`
--

CREATE TABLE `bukti_laporans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `laporan_id` bigint(20) UNSIGNED NOT NULL,
  `bukti_laporan` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `bukti_laporans`
--

INSERT INTO `bukti_laporans` (`id`, `laporan_id`, `bukti_laporan`, `created_at`, `updated_at`) VALUES
(1, 1, 'bukti-laporan/laporan1.1.png', '2024-06-16 05:09:53', '2024-06-16 05:09:53'),
(2, 1, 'bukti-laporan/laporan1.2.mp4', '2024-06-16 05:09:53', '2024-06-16 05:09:53'),
(3, 2, 'bukti-laporan/laporan2.1.jpg', '2024-06-16 05:09:53', '2024-06-16 05:09:53'),
(4, 2, 'bukti-laporan/laporan2.2.mp4', '2024-06-16 05:09:53', '2024-06-16 05:09:53'),
(5, 3, 'bukti-laporan/laporan2.2.mp4', '2024-06-16 05:09:53', '2024-06-16 05:09:53'),
(6, 3, 'bukti-laporan/laporan2.1.jpg', '2024-06-16 05:09:53', '2024-06-16 05:09:53');

-- --------------------------------------------------------

--
-- Struktur dari tabel `comment_laporans`
--

CREATE TABLE `comment_laporans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `laporan_id` bigint(20) UNSIGNED NOT NULL,
  `comment_laporan` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `comment_laporans`
--

INSERT INTO `comment_laporans` (`id`, `user_id`, `laporan_id`, `comment_laporan`, `created_at`, `updated_at`) VALUES
(1, 5, 1, 'Saya setuju dengan laporan ini', '2024-06-16 05:09:53', '2024-06-16 05:09:53'),
(2, 4, 1, 'Sangat merugikan sekali', '2024-06-16 05:09:53', '2024-06-16 05:09:53'),
(3, 4, 2, 'Mohon segera tindak lanjutin pak polsek', '2024-06-16 05:09:53', '2024-06-16 05:09:53'),
(4, 1, 1, 'Update kasus terkini, telah berhasil kami tangkap dan sudah dihukumi penjara 2 tahun. Dengan ini, kasus ini dapat kami sampaikan sudah teratasi. terima kasih', '2024-06-16 05:09:53', '2024-06-16 05:09:53'),
(6, 2, 1, 'maleh berubah yes was eddited', '2024-06-16 13:32:17', '2024-06-16 13:40:22');

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `laporans`
--

CREATE TABLE `laporans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `lat` varchar(255) NOT NULL,
  `long` varchar(255) NOT NULL,
  `alamat` text DEFAULT NULL,
  `judul_laporan` varchar(255) NOT NULL,
  `deskripsi_laporan` text NOT NULL,
  `status_laporan` enum('perlu-dukungan','perlu-diatasi','sedang-diatasi','sudah-teratasi') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `laporans`
--

INSERT INTO `laporans` (`id`, `user_id`, `lat`, `long`, `alamat`, `judul_laporan`, `deskripsi_laporan`, `status_laporan`, `created_at`, `updated_at`) VALUES
(1, 2, '-7.2820549', '112.7834058', NULL, 'Parkir Ilegal Di Indomaret Sukolilo', 'Parkir Ilegal Di Indomaret Sukolilo sangat membuat saya risih, diminta 50.000 anjinggg', 'sudah-teratasi', '2024-06-16 05:09:53', '2024-06-16 05:09:53'),
(2, 3, '-7.2798067', '112.7894837', NULL, 'Parkir Ilegal Di Alfamart Sukolilo', 'Parkir Ilegal Di Indomaret Alfamart sangat membuat saya risih, diminta 50.000 anjinggg', 'perlu-dukungan', '2024-06-16 05:09:53', '2024-06-16 05:09:53'),
(3, 3, '-7.2926971', '112.8006417', NULL, 'Parkir Ilegal Di sukolilo regency Sukolilo', 'Parkir Ilegal Di sukolilo regency, ', 'perlu-dukungan', '2024-06-16 05:09:53', '2024-06-16 05:09:53');

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2021_01_24_083237_create_sessions_table', 1),
(6, '2024_06_16_014706_create_laporans_table', 1),
(7, '2024_06_16_015034_create_bukti_laporans_table', 1),
(8, '2024_06_16_015110_create_report_laporans_table', 1),
(9, '2024_06_16_015143_create_comment_laporans_table', 1),
(10, '2024_06_16_015207_create_vote_laporans_table', 1),
(11, '2024_06_16_015227_create_notif_users_table', 1),
(12, '2024_06_16_023009_create_bukti_comments_table', 1),
(13, '2024_06_16_135547_add_pesan_report_to_report_laporans_table', 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `notif_users`
--

CREATE TABLE `notif_users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `laporan_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `status_laporan` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `notif_users`
--

INSERT INTO `notif_users` (`id`, `laporan_id`, `user_id`, `is_read`, `status_laporan`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 1, 'perlu-dukungan', '2024-06-16 05:09:53', '2024-06-16 05:09:53'),
(2, 2, 3, 0, 'perlu-dukungan', '2024-06-16 05:09:53', '2024-06-16 05:09:53'),
(3, 3, 3, 0, 'perlu-dukungan', '2024-06-16 05:09:53', '2024-06-16 05:09:53'),
(5, 1, 2, 1, 'sedang-diatasi', '2024-06-16 12:54:39', '2024-06-16 12:54:39'),
(6, 1, 2, 1, 'sudah-teratasi', '2024-06-16 12:54:39', '2024-06-16 12:54:39');

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `report_laporans`
--

CREATE TABLE `report_laporans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `laporan_id` bigint(20) UNSIGNED NOT NULL,
  `pesan_report` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `report_laporans`
--

INSERT INTO `report_laporans` (`id`, `user_id`, `laporan_id`, `pesan_report`, `created_at`, `updated_at`) VALUES
(3, 2, 2, 'ini penipuan dick', '2024-06-16 07:57:55', '2024-06-16 07:57:55'),
(4, 2, 3, 'ini penipuan dick', '2024-06-16 07:58:26', '2024-06-16 07:58:26');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` text NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('3DB58WyGtr2WcyYyzuC2s9r6X4muOhqmlMlZGuEx', NULL, '127.0.0.1', 'PostmanRuntime/7.37.3', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZW9adTZPM0NobGVsRldFWmVBS2tLMG5kYm1hNlhYVWFVYmRPa3ViVCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1718573103),
('LFl5fBFV8vbYLS8pZJYnPzguQyEnWqizuIFgMmhr', NULL, '127.0.0.1', 'PostmanRuntime/7.37.3', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoibkdFWG5oc1oxcFpNSHJYRVRDbmNHNGN4emFxYU5Ub2hHTVlEbHlQdiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1718543772);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `nomor_telepon` varchar(255) NOT NULL,
  `kode_otp` varchar(255) DEFAULT NULL,
  `phone_number_verified_at` timestamp NULL DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `roles` enum('kapolsek','masyarakat-umum') NOT NULL DEFAULT 'masyarakat-umum',
  `remember_token` varchar(100) DEFAULT NULL,
  `profile_photo_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `nama`, `email`, `nomor_telepon`, `kode_otp`, `phone_number_verified_at`, `email_verified_at`, `password`, `roles`, `remember_token`, `profile_photo_path`, `created_at`, `updated_at`) VALUES
(1, 'kapolsek sukolilo', 'kapolsek@gmail.com', '083133737660', NULL, '2024-06-16 05:09:52', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'kapolsek', NULL, 'profile/poldajatim.png', '2024-06-16 05:09:53', '2024-06-16 05:09:53'),
(2, 'ari', 'ari@gmail.com', '083133737661', NULL, '2024-06-16 05:09:53', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'masyarakat-umum', NULL, NULL, '2024-06-16 05:09:53', '2024-06-16 05:09:53'),
(3, 'iqbal', 'iqbal@gmail.com', '083133737662', NULL, '2024-06-16 05:09:53', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'masyarakat-umum', NULL, NULL, '2024-06-16 05:09:53', '2024-06-16 05:09:53'),
(4, 'fikri', 'fikri@gmail.com', '083133737663', NULL, '2024-06-16 05:09:53', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'masyarakat-umum', NULL, NULL, '2024-06-16 05:09:53', '2024-06-16 05:09:53'),
(5, 'dika', 'dika@gmail.com', '083133737664', NULL, '2024-06-16 05:09:53', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'masyarakat-umum', NULL, NULL, '2024-06-16 05:09:53', '2024-06-16 05:09:53');

-- --------------------------------------------------------

--
-- Struktur dari tabel `vote_laporans`
--

CREATE TABLE `vote_laporans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `laporan_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `vote_laporans`
--

INSERT INTO `vote_laporans` (`id`, `user_id`, `laporan_id`, `created_at`, `updated_at`) VALUES
(3, 4, 2, '2024-06-16 05:09:53', '2024-06-16 05:09:53'),
(4, 5, 2, '2024-06-16 05:09:53', '2024-06-16 05:09:53'),
(9, 2, 2, '2024-06-16 14:21:30', '2024-06-16 14:21:30');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `bukti_comments`
--
ALTER TABLE `bukti_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bukti_comments_comment_laporan_id_foreign` (`comment_laporan_id`);

--
-- Indeks untuk tabel `bukti_laporans`
--
ALTER TABLE `bukti_laporans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bukti_laporans_laporan_id_foreign` (`laporan_id`);

--
-- Indeks untuk tabel `comment_laporans`
--
ALTER TABLE `comment_laporans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `comment_laporans_user_id_foreign` (`user_id`),
  ADD KEY `comment_laporans_laporan_id_foreign` (`laporan_id`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `laporans`
--
ALTER TABLE `laporans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `laporans_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `notif_users`
--
ALTER TABLE `notif_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notif_users_laporan_id_foreign` (`laporan_id`),
  ADD KEY `notif_users_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indeks untuk tabel `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indeks untuk tabel `report_laporans`
--
ALTER TABLE `report_laporans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `report_laporans_user_id_foreign` (`user_id`),
  ADD KEY `report_laporans_laporan_id_foreign` (`laporan_id`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_nomor_telepon_unique` (`nomor_telepon`);

--
-- Indeks untuk tabel `vote_laporans`
--
ALTER TABLE `vote_laporans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vote_laporans_user_id_foreign` (`user_id`),
  ADD KEY `vote_laporans_laporan_id_foreign` (`laporan_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `bukti_comments`
--
ALTER TABLE `bukti_comments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `bukti_laporans`
--
ALTER TABLE `bukti_laporans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `comment_laporans`
--
ALTER TABLE `comment_laporans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `laporans`
--
ALTER TABLE `laporans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `notif_users`
--
ALTER TABLE `notif_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `report_laporans`
--
ALTER TABLE `report_laporans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `vote_laporans`
--
ALTER TABLE `vote_laporans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `bukti_comments`
--
ALTER TABLE `bukti_comments`
  ADD CONSTRAINT `bukti_comments_comment_laporan_id_foreign` FOREIGN KEY (`comment_laporan_id`) REFERENCES `comment_laporans` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `bukti_laporans`
--
ALTER TABLE `bukti_laporans`
  ADD CONSTRAINT `bukti_laporans_laporan_id_foreign` FOREIGN KEY (`laporan_id`) REFERENCES `laporans` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `comment_laporans`
--
ALTER TABLE `comment_laporans`
  ADD CONSTRAINT `comment_laporans_laporan_id_foreign` FOREIGN KEY (`laporan_id`) REFERENCES `laporans` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comment_laporans_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `laporans`
--
ALTER TABLE `laporans`
  ADD CONSTRAINT `laporans_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `notif_users`
--
ALTER TABLE `notif_users`
  ADD CONSTRAINT `notif_users_laporan_id_foreign` FOREIGN KEY (`laporan_id`) REFERENCES `laporans` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notif_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `report_laporans`
--
ALTER TABLE `report_laporans`
  ADD CONSTRAINT `report_laporans_laporan_id_foreign` FOREIGN KEY (`laporan_id`) REFERENCES `laporans` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `report_laporans_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `vote_laporans`
--
ALTER TABLE `vote_laporans`
  ADD CONSTRAINT `vote_laporans_laporan_id_foreign` FOREIGN KEY (`laporan_id`) REFERENCES `laporans` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vote_laporans_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
