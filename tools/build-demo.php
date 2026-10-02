<?php
/** Generate a static presentation from existing views, using only fictitious fixtures. */
declare(strict_types=1);
if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}
error_reporting(E_ALL);
set_error_handler(static function (int $severity, string $message, string $file, int $line): never {
    throw new ErrorException($message, 0, $severity, $file, $line);
});
date_default_timezone_set('Asia/Jakarta');
$root = dirname(__DIR__);
$output = $root . '/demo';
$demoRole = 'admin';
$demoRoute = '';
function esc($value, string $context = 'html'): string { return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); }
function base_url(string $path): string { return '/' . $path; }
function csrf_token(): string { return 'demo'; }
function csrf_hash(): string { return 'demo'; }
function csrf_field(): string { return ''; }
function old(string $key): string { return ''; }
function session(?string $key = null) {
    global $demoRole;
    if ($key === null) return new class { public function getFlashdata(string $key) { return null; } };
    return ['role' => $demoRole, 'name' => $demoRole === 'admin' ? 'Admin Demo' : 'Staff Demo'][$key] ?? null;
}
function request() { return new class { public function getGet(?string $key = null) { return $key === null ? [] : null; } }; }
function service(string $name) {
    return new class { public function getPath(): string { global $demoRoute; return $demoRoute; } };
}
class DemoView {
    private array $sections = [];
    private ?string $current = null;
    public function extend(string $layout): string { return ''; }
    public function section(string $name): string { $this->current = $name; ob_start(); return ''; }
    public function endSection(): string { $this->sections[$this->current] = ob_get_clean(); $this->current = null; return ''; }
    public function renderSection(string $name): string { return $name === 'scripts' ? '' : ($this->sections[$name] ?? ''); }
    public function render(string $view, array $data): string {
        global $root;
        extract($data);
        ob_start();
        include $root . '/app/Views/' . $view . '.php';
        $html = ob_get_clean();
        if ($view !== 'auth/login') {
            ob_start();
            include $root . '/app/Views/layouts/main.php';
            $html = ob_get_clean();
        }
        return $html;
    }
}
function writeDemo(string $path, string $content): void {
    global $output;
    $target = $output . '/' . $path;
    if (!is_dir(dirname($target))) mkdir(dirname($target), 0755, true);
    file_put_contents($target, $content);
}
$departments = [['id'=>1,'name'=>'Teknologi','code'=>'IT'], ['id'=>2,'name'=>'Operasional','code'=>'OPS']];
$history = [];
for ($i = 0; $i < 5; $i++) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $history[] = ['id'=>$i+1,'attendance_date'=>$date,'employee_no'=>'DEMO00'.($i+1),'name'=>'Staff Contoh '.($i+1),'department_name'=>$i%2?'Operasional':'Teknologi','check_in'=>$date.' 09:55:00','check_out'=>$i?$date.' 18:05:00':null,'work_type'=>'Full Time','check_in_status'=>'Tepat Waktu','check_out_status'=>$i?'Pulang Normal':null,'location_name'=>'Kantor Contoh'];
}
$configs = [
    'staff'=>['title'=>'Manajemen Staff','fields'=>['name'=>'Nama','email'=>'Email','employee_no'=>'NIP','department_id'=>'ID Divisi','shift_id'=>'ID Shift','role'=>'Role','is_active'=>'Aktif'],'rows'=>[['id'=>1,'name'=>'Staff Contoh','email'=>'staff@example.com','employee_no'=>'DEMO001','department_id'=>1,'shift_id'=>1,'role'=>'staff','is_active'=>1]]],
    'departments'=>['title'=>'Manajemen Divisi','fields'=>['name'=>'Nama','code'=>'Kode'],'rows'=>$departments],
    'locations'=>['title'=>'Manajemen Lokasi Kantor','fields'=>['name'=>'Nama','latitude'=>'Latitude','longitude'=>'Longitude','radius_meters'=>'Radius (m)','is_active'=>'Aktif'],'rows'=>[['id'=>1,'name'=>'Kantor Contoh','latitude'=>-8.663626,'longitude'=>115.2303082,'radius_meters'=>150,'is_active'=>1]]],
    'shifts'=>['title'=>'Manajemen Shift','fields'=>['name'=>'Nama','start_time'=>'Mulai Full Time','part_time_start'=>'Mulai Part Time','end_time'=>'Jam Pulang','late_tolerance_minutes'=>'Toleransi Full Time','early_leave_tolerance_minutes'=>'Toleransi Pulang','is_active'=>'Aktif'],'rows'=>[['id'=>1,'name'=>'Reguler','start_time'=>'10:00','part_time_start'=>'14:00','end_time'=>'18:00','late_tolerance_minutes'=>0,'early_leave_tolerance_minutes'=>0,'is_active'=>1]]],
];
$pages = [
    'index'=>['auth/login',[]],
    'login'=>['auth/login',[]],
    'admin/dashboard'=>['admin/dashboard',['title'=>'Dashboard Admin','date'=>date('Y-m-d'),'departments'=>$departments,'stats'=>['present'=>42,'late'=>3,'absent'=>6,'working'=>30,'finished'=>12,'overtime'=>2],'chart'=>[],'locations'=>[]]],
    'admin/qr'=>['admin/qr',['title'=>'QR Absensi']],
    'admin/reports'=>['reports/index',['title'=>'Laporan Absensi','rows'=>$history]],
    'staff/dashboard'=>['staff/dashboard',['title'=>'Dashboard Staff','today'=>$history[0],'history'=>$history]],
    'staff/history'=>['staff/history',['title'=>'Riwayat Absensi','rows'=>$history,'pager'=>new class { public function links(): string { return '<span class="text-secondary small">Menampilkan 5 data contoh</span>'; } }]],
];
foreach ($configs as $type=>$config) $pages['admin/'.$type] = ['crud/index',['title'=>$config['title'],'type'=>$type,'config'=>$config,'rows'=>$config['rows']]];
foreach ($pages as $route=>[$view,$data]) {
    $demoRoute = $route;
    $demoRole = str_starts_with($route, 'staff/') ? 'staff' : 'admin';
    $html = (new DemoView())->render($view, $data);
    $html = str_replace(['SYSTEM ONLINE','LIVE TREND','LIVE TOKEN','ENCRYPTED','SERVER VERIFIED','perbarui otomatis tiap 30 detik.','Waktu absensi terverifikasi langsung oleh server WIB.','Token terenkripsi diperbarui setiap 30 detik dan hanya dapat dipakai satu kali.'], ['DEMO MODE','DATA CONTOH','QR DEMO','SIMULASI','DEMO PREVIEW','gunakan data contoh.','Preview kehadiran menggunakan data contoh.','Contoh tampilan QR; kode ini tidak digunakan untuk absensi.'], $html);
    $html = str_replace('digunakan data contoh.', 'menggunakan data contoh.', $html);
    $html = str_replace('</span>LIVE</span>', '</span>DEMO</span>', $html);
    $html = str_replace('href="/logout"', 'href="/"', $html);
    $html = preg_replace('~Demo access:.*?</small>~s', 'Preview tanpa login. Semua data adalah contoh.</small><div class="d-grid gap-2 mt-3"><a class="btn btn-primary" href="/admin/dashboard">Lihat Dashboard Admin</a><a class="btn btn-outline-primary" href="/staff/dashboard">Lihat Dashboard Staff</a></div>', $html);
    $html = str_replace('Masuk ke workspace Anda untuk melanjutkan.', 'Pilih dashboard di bawah untuk menjelajahi tampilan demo.', $html);
    if ($view === 'auth/login') $html = preg_replace('~<form.*?</form>~s', '', $html);
    // Convert server operations into explicitly local presentation controls.
    $html = preg_replace('~action="[^"]*"~', 'action="#"', $html);
    $html = str_replace('method="post"', 'method="get"', $html);
    $html = str_replace('onsubmit="return confirm(\'Hapus data ini?\')"', '', $html);
    $html = preg_replace('~href="/admin/reports/(excel|pdf)[^"]*"~', 'href="#" data-demo-action="true"', $html);
    $banner = '<div class="alert alert-info demo-banner">Demo tampilan · Semua data adalah contoh. <a href="/admin/dashboard">Admin</a> · <a href="/staff/dashboard">Staff</a></div>';
    $html = str_replace('<main class="content-wrap">', '<main class="content-wrap">'.$banner, $html);
    $html = str_replace('</body>', '<script src="/assets/js/demo.js"></script></body>', $html);
    writeDemo($route.'.html', $html);
}
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root.'/public/assets', FilesystemIterator::SKIP_DOTS));
foreach ($iterator as $file) if ($file->isFile()) writeDemo('assets/'.str_replace('\\','/',substr($file->getPathname(), strlen($root.'/public/assets/'))), file_get_contents($file->getPathname()));
writeDemo('favicon.ico', file_get_contents($root.'/public/favicon.ico'));
writeDemo('404.html', '<!doctype html><html lang="id"><meta charset="utf-8"><title>Halaman tidak ditemukan</title><h1>Halaman tidak ditemukan</h1><a href="/">Kembali ke demo</a></html>');
echo 'Generated '.count($pages).' static demo pages in demo/'.PHP_EOL;
