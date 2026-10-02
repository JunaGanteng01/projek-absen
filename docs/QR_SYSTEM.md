# Sistem QR

Admin membuka `/admin/qr`. Browser meminta `GET /api/qr/current`; `QrTokenService::issue()` membuat payload biner ringkas berisi expiry 4 byte dan nonce kriptografis 8 byte, mengenkripsinya memakai AES-256-CTR dengan autentikasi HMAC-SHA256, mengubah cipher menjadi Base64URL, lalu menyimpan SHA-256-nya. Format ringkas menjaga pola QR tidak terlalu padat sehingga lebih mudah dipindai kamera. TTL adalah 30 detik.

Saat scan, cipher didekripsi untuk memastikan integritas dan expiry. Hash dicari dengan kondisi belum terpakai dan belum expired. Update `used_at/used_by` memakai kondisi `used_at IS NULL`; affected row harus tepat satu sehingga dua request bersamaan tidak bisa memakai token sama.

Key wajib unik dan hanya ada di `.env`. Rotasi key membatalkan token aktif. Token kedaluwarsa dapat dibersihkan berkala: `DELETE FROM attendance_tokens WHERE expires_at < NOW() - INTERVAL 7 DAY`.

Untuk pemindaian yang baik, tampilkan QR minimal 340×340 piksel, naikkan kecerahan layar, hindari glare/pantulan, dan jaga kamera sekitar 20–40 cm sampai fokus. Scanner membatasi format ke QR Code dan memakai area deteksi adaptif sekitar 78% sisi kamera.
