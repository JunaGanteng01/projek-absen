# Referensi API

Semua endpoint privat memerlukan session login. POST memerlukan CSRF dengan nama token dari meta halaman; response scan mengembalikan hash baru.

## GET `/api/qr/current` (admin)

Response: `{"success":true,"data":{"token":"…","expires_at":"2026-09-15 09:00:30","ttl":30}}`.

## POST `/api/attendance/scan` (staff/admin terautentikasi)

```json
{"token":"cipher-base64url","latitude":-6.2,"longitude":106.8166667,"csrf_test_name":"hash"}
```

Sukses 200: `{"success":true,"message":"Absensi berhasil.","data":{"action":"check_in","status":"Tepat Waktu","time":"…","attendance_id":1},"csrfHash":"…"}`.

Gagal validasi/domain 422: `{"success":false,"message":"Token QR sudah digunakan atau kedaluwarsa.","csrfHash":"…"}`. Rate limit menghasilkan 429; tanpa session diarahkan ke login; role salah menghasilkan 403.

## GET `/api/dashboard/stats` (admin)

Query opsional: `date=YYYY-MM-DD&department_id=1`. Output berisi `stats`, `chart`, `locations`, `departments`, dan tanggal. Endpoint ini dapat dipoll untuk peta/statistik realtime.

Route halaman dan form CRUD tercantum dengan `php spark routes`.
