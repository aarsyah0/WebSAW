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
- Admin: `admin@toy.com` / `password`
- User: `user@toy.com` / `password`
```
