# Validasi GPS

`navigator.geolocation.getCurrentPosition()` diminta setelah kamera membaca QR. Aplikasi mengirim latitude/longitude; keputusan tidak dilakukan di JavaScript. `LocationService` mengambil semua kantor aktif, menghitung jarak ke setiap titik, memilih yang terdekat, lalu membandingkannya dengan `radius_meters` milik kantor itu.

Rumus Haversine:

```text
a = sin²(Δlat/2) + cos(lat1) × cos(lat2) × sin²(Δlon/2)
c = 2 × atan2(√a, √(1-a))
jarak = 6.371.000 × c meter
```

Koordinat divalidasi pada rentang latitude −90..90 dan longitude −180..180. Akurasi GPS bisa memburuk di dalam gedung; tetapkan radius realistis (misalnya 100–200 m). HTTPS diperlukan untuk izin lokasi pada production. GPS browser bukan anti-spoofing penuh; untuk risiko tinggi tambahkan attestation perangkat dan review anomali.

Lokasi seed saat ini adalah **PT. Alma Indonesia Raya | Almai**, Jl. Badak Agung, Renon, Denpasar, pada koordinat `-8.6636260, 115.2303082` dengan radius 150 meter.
