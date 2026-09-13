# Expense Tracker Web Application

Aplikasi pencatatan pemasukan dan pengeluaran berbasis Laravel. Project ini dibuat dengan tampilan mobile-first untuk mencatat transaksi harian, mengelola kategori, melihat ringkasan dashboard, grafik, dan export data transaksi.

## Fitur

- Dashboard ringkasan saldo, pemasukan, dan pengeluaran per bulan
- Filter bulan dan tahun pada dashboard
- Daftar transaksi yang dikelompokkan per tanggal
- Ringkasan income dan expense harian pada daftar transaksi
- Tambah, edit, dan hapus transaksi
- Keypad angka custom untuk input nominal
- Kelola kategori income dan expense
- Reorder kategori
- Grafik breakdown kategori dan tren bulanan
- Export transaksi bulanan ke CSV
- Tampilan responsive/mobile-first menggunakan Tailwind CSS

## Tech Stack

- Laravel 12
- PHP 8.2+
- SQLite/MySQL
- Blade
- Alpine.js
- Tailwind CSS
- Vite
- Chart.js
- PHPUnit

## Struktur Utama

```text
app/Http/Controllers
├── DashboardController.php
├── TransactionController.php
├── CategoryController.php
├── ChartController.php
└── ExportController.php

app/Models
├── User.php
├── Category.php
└── Transaction.php

resources/views/app
├── dashboard.blade.php
├── transactions/
├── categories/
└── charts/
```

## Instalasi

1. Clone atau buka folder project.

2. Install dependency PHP.

```bash
composer install
```

3. Install dependency frontend.

```bash
npm install
```

4. Buat file environment.

```bash
cp .env.example .env
```

Untuk Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

5. Generate application key.

```bash
php artisan key:generate
```

6. Siapkan database.

Jika menggunakan SQLite, buat file berikut:

```bash
touch database/database.sqlite
```

Untuk Windows PowerShell:

```powershell
New-Item -ItemType File database/database.sqlite
```

Lalu sesuaikan `.env`:

```env
DB_CONNECTION=sqlite
```

7. Jalankan migration dan seeder.

```bash
php artisan migrate --seed
```

8. Jalankan aplikasi.

```bash
composer run dev
```

Atau jalankan backend dan frontend secara terpisah:

```bash
php artisan serve
npm run dev
```

## Build Production

```bash
npm run build
```

## Testing

```bash
composer test
```

Atau:

```bash
php artisan test
```

## Export Data

Export transaksi bulanan tersedia melalui route:

```text
/export?month=YYYY-MM
```

Contoh:

```text
/export?month=2026-09
```

File akan diunduh dalam format CSV.

## Catatan Aplikasi

Saat ini aplikasi berjalan sebagai single-user app menggunakan default user yang dibuat otomatis oleh controller. Fitur login/register belum aktif.

## Optimasi Performa

Project sudah menggunakan eager loading pada relasi kategori transaksi dan index database pada tabel transaksi untuk kolom yang sering dipakai dalam query:

- `user_id`
- `category_id`
- `transaction_date`

Query chart tren bulanan juga sudah dioptimasi agar agregasi 6 bulan dilakukan dalam satu query.
