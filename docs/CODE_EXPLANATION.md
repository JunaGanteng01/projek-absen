# Penjelasan Kode

## Konfigurasi

- `app/Config/Routes.php`: mendefinisikan seluruh GET/POST dan menempelkan filter auth, role, throttle. Dipanggil router pada setiap request.
- `app/Config/Filters.php`: mengaktifkan CSRF/secure headers global dan alias filter aplikasi.
- `app/Config/App.php`: timezone aplikasi `Asia/Jakarta`.

## Controller

- `AuthController.php`: `login()` menampilkan/memproses login, validasi, verify hash, regenerate session, redirect role; `logout()` menghancurkan session.
- `AttendanceController.php`: `currentToken()` menerbitkan QR; `scan()` validasi JSON/form, memanggil AttendanceService, membentuk response 200/422.
- `StaffController.php`: `dashboard()` mengambil attendance hari ini dan 10 riwayat; `history()` memaginasi data milik session user.
- `AdminController.php`: `dashboard()` merender UI; `stats()` JSON; `dashboardData()` menghitung enam statistik, tujuh hari, koordinat dan filter.
- `CrudController.php`: `config()` whitelist model/field/rule; `index()`, `save()`, `delete()` menjalankan CRUD + audit.
- `ReportController.php`: `rows()` query terfilter; `index()` tabel; `excel()` membuat XLSX; `pdf()` merender HTML melalui Dompdf.

## Service

- `QrTokenService.php`: `issue()` random→encrypt→hash/store; `consume()` decrypt→expiry→atomic lock. Dipanggil controller/service absensi.
- `LocationService.php`: `validate()` memilih kantor terdekat dalam radius; `haversine()` mengembalikan meter.
- `AttendanceService.php`: `scan()` memuat user/shift, validasi lokasi, membuka transaksi, consume token, menentukan check-in/out, status hadir dan tipe Full Time/Part Time, audit, commit/rollback.
- `AuditService.php`: `record()` menyimpan snapshot JSON dan IP.

## Model, filter, view

- `BaseAppModel` memberi array result, timestamp dan field protection. `UserModel`, `DepartmentModel`, `OfficeLocationModel`, `ShiftModel`, `AttendanceModel`, `AttendanceTokenModel`, `AuditLogModel` menentukan tabel/field serta soft delete bila relevan.
- `AuthFilter` mensyaratkan session; `RoleFilter` membandingkan role; `ThrottleFilter` menghitung request per IP/path lewat cache.
- `layouts/main.php` shell sidebar eksekutif, navigasi aktif, mobile overlay, topbar/flash/CDN; `auth/login.php` login split-layout; `staff/*` kamera dan riwayat; `admin/dashboard.php` statistik/Chart/peta; `admin/qr.php` generator QR; `crud/index.php` form create/edit/delete; `reports/*` tabel dan template PDF. Seluruh visual memakai token desain di `public/assets/css/app.css`.
- Migration membangun tabel/FK/index; seeder membuat divisi, lokasi, shift, admin, staff. CSS berada di `public/assets/css/app.css`.

Alur panggilan normal: Route → Filter → Controller → Service → Model/DB → View/JSON. Untuk menambah aturan, ubah service; untuk field baru, tambah migration, allowedFields/rules, lalu view dan dokumentasi.
