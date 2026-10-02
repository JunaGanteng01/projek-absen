# Alur Absensi

```mermaid
flowchart TD
 A[Login Staff] --> B[Buka kamera html5-qrcode]
 B --> C[Browser membaca QR]
 C --> D[Browser meminta GPS]
 D --> E[POST token dan koordinat + CSRF]
 E --> F[Validasi session, rate limit, input]
 F --> G[Dekripsi, cek expiry, atomik consume token]
 G --> H[Haversine ke semua kantor aktif]
 H --> I{Dalam radius?}
 I -- Tidak --> X[Rollback dan tolak]
 I -- Ya --> J[Cek tanggal/jam server WIB]
 J --> K{Sudah check-in?}
 K -- Tidak --> L[Simpan check-in + status]
 K -- Ya --> M[Simpan check-out + status]
 L --> N[Audit log dan commit]
 M --> N
 N --> O[Update dashboard]
```

Transaksi database menyatukan konsumsi token dan penulisan attendance. Kegagalan pada tahap apa pun di dalam transaksi di-rollback. Record hari yang sudah memiliki checkout ditolak.

Klasifikasi masuk menggunakan dua batas. Sampai jam mulai Full Time + toleransi menghasilkan `Tepat Waktu / Full Time`. Setelah itu sampai batas mulai Part Time (default 14:00, inklusif) menghasilkan `Hadir / Part Time`, bukan terlambat. Setelah batas Part Time menghasilkan `Terlambat / Part Time`. Pulang sebelum jam pulang − toleransi adalah `Pulang Cepat`, hingga jam pulang `Pulang Normal`, sesudahnya `Lembur`.
