<?php
$uri=trim(service('uri')->getPath(),'/');
$active=static fn(string $route): string => ($uri===$route || str_starts_with($uri,$route.'/'))?'active':'';
$adminNav=[
 ['admin/dashboard','bi-grid-1x2-fill','Executive Overview'],
 ['admin/qr','bi-qr-code-scan','QR Attendance'],
 ['admin/reports','bi-file-earmark-bar-graph','Reports'],
 ['admin/staff','bi-people','Staff'],
 ['admin/departments','bi-diagram-3','Departments'],
 ['admin/locations','bi-geo-alt','Office Locations'],
 ['admin/shifts','bi-clock-history','Work Shifts'],
];
$staffNav=[['staff/dashboard','bi-grid-1x2-fill','My Dashboard'],['staff/history','bi-calendar3','Attendance History']];
$navigation=session('role')==='admin'?$adminNav:$staffNav;
?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="csrf-name" content="<?= csrf_token() ?>"><meta name="csrf-hash" content="<?= csrf_hash() ?>">
<title><?= esc($title ?? 'ALMAI Attendance') ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="icon" type="image/png" href="<?= base_url('assets/img/almai-logo.png') ?>"><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"><link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"><link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>"></head>
<body class="app-shell"><div class="ambient ambient-one"></div><div class="ambient ambient-two"></div>
<aside class="app-sidebar" id="sidebar"><div class="sidebar-brand"><div class="brand-mark"><img src="<?= base_url('assets/img/almai-logo.png') ?>" alt="Logo ALMAI"></div><div><div class="brand-name">ALMAI</div><div class="brand-suite">ATTENDANCE SUITE</div></div><button class="sidebar-close d-lg-none" id="sidebarClose" aria-label="Tutup menu"><i class="bi bi-x-lg"></i></button></div>
<div class="sidebar-divider"></div><div class="nav-caption">WORKSPACE</div><nav class="sidebar-nav"><?php foreach($navigation as [$route,$icon,$label]): ?><a class="sidebar-link <?= $active($route) ?>" href="/<?= esc($route) ?>"><i class="bi <?= esc($icon) ?>"></i><span><?= esc($label) ?></span></a><?php endforeach ?></nav>
<div class="sidebar-spacer"></div><div class="executive-profile"><div class="profile-avatar"><?= esc(strtoupper(substr((string)session('name'),0,1))) ?></div><div class="profile-copy"><strong><?= esc(session('name')) ?></strong><span><?= esc(strtoupper((string)session('role'))) ?> ACCESS</span></div></div><a class="sidebar-link logout-link" href="/logout"><i class="bi bi-box-arrow-right"></i><span>Sign Out</span></a></aside>
<div class="sidebar-overlay" id="sidebarOverlay"></div>
<div class="app-main"><header class="topbar"><button class="menu-trigger d-lg-none" id="menuTrigger" aria-label="Buka menu"><i class="bi bi-list"></i></button><div class="topbar-context"><span class="context-kicker">CEO EXECUTIVE ECOSYSTEM</span><span class="context-title"><?= esc($title ?? 'Attendance') ?></span></div><div class="topbar-right"><span class="system-status"><span class="live-dot"></span>SYSTEM ONLINE</span><span class="topbar-date"><?= esc(date('d M Y')) ?></span></div></header>
<main class="content-wrap"><?php foreach(['success'=>'success','error'=>'danger'] as $key=>$class): if(session()->getFlashdata($key)): ?><div class="alert alert-<?= $class ?> animate-in"><?= esc(session()->getFlashdata($key)) ?></div><?php endif; endforeach ?><?= $this->renderSection('content') ?></main></div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script><script>const sidebar=document.getElementById('sidebar'),overlay=document.getElementById('sidebarOverlay');function toggleSidebar(open){sidebar.classList.toggle('open',open);overlay.classList.toggle('show',open);document.body.classList.toggle('menu-open',open)}document.getElementById('menuTrigger')?.addEventListener('click',()=>toggleSidebar(true));document.getElementById('sidebarClose')?.addEventListener('click',()=>toggleSidebar(false));overlay?.addEventListener('click',()=>toggleSidebar(false));document.querySelectorAll('.card,.dash-card,.page-heading').forEach((el,i)=>{el.classList.add('animate-in');el.style.setProperty('--delay',`${Math.min(i*55,440)}ms`)})</script><?= $this->renderSection('scripts') ?></body></html>
