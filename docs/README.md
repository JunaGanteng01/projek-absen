# Dokumentasi AbsensiQR

AbsensiQR adalah sistem kehadiran role-based untuk admin dan staff. QR terenkripsi berganti setiap 30 detik, hanya sekali pakai, dan scan diterima jika GPS berada dalam radius salah satu kantor aktif. Waktu keputusan selalu berasal dari server `Asia/Jakarta`.

## Fitur

- Login/session aman dan otorisasi admin/staff.
- Check-in/check-out QR, GPS Haversine, multi lokasi dan shift bertoleransi.
- Dashboard staff, riwayat, status waktu, dashboard admin, grafik, dan peta.
- CRUD staff, divisi, lokasi, shift; laporan rentang harian/mingguan/bulanan.
- Ekspor XLSX/PDF, CSRF, escaping output, validation, throttling, audit log.

Teknologi: PHP 8.2+, CodeIgniter 4, MySQL, Bootstrap 5, html5-qrcode, Leaflet/OpenStreetMap, Chart.js, PhpSpreadsheet, dan Dompdf.

Mulai dengan [INSTALLATION.md](INSTALLATION.md). Jalankan lokal melalui `php spark serve`, lalu buka `http://localhost:8080`.

Panduan identitas visual dan kelas komponen tersedia di [DESIGN_SYSTEM.md](DESIGN_SYSTEM.md).
