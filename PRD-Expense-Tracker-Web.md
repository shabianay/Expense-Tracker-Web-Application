# Product Requirements Document (PRD)
## Expense Tracker — Web Application

**Versi Dokumen:** 1.0
**Tanggal:** 12 September 2026
**Status:** Draft

---

## 1. Latar Belakang & Tujuan

Aplikasi referensi (mobile) memiliki fokus utama: pencatatan pemasukan/pengeluaran yang cepat, breakdown visual berbasis kategori, budgeting bulanan, dan trend cash flow. Dokumen ini mendefinisikan kebutuhan untuk membangun **versi web** dari aplikasi tersebut, dengan tampilan/UX yang mengadaptasi gaya UI pada screenshot yang dilampirkan (header biru dengan wave shape, kartu saldo, daftar transaksi harian, donut chart kategori, line chart trend, dan budget progress bar).

**Tujuan Produk:**
- Menyediakan pencatatan transaksi keuangan (income & expense) yang cepat dan sederhana melalui browser.
- Memberikan visualisasi data (pie/donut chart & trend line) agar pengguna paham pola pengeluaran mereka.
- Memungkinkan pengguna menetapkan dan memonitor budget bulanan per akun/kategori.
- Mendukung kategori custom (icon + warna) sesuai kebutuhan pengguna.

**Target Pengguna:** individu yang ingin mengelola keuangan pribadi/harian secara mandiri via web (desktop maupun mobile browser).

---

## 2. Ruang Lingkup (Scope)

### In Scope (v1)
- Autentikasi user (register/login)
- CRUD transaksi (income & expense)
- Manajemen kategori custom (icon, warna, tipe income/expense)
- Dashboard ringkasan bulanan (saldo, income, expense, budget progress)
- Halaman Charts: donut chart per kategori + trend line bulanan + tabel ringkasan bulanan
- Set & edit budget bulanan
- Navigasi bulan (prev/next month)
- Export data ke Excel/CSV
- Responsive design (mobile-first, tapi tetap nyaman di desktop)

### Out of Scope (v1, dipertimbangkan untuk v2)
- Cloud sync multi-device secara real-time (bisa cukup via akun user + DB terpusat)
- Multi-currency
- Rekonsiliasi rekening bank otomatis (bank feed/API)
- Fitur kolaborasi/shared budget antar user
- Notifikasi push (bisa digantikan notifikasi in-app/email untuk v1)
- Dark mode (nice-to-have, bisa masuk v1.1)

---

## 3. Struktur Halaman & Fitur Detail

### 3.1 Dashboard / Home
Mengacu pada Image 1 & 5.

**Elemen UI:**
- Header dengan background gradient biru + wave shape di bagian bawah header
- Selector bulan aktif (`< Jan 2026 >`) dengan tombol navigasi prev/next
- Card ringkasan: **Balance**, **Expense**, **Income** (angka besar, warna kontras di atas background biru)
- Progress bar **Budget** dengan info "Budget: 1.000" dan "Remaining: 652"
- Daftar transaksi dikelompokkan per tanggal, tiap grup menampilkan total Expense & Income hari itu
- Tiap item transaksi: icon kategori (bulat, berwarna), nama kategori, catatan (opsional), nominal (income berwarna beda dari expense, expense biasanya ditampilkan negatif)
- Floating action button (+) di kanan bawah untuk tambah transaksi cepat
- Bottom navigation: Home, Charts, Search

**Functional Requirements:**
- FR-1.1: User dapat berpindah bulan dan data (balance, expense, income, list transaksi) ter-update sesuai bulan terpilih
- FR-1.2: Balance dihitung dari total income − total expense (bisa kumulatif atau per bulan, perlu didefinisikan — asumsi: balance = saldo berjalan kumulatif hingga akhir bulan terpilih)
- FR-1.3: Klik icon mata (eye) pada Balance dapat menyembunyikan/menampilkan nominal (privacy mode)
- FR-1.4: Klik item transaksi membuka form edit
- FR-1.5: Tombol (+) membuka form tambah transaksi

### 3.2 Form Tambah/Edit Transaksi
Mengacu pada Image 2.

**Elemen UI:**
- Header "Add" / "Edit" dengan tombol back dan Select kategori
- Kategori terpilih ditampilkan dengan icon bulat + nama, ada opsi "Select" untuk ganti kategori
- Input catatan/note bebas teks (contoh: "Hat")
- Numeric keypad kustom untuk input nominal (angka besar di atas keypad)
- Date & time picker (contoh: "Jan 2, 2026 23:07")
- Tombol konfirmasi (✓) di keypad

**Functional Requirements:**
- FR-2.1: User memilih tipe transaksi (income/expense) — biasanya ditentukan dari tab/kategori yang dipilih
- FR-2.2: User memilih kategori dari daftar kategori yang sudah ada
- FR-2.3: User input nominal via numeric input (mendukung operasi tambah/kurang seperti tombol "+x"/"−+" pada image, bisa berupa quick calculator sederhana)
- FR-2.4: User dapat mengatur tanggal & waktu transaksi (default: sekarang)
- FR-2.5: User dapat menambahkan catatan opsional
- FR-2.6: Simpan transaksi baru / update transaksi yang sudah ada

### 3.3 Charts (Visual Analysis)
Mengacu pada Image 3 & 4.

**Elemen UI - Category Breakdown:**
- Selector bulan
- 4 kartu ringkasan: Income, Expense, Balance, Daily Spent (rata-rata harian)
- Toggle tab "Income" / "Expense"
- Donut chart per kategori dengan label persentase di tepi chart, total di tengah donut
- List kategori di bawah chart: icon, nama, progress bar mini, persentase, nominal

**Elemen UI - Trend:**
- Toggle tab "Income" / "Expense"
- Line chart tren bulanan (garis merah pada contoh untuk expense)
- Tabel ringkasan: kolom Date, Income, Expense, Balance — baris "2026" (total tahunan), "Monthly" (rata-rata), lalu daftar per bulan (Jul, Jun, May, ... Jan) yang bisa di-expand/collapse

**Functional Requirements:**
- FR-3.1: Donut chart dan list kategori update sesuai toggle Income/Expense dan bulan aktif
- FR-3.2: Trend line menampilkan data historis (default beberapa bulan terakhir), dengan sumbu Y otomatis menyesuaikan skala data
- FR-3.3: Tabel ringkasan bulanan bisa di-collapse/expand (indikator panah di bawah tabel)
- FR-3.4: Klik salah satu baris bulan dapat membawa user ke Dashboard bulan tsb (opsional v1)

### 3.4 Budget
Mengacu pada Image 5 & 6.

**Elemen UI:**
- Modal/bottom sheet "Edit Budget" dengan judul bulan aktif
- Numeric keypad untuk input nominal budget
- Progress bar budget di Dashboard menunjukkan proporsi expense terhadap budget, dengan info "Remaining"

**Functional Requirements:**
- FR-4.1: User dapat set/update nominal budget bulanan (berlaku untuk bulan yang sedang dipilih, atau default untuk bulan-bulan berikutnya bila belum di-set)
- FR-4.2: Progress bar berubah warna (misal kuning/merah) ketika mendekati/melebihi limit budget
- FR-4.3: (Opsional v1.1) Budget per kategori, bukan hanya budget total

### 3.5 Kategori (Categories)
Mengacu pada Image 7.

**Elemen UI:**
- Tab "Expense (35)" / "Income (5)" dengan jumlah kategori
- List kategori: icon bulat berwarna, nama kategori, tombol "..." (edit/hapus/reorder)
- Tombol "+" di header untuk tambah kategori baru
- Tombol sort/reorder (icon panah atas-bawah di header)

**Functional Requirements:**
- FR-5.1: User dapat menambah kategori baru (pilih/upload icon, pilih warna, tentukan tipe income/expense)
- FR-5.2: User dapat mengedit nama, icon, warna kategori
- FR-5.3: User dapat menghapus kategori (dengan validasi: kategori yang sudah punya transaksi tidak bisa dihapus langsung, atau transaksi di-reassign ke kategori "Uncategorized")
- FR-5.4: User dapat mengurutkan ulang kategori (drag & drop atau tombol reorder)
- FR-5.5: Sistem menyediakan kategori default saat akun baru dibuat (Food, Clothing, Pets, Shopping, Coffee, Travel, Sports, Gaming, Gifts, Fruit, dll — sesuai contoh)

### 3.6 Export Data
- FR-6.1: User dapat export transaksi ke CSV/Excel berdasarkan rentang tanggal/bulan yang dipilih

---

## 4. Data Model (Usulan Skema Database)

```
users
- id, name, email, password, month_start_day (default 1), created_at, updated_at

categories
- id, user_id, name, type (enum: income/expense), icon, color, sort_order, created_at, updated_at

transactions
- id, user_id, category_id, type (enum: income/expense), amount, note, transaction_date, created_at, updated_at

budgets
- id, user_id, month (YYYY-MM), amount, created_at, updated_at
```

---

## 5. Non-Functional Requirements

- **Performance:** Dashboard & Charts harus load < 2 detik untuk data hingga ~5000 transaksi/user
- **Responsiveness:** Layout tetap nyaman digunakan di mobile browser (mengikuti gaya UI mobile pada referensi) maupun desktop (bisa menggunakan layout 2 kolom di desktop: sidebar + konten)
- **Security:** Password di-hash, autentikasi berbasis session/token, data transaksi hanya bisa diakses oleh pemiliknya
- **Data privacy:** Fitur "hide balance" (mode privasi) seperti pada referensi
- **Browser support:** Chrome, Safari, Firefox, Edge versi terbaru

---

## 6. Rekomendasi Tech Stack

Menyesuaikan stack yang biasa digunakan:
- **Backend:** Laravel 12
- **Frontend/Templating:** Blade + Alpine.js (untuk interaktivitas ringan: numeric keypad, toggle tab, bottom sheet form)
- **Styling:** Tailwind CSS (memudahkan replikasi gradient header, wave shape via SVG/clip-path, card, progress bar)
- **Database:** MySQL
- **Chart Library:** Chart.js atau ApexCharts (mendukung donut chart & line chart dengan mudah, ringan untuk Blade+Alpine)
- **Export:** Laravel Excel (maatwebsite/excel) untuk fitur export CSV/Excel

---

## 7. User Flow Utama

1. User login/register → diarahkan ke Dashboard
2. Dashboard menampilkan bulan berjalan → user tap (+) → form tambah transaksi
3. User pilih kategori → input nominal via keypad → simpan
4. Transaksi baru muncul di list Dashboard, balance/expense/income ter-update
5. User buka tab Charts → lihat breakdown kategori (donut) atau trend (line + tabel)
6. User buka menu Budget → set nominal budget bulanan → progress bar di Dashboard ter-update
7. User buka Categories → tambah/edit/hapus kategori sesuai kebutuhan

---

## 8. Metrik Keberhasilan (Success Metrics)

- Rata-rata waktu input 1 transaksi < 10 detik
- Retention: user kembali mencatat transaksi minimal 3x/minggu
- % user yang mengaktifkan fitur budget bulanan

---

## 9. Milestone / Fase Pengembangan

| Fase | Fitur | Estimasi |
|---|---|---|
| Fase 1 | Auth, CRUD transaksi, kategori default, Dashboard dasar | 1-2 minggu |
| Fase 2 | Kategori custom (CRUD + reorder), Budget | 1 minggu |
| Fase 3 | Charts (donut + trend + tabel ringkasan) | 1 minggu |
| Fase 4 | Export Excel/CSV, polish UI (wave header, animasi, responsive) | 3-5 hari |

---

## 10. Catatan Desain UI (mengacu screenshot)

- Warna dominan: biru gradient (header) di atas, putih/abu muda (konten) di bawah, dipisahkan bentuk wave/lengkung
- Angka nominal besar & bold untuk balance, warna putih di atas background biru
- Icon kategori: bentuk lingkaran solid dengan warna berbeda per kategori + icon putih di tengah
- Progress bar budget: rounded, warna biru terang di atas track abu-abu muda
- Bottom navigation: 4 item (Home, Chart/pie icon, Search) + FAB (+) melayang di kanan bawah, warna biru solid
