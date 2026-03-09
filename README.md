# Sistem Pendukung Keputusan — Rekomendasi Produk Mainan Anak (SAW)

Aplikasi e-commerce mainan anak dengan fitur rekomendasi produk menggunakan metode **Simple Additive Weighting (SAW)**.

## Spesifikasi

- **Backend:** Laravel (PHP 8+), MVC, Service Layer (SAW), Form Request, Eloquent ORM
- **Frontend:** Blade, Tailwind CSS, responsive, UI pastel/minimal
- **Database:** MySQL (relational, foreign key, indexing)
- **Keamanan:** Laravel Auth, password hashing, CSRF, role-based middleware (Admin/User)

## Role

- **Admin:** Dashboard, CRUD Produk & Kriteria, Manajemen Transaksi, Export Laporan
- **User:** Register/Login, Dashboard, Belanja (filter, search), Keranjang, Checkout, Riwayat Transaksi, **Konsultasi Rekomendasi SAW** (slider prioritas: Harga, Kualitas, Keamanan, Edukasi, Popularitas)

## Instalasi

```bash
# Clone & masuk folder
cd Jok

# Install dependency PHP
composer install

# Salin env
cp .env.example .env
php artisan key:generate

# Database (MySQL)
# Set di .env: DB_CONNECTION=mysql, DB_DATABASE=..., DB_USERNAME=..., DB_PASSWORD=...
php artisan migrate
php artisan db:seed

# Storage link (untuk gambar produk)
php artisan storage:link

# Frontend (wajib agar CSS/JS dan warna tampil)
npm install
npm run build
# Saat development: jalankan di terminal terpisah: npm run dev
```

## Menjalankan

```bash
php artisan serve
# Buka http://localhost:8000
```

**Akun contoh (setelah seed):**

- Admin: `admin@toy.com` / `password`
- User: `user@toy.com` / `password`

## Testing

```bash
php artisan test
# Atau: ./vendor/bin/phpunit
```

- Unit: `ToyRecommendationServiceTest` (perhitungan SAW)
- Feature: `CheckoutTest`, `RecommendationValidationTest`

## Troubleshooting

- **CSS/JS/warna tidak muncul:** Pastikan sudah menjalankan `npm run build` sekali (menghasilkan `public/build/`). Atau saat development jalankan `npm run dev` di terminal terpisah selain `php artisan serve`.
- **Content Security Policy / eval:** Semua script sudah dipindah ke `resources/js/app.js` (tanpa inline/eval). Jika tetap ada error CSP, periksa header dari server atau ekstensi browser.
- **Autocomplete warning:** Form login, register, dan checkout sudah memakai atribut `autocomplete` yang sesuai.

## Production

- Set `APP_ENV=production`, `APP_DEBUG=false`
- Konfigurasi `.env` untuk database dan `APP_URL`
- Jalankan `php artisan config:cache`, `php artisan route:cache`, `php artisan view:cache`
- Pastikan `php artisan storage:link` sudah dijalankan

## Struktur Relevan

- **SAW:** `app/Services/ToyRecommendationService.php`
- **Migrasi:** `database/migrations/` (users, products, criterias, product_criterias, carts, transactions, transaction_details)
- **Rute:** `routes/web.php` (guest, auth, admin prefix)
