# Panduan Admin

1. Login dengan akun admin dan segera ganti password seed di database/fitur akun yang dikembangkan organisasi.
2. Buat divisi, kemudian shift (format `HH:MM`), lokasi kantor (latitude, longitude, radius), lalu staff. Shift default memakai batas Full Time 10:00, batas Part Time 14:00, dan pulang 18:00. Isi ID divisi/shift yang tersedia.
3. Buka menu QR di perangkat layar kantor. QR otomatis diperbarui tiap 30 detik.
4. Dashboard menampilkan hadir, terlambat, belum hadir, bekerja, pulang, lembur, grafik tujuh hari dan peta. Gunakan filter tanggal/divisi.
5. Laporan: pilih rentang tanggal untuk harian (tanggal sama), mingguan (7 hari), atau bulanan. Satu Excel menggabungkan Full Time dan Part Time, berformat A4 landscape, mengulang header, dan muat satu halaman lebar agar mudah dicetak.
6. Edit data melalui tombol Edit, atau soft-delete melalui Hapus. Perubahan dicatat di `audit_logs`.

Untuk lokasi akurat, ambil koordinat dari survei lokasi dan uji dari batas gedung. Jangan membesarkan radius tanpa persetujuan kebijakan HR.
