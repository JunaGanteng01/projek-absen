# Keamanan

- CSRF global aktif; seluruh form memakai `csrf_field()`, JSON scan mengirim token dan menerima hash regenerasi.
- Semua output dinamis di-view memakai `esc()`; JSON untuk script memakai `JSON_HEX_TAG`.
- Model/Query Builder menghasilkan prepared statement dan `allowedFields` mencegah mass assignment.
- Input divalidasi server-side. Koordinat dan role dibatasi; CRUD hanya memakai whitelist konfigurasi, bukan nama tabel dari URL.
- Session ID diregenerasi setelah login; password memakai `password_hash`/`password_verify`; route memakai Auth/RoleFilter.
- login dan scan dibatasi 10 request/menit per IP+path melalui cache. Untuk multi-node, gunakan Redis cache.
- QR memakai random nonce, enkripsi key environment, hash at-rest, expiry, dan atomic one-time consume.
- Timestamp domain berasal dari server `Asia/Jakarta`, bukan jam perangkat.
- `AuditService` merekam perubahan penting dan scan beserta aktor/IP. Batasi akses DB agar audit tidak bisa diedit aplikasi biasa.
- Secure headers aktif. Production wajib HTTPS, secure/HTTPOnly/SameSite cookies, secret rotation, CSP yang disesuaikan CDN, backup, monitoring, dan dependency audit. Basemap dashboard memakai OpenFreeMap/MapLibre dan tetap menampilkan atribusi OpenMapTiles serta OpenStreetMap.

Catatan: CDN sebaiknya di-self-host atau diberi Subresource Integrity. GPS browser dapat dipalsukan pada perangkat terkompromi; gunakan kontrol tambahan bila ancaman menuntutnya.
