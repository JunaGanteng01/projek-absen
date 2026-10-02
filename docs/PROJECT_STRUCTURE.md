# Struktur Proyek

- `app/Config`: route, filter global CSRF/secure headers, timezone.
- `app/Controllers`: adapter HTTP; tipis, validasi input, panggil service/model, bentuk response.
- `app/Filters`: autentikasi, role, dan rate limit.
- `app/Models`: whitelist field dan operasi database melalui Query Builder/prepared statement.
- `app/Services`: aturan domain QR, GPS, absensi, dan audit.
- `app/Database/Migrations`: skema versioned; `Seeds`: data awal.
- `app/Views`: layout, login, staff, admin, CRUD, laporan/PDF.
- `public/assets`: CSS publik; `public/index.php` satu-satunya entrypoint web.
- `writable`: cache, log, session, dan file sementara; jangan dipublikasikan.
- `tests`: pengujian otomatis. `docs`: dokumentasi arsitektur dan penggunaan.

Controller tidak menghitung jarak atau status waktu. Aturan tersebut sengaja ditempatkan pada service agar dapat diuji dan digunakan ulang.
