# Instalasi

1. Pasang PHP 8.2+ beserta ekstensi `intl`, `mbstring`, `mysqli`, `curl`, `zip`, `gd`, Composer, dan MySQL 8/MariaDB.
2. Jalankan `composer install`.
3. Buat database: `CREATE DATABASE attendance_qr CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;`. Alternatif cepat: impor `database/attendance_qr.sql` yang sudah memuat skema dan seed.
4. Salin `.env.example` menjadi `.env`; isi host, database, username, password, dan `app.baseURL`.
5. Jalankan `php spark key:generate`, lalu pastikan `encryption.key` pada `.env` unik. Jangan commit `.env`.
6. Jika tidak mengimpor dump, jalankan `php spark migrate` dan `php spark db:seed AttendanceSeeder`. Jangan menjalankan seeder berulang pada database yang sudah berisi akun seed.
7. Pastikan `writable/` dapat ditulis server web. Jalankan `php spark serve` untuk development.

Production: arahkan document root ke `public/`, set `CI_ENVIRONMENT=production`, aktifkan HTTPS, secure cookie, backup database, cron pembersihan token lama, dan ganti semua password seed. Kamera/GPS browser mensyaratkan HTTPS kecuali localhost.
