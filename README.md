# AbsensiQR

Sistem absensi online berbasis CodeIgniter 4, QR dinamis 30 detik, GPS multi-kantor, dashboard, laporan Excel/PDF, dan audit log. Dokumentasi lengkap dimulai di [docs/README.md](docs/README.md).

```bash
composer install
php spark serve
```

Database lokal sudah dimigrasikan dan di-seed. Perintah `php spark migrate` serta `php spark db:seed AttendanceSeeder` hanya diperlukan untuk instalasi database baru.

Database MySQL lokal `attendance_qr` sudah dibuat dan `.env` sudah dikonfigurasi untuk `root` tanpa password. Untuk mesin lain, impor `database/attendance_qr.sql` atau jalankan migration + seeder, kemudian sesuaikan kredensial `.env`. Akun seed: `admin@example.com / Admin123!` dan `staff@example.com / Staff123!`; segera ganti untuk penggunaan nyata.
