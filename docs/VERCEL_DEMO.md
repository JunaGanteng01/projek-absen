# Publish demo tampilan ke Vercel

Folder `demo/` berisi HTML, CSS, JavaScript, dan gambar. Semua data adalah fixture contoh; demo tidak memakai PHP, database, login nyata, kamera, atau GPS.

1. Login ke https://vercel.com menggunakan GitHub.
2. Pilih Add New → Project → import `JunaGanteng01/projek-absen`.
3. Framework Preset: Other. Root Directory: folder utama repository (jangan pilih public).
4. Konfigurasi `vercel.json` menetapkan Output Directory `demo`, Build Command kosong, dan Install Command kosong. Jika ada override lama, hapus atau sesuaikan.
5. Tidak perlu Environment Variables. Hapus konfigurasi runtime PHP lama jika sebelumnya ditambahkan.
6. Klik Deploy, lalu buka domain deployment. Pilih Dashboard Admin atau Dashboard Staff.
7. Untuk project Vercel yang sudah ada, pastikan konfigurasi sama dan lakukan Redeploy dari commit terbaru.

## Memperbarui tampilan

Setelah mengubah view aplikasi, jalankan `php tools/build-demo.php` secara lokal, lalu commit dan push perubahan pada `demo/`. Generator tidak memuat `.env` atau membaca database. File JavaScript demo berada di `demo/assets/js/demo.js` dan dipertahankan ketika build ulang.

Untuk preview lokal: `php -S localhost:8090 -t demo tools/demo-router.php`.

Halaman: `/`, `/admin/dashboard`, `/admin/qr`, `/admin/reports`, `/admin/staff`, `/admin/departments`, `/admin/locations`, `/admin/shifts`, `/staff/dashboard`, `/staff/history`.

Tindakan simpan, hapus, scan, filter, dan ekspor hanya menampilkan pemberitahuan demo. Grafik, peta, QR, dan angka adalah ilustrasi. Dependency font dan Bootstrap dimuat dari CDN, sehingga memerlukan internet.
