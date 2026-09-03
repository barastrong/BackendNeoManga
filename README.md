# NeoManga - A Modern Web Reader for Manga & Comics

![NeoManga Showcase](docs/images/Dashboard_Dark.png)

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php" alt="PHP">
  <img src="https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css" alt="Tailwind CSS">
  <img src="https://img.shields.io/github/license/barastrong/BackendNeoManga?style=for-the-badge" alt="License">
</p>

**NeoManga** adalah aplikasi web elegan dan modern, dibangun dengan Laravel + Blade, untuk membaca manga, manhwa, manhua, dan webtoon. Antarmuka bersih, desain responsif dark-first, fitur berpusat pada pengguna.

## 🔴 Live

- **Vercel (aktif):** [https://backendneomanga.vercel.app](https://backendneomanga.vercel.app) — hosting PHP penuh
- **GitHub Pages (placeholder statis):** [`https://barastrong.github.io/BackendNeoManga/`](https://barastrong.github.io/BackendNeoManga/)

> Catatan: aplikasi ini Laravel (PHP + MySQL + Cloudinary). GitHub Pages hanya bisa hosting file statis, jadi workflow `pages.yml` men-deploy halaman landing statis sebagai placeholder — aplikasi penuh berjalan di Vercel.

## ✨ Fitur Utama

- **Desain Modern & Responsif**: Dark-first, Tailwind CSS, aksesibel di semua perangkat.
- **Mode Terang & Gelap**: Toggle tema dengan mudah.
- **Katalog Manga Komprehensif**: Perpustakaan manga luas, dapat dicari & diurutkan.
- **Kategori & Genre**: Sistem genre & kategori terorganisir (60+ genre).
- **Akun Pengguna**: Registrasi, login (OTP), profil, bookmark, riwayat baca.
- **Reader yang Dioptimalkan**: Mode baca vertikal untuk webtoon/manhwa, progress bar.
- **Panel Admin Lengkap**: Dashboard statistik, manajemen Manga/Chapter/Kategori, moderasi komentar, manajemen user & ban.
- **Scraper Kiryuu**: Import otomatis manga & chapter dari sumber eksternal.
- **SEO-Friendly**: Struktur URL bersih, meta tags.

## 🚀 Teknologi

- **Backend**: Laravel, PHP 8.2+
- **Frontend**: Blade, Tailwind CSS 3, Alpine.js
- **Database**: MySQL / MariaDB
- **Media**: Cloudinary
- **Tooling**: Composer, Vite

## 📁 Struktur Folder

```
resources/views/          → hanya file .blade.php (HTML/markup + syntax Blade)
public/css/app.css        → CSS global + utility
public/css/<folder>/      → CSS spesifik per view (dipisah dari blade)
public/js/<folder>/       → JS spesifik per view (dipisah dari blade)
routes/  app/  config/    → struktur standar Laravel
.github/workflows/        → GitHub Actions (deploy Vercel + GitHub Pages)
```

Semua `<style>`/`<script>` inline sudah dipindah ke `public/css/<folder>/` dan `public/js/<folder>/` mengikuti struktur view aslinya. Satu-satunya pengecualian: `resources/views/emails/otp-verification.blade.php` tetap inline (email client memblokir CSS eksternal).

## 🛠️ Instalasi & Setup Lokal

**Prasyarat:** PHP 8.2+, Composer, Node.js & NPM, MySQL/MariaDB

```bash
git clone https://github.com/barastrong/BackendNeoManga.git
cd BackendNeoManga
composer install
cp .env.example .env
php artisan key:generate
# atur DB_CONNECTION/DB_HOST/DB_PORT/DB_DATABASE/DB_USERNAME/DB_PASSWORD di .env
php artisan migrate --seed
npm install
npm run build
php artisan serve
```

Aplikasi tersedia di `http://127.0.0.1:8000`.

> Jika memakai Laragon, akses via `http://backendneomanga.test`.

## 🚀 Deploy

### Vercel (aplikasi penuh)
Push ke branch `main` otomatis memicu workflow `.github/workflows/deploy.yml` → deploy ke Vercel. Butuh secrets: `VERCEL_TOKEN`, `VERCEL_ORG_ID`, `VERCEL_PROJECT_ID`.

### GitHub Pages (landing statis)
Push ke `main` juga memicu `.github/workflows/pages.yml` → men-deploy halaman statis ke `https://barastrong.github.io/BackendNeoManga/`. Karena Laravel butuh PHP runtime, ini halaman placeholder, bukan aplikasi penuh.

## 🗺️ Roadmap

- [x] Sistem Komentar pada Chapter
- [x] Riwayat Baca
- [x] Panel Admin lengkap (Manga, Chapter, Kategori, Moderasi, User)
- [ ] Peringkat & Ulasan Manga
- [ ] Notifikasi Chapter Baru
- [ ] Dukungan Multi-bahasa
- [ ] API untuk aplikasi pihak ketiga

## 🤝 Berkontribusi

1. Fork repositori ini
2. Buat branch baru (`git checkout -b feature/FiturBaru`)
3. Commit perubahan (`git commit -m 'Menambahkan FiturBaru'`)
4. Push ke branch (`git push origin feature/FiturBaru`)
5. Buka Pull Request

## 📝 Lisensi

Proyek ini dilisensikan di bawah [MIT License](LICENSE.md).

---

Dibuat dengan ❤️ oleh barastrong