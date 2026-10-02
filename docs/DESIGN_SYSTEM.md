# ALMAI Unified Design System

Antarmuka AbsensiQR mengikuti identitas CEO Executive Suite:

- Obsidian background `#050505`; surface kartu `#0C0C0C`.
- Electric Neon Green `#33E818` sebagai accent, active state, data utama, dan focus ring.
- Montserrat untuk heading, branding, navigasi, form, dan data UI.
- `.dash-card`/`.card` memakai border `rgba(255,255,255,.08)`. Tambahkan `.dash-card-interactive` untuk hover lift dan glow.
- Sidebar aktif memiliki border kiri 3px neon dan drop-shadow pada ikon.
- Animasi: staggered fade-up, system pulse, brand pulse, QR-card glow, serta transisi sidebar mobile.
- `prefers-reduced-motion` dihormati agar animasi tidak mengganggu pengguna yang menonaktifkan motion.

CSS utama berada di `public/assets/css/app.css`; shell/sidebar berada di `app/Views/layouts/main.php`. Area QR sengaja tetap putih dan tidak diberi overlay bergerak supaya pembacaan kamera tetap optimal.
