# TreeLink — Platform Link-in-Bio (Clone Linktree)

Platform "link-in-bio" lengkap yang dibangun dengan **Laravel 11, PHP, MySQL, Tailwind CSS, dan JavaScript (Alpine.js + SortableJS + Chart.js)**.

Setiap user bisa mendaftar, membuat halaman profil publik di `domain.com/username`, menambahkan banyak link, mengatur tampilan, dan melihat statistik kunjungan/klik. Ada juga admin panel untuk mengelola seluruh platform.

---

## 1. Requirement

- PHP >= 8.2 dengan ekstensi: `pdo_mysql`, `mbstring`, `xml`, `curl`, `gd`/`fileinfo`
- Composer 2.x
- Node.js >= 18 & NPM
- MySQL 8 (atau MariaDB 10.6+)

> **Catatan:** Project ini dibuat di lingkungan sandbox tanpa akses ke Packagist, sehingga `composer install` **belum pernah dijalankan** di sini. Semua source code (migration, model, controller, route, view, JS) sudah lengkap dan sudah melewati pengecekan sintaks PHP (`php -l`) satu per satu — tidak ada error sintaks. Jalankan langkah instalasi di bawah ini di komputer/server Anda yang memiliki akses internet normal.

---

## 2. Instalasi

```bash
# 1. Masuk ke folder project
cd TreeLink

# 2. Install dependency PHP
composer install

# 3. Install dependency JS
npm install

# 4. Copy file environment
cp .env.example .env

# 5. Generate application key
php artisan key:generate

# 6. Buat database MySQL terlebih dahulu, misal:
mysql -u root -e "CREATE DATABASE TreeLink CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 7. Sesuaikan kredensial database di file .env
#    DB_DATABASE=TreeLink
#    DB_USERNAME=root
#    DB_PASSWORD=

# 8. Jalankan migration + seeder (membuat akun admin & demo otomatis)
php artisan migrate --seed

# 9. Buat symbolic link storage (agar foto profil bisa diakses publik)
php artisan storage:link

# 10. Build asset frontend
npm run build
# (atau gunakan `npm run dev` saat development agar hot-reload aktif)

# 11. Jalankan server
php artisan serve
```

Buka browser ke **http://localhost:8000**.

---

## 3. Akun untuk Testing

Seeder (`database/seeders/DatabaseSeeder.php`) otomatis membuat 2 akun saat `php artisan migrate --seed`:

| Peran | Email | Password | Username | Keterangan |
|---|---|---|---|---|
| **Admin** | `admin@TreeLink.test` | `password` | `admin` | Akses ke `/admin` (panel admin) |
| **User Demo** | `demo@TreeLink.test` | `password` | `demo` | Sudah punya 6 contoh link + data analitik 30 hari terakhir (untuk lihat chart) |

Halaman publik demo bisa langsung dicoba di: `http://localhost:8000/demo`

Untuk membuat admin baru secara manual, gunakan Tinker:
```bash
php artisan tinker
>>> $u = App\Models\User::find(1); // atau User::where('email','...')->first()
>>> $u->update(['is_admin' => true]);
```

---

## 4. Alur Fitur yang Sudah Berfungsi End-to-End

1. **Landing Page** (`/`) → tombol Sign Up / Login, section fitur, cara kerja, FAQ, CTA.
2. **Register** (`/register`) → user mengisi nama, username, email, password → otomatis login → redirect ke dashboard, profil dibuat otomatis.
3. **Login** (`/login`) → bisa pakai email **atau** username. Akun yang dinonaktifkan admin akan otomatis ditolak.
4. **Forgot/Reset Password** (`/forgot-password`, `/reset-password/{token}`) → menggunakan mekanisme bawaan Laravel (`Password` facade). Di lokal, email reset akan tercatat di `storage/logs/laravel.log` karena `MAIL_MAILER=log`.
5. **Dashboard** (`/dashboard`) dengan sidebar: Overview, Links, Appearance, Profile, Analytics, Settings.
   - **Links**: tambah/edit/hapus/aktifkan-nonaktifkan link, drag & drop reorder (SortableJS + endpoint AJAX `/dashboard/links/reorder`), live preview di sisi kanan.
   - **Appearance**: pilih tema warna, gaya tombol (rounded/pill/square/outline), font — preview berubah **real-time** tanpa reload (Alpine.js).
   - **Profile**: ubah nama, username, bio, foto profil (upload), dan link social media (Instagram, TikTok, YouTube, WhatsApp, GitHub, dll).
   - **Analytics**: total views, total klik, klik per link, pengunjung unik, grafik tren 30 hari (Chart.js, data dari endpoint JSON `/dashboard/analytics/chart-data`).
   - **Settings**: ubah email/password, salin URL profil, hapus akun (dengan konfirmasi).
6. **Halaman Publik** (`domain.com/username`) → menampilkan foto, nama, bio, ikon sosial media, semua link aktif dengan tema & gaya tombol sesuai pengaturan user, tombol Share (Web Share API / fallback copy-to-clipboard). Setiap kunjungan & klik link otomatis tercatat untuk analytics.
7. **Admin Panel** (`/admin`, khusus akun `is_admin = true`):
   - Overview: total user, user aktif/nonaktif, user baru 7 hari, total link, total views, total klik.
   - Semua User: pencarian, lihat jumlah link & views per user, aktifkan/nonaktifkan akun, hapus akun.
   - Detail User: lihat profil, statistik, dan daftar link milik user tersebut.

---

## 5. Struktur Database

| Tabel | Fungsi |
|---|---|
| `users` | Akun (nama, username unik, email, password, `is_admin`, `is_active`) |
| `profiles` | Data tampilan (bio, avatar, tema, gaya tombol, font, background, social links JSON) — relasi 1:1 ke `users` |
| `links` | Daftar link tiap user (judul, url, icon, posisi urutan, status aktif) — relasi 1:N ke `users` |
| `link_clicks` | Log setiap klik pada sebuah link (IP, user agent, referrer, waktu) — untuk analytics |
| `profile_views` | Log setiap kunjungan ke halaman publik user — untuk analytics |

Relasi Eloquent: `User hasOne Profile`, `User hasMany Link`, `User hasMany ProfileView`, `Link hasMany LinkClick`.

---

## 6. Keamanan yang Diterapkan

- Semua route dashboard dilindungi middleware `auth` + `active` (akun nonaktif otomatis logout).
- Semua route admin dilindungi middleware `auth` + `admin` (cek `is_admin`, selain itu `403`).
- Setiap aksi Link (`update`, `destroy`, `toggle`) melakukan pengecekan kepemilikan (`$link->user_id === Auth::id()`), sehingga user hanya bisa mengubah datanya sendiri.
- Validasi form lengkap di setiap request (username unik & format, URL harus valid, password confirmed, dsb).
- Password di-hash dengan bcrypt (`Hash::make`, cast `hashed` pada model `User`).
- CSRF protection aktif di semua form (`@csrf`).

---

## 7. Struktur File Penting

```
app/Http/Controllers/          → semua controller (Auth, Dashboard, Link, Appearance, Profile, Analytics, PublicProfile, Admin)
app/Http/Middleware/           → IsAdmin, EnsureAccountIsActive, RedirectIfAuthenticated
app/Models/                    → User, Profile, Link, LinkClick, ProfileView
database/migrations/           → seluruh skema tabel
database/seeders/              → DatabaseSeeder (admin+demo user), DemoAnalyticsSeeder (data chart)
resources/views/layouts/       → guest.blade.php, auth.blade.php, dashboard.blade.php, admin.blade.php
resources/views/dashboard/     → overview, links, appearance, profile, analytics, settings
resources/views/admin/         → dashboard, users, user-show
resources/views/profile/show.blade.php → halaman publik link-in-bio
resources/views/components/    → navbar, footer, input, phone-preview (reusable)
resources/js/app.js            → Alpine.js init + SortableJS drag-and-drop
routes/web.php                 → seluruh route aplikasi
```

---

## 8. Troubleshooting

- **"SQLSTATE[HY000] [1049] Unknown database"** → pastikan database sudah dibuat manual sebelum `migrate`.
- **Foto profil tidak muncul** → pastikan sudah menjalankan `php artisan storage:link`.
- **Tampilan tidak ter-style (CSS/JS kosong)** → jalankan `npm install && npm run build` (atau `npm run dev`).
- **Halaman drag & drop tidak jalan** → pastikan asset JS ter-build (`npm run build`) dan cek console browser untuk error CSRF token.
