# SecondLife Marketplace Barang Preloved Mahasiswa

**SecondLife** adalah platform marketplace berbasis web untuk jual-beli barang bekas (preloved) khususnya di lingkungan mahasiswa. Dibangun dengan **Laravel 12**, aplikasi ini mempertemukan pembeli dan penjual dalam satu ekosistem yang aman, mudah, dan terpercaya.

---

## Fitur Utama

### Autentikasi & Manajemen Akun
- Registrasi & Login dengan sistem role (Admin, Penjual, Pembeli)
- Lupa password & reset password via email
- Kelola profil: foto, nomor HP, lokasi, dan hapus akun

### Penjual
- Daftar sebagai penjual dengan upload KTM (verifikasi admin)
- Dashboard penjual: kelola produk, pantau transaksi masuk
- Tambah, edit, hapus, dan publish/unpublish produk
- Upload hingga multi-foto untuk setiap produk
- Balas ulasan pembeli
- Jadwal COD (Cash on Delivery) langsung dari platform

### Pembeli
- Jelajahi produk dengan filter: **kategori**, **kondisi**, **rentang harga**, dan **urutan**
- Pencarian produk & penjual
- Proses checkout dengan pilihan metode pembayaran
- Riwayat transaksi lengkap
- Upload bukti pembayaran
- Beri ulasan produk & ulasan penjual
- Chat langsung dengan penjual per transaksi

### Admin
- Dashboard monitoring platform
- Verifikasi akun penjual (setujui / tolak)
- Kelola kategori & proses request kategori dari pengguna
- Kelola produk (publish/unpublish/hapus)
- Pantau semua transaksi
- Manajemen pengguna (aktifkan / nonaktifkan)
- Moderasi ulasan penjual

---

## Struktur Peran (Role)

| Role | Akses |
|------|-------|
| **Admin** | Full kontrol platform, verifikasi penjual, moderasi konten |
| **Penjual** | Kelola produk & transaksi milik sendiri |
| **Pembeli** | Browse produk, checkout, review, chat |

---

## Database & Migrasi

Tabel utama yang digunakan:

| Tabel | Keterangan |
|-------|-----------|
| `users` | Data pengguna |
| `roles` | Daftar role (admin, penjual, pembeli) |
| `user_roles` | Relasi many-to-many user ↔ role |
| `kategori` | Kategori produk |
| `produk` | Data produk yang dijual |
| `produk_foto` | Foto-foto produk |
| `transaksi` | Data transaksi jual-beli |
| `detail_transaksi` | Detail item per transaksi |
| `jadwal_cod` | Jadwal COD antara penjual & pembeli |
| `review` | Ulasan produk oleh pembeli |
| `seller_reviews` | Ulasan penjual oleh pembeli |
| `chat` | Pesan chat per transaksi |
| `request_kategori` | Request kategori baru dari pengguna |
| `password_reset_tokens` | Token reset password |
| `sessions` | Sesi login pengguna |

---

## Cara Menjalankan Proyek

### Prasyarat
- PHP 8.2+
- Composer
- Node.js & NPM
- MySQL (via XAMPP atau sejenisnya)
- Laravel 12

### Langkah Instalasi

**1. Clone repository**
```bash
git clone https://github.com/namw2e/Secondlife.git
cd Secondlife
```

**2. Install dependensi PHP & Node.js**
```bash
composer install
npm install
```

**3. Salin file environment & generate key**
```bash
cp .env.example .env
php artisan key:generate
```

**4. Konfigurasi database di `.env`**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=secondlife
DB_USERNAME=root
DB_PASSWORD=
```

**5. Konfigurasi email untuk fitur reset password di `.env`**
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your_email@gmail.com
MAIL_FROM_NAME="SecondLife"
```

**6. Jalankan migrasi & seeder**
```bash
php artisan migrate --seed
```

**7. Build aset frontend**
```bash
npm run build
# atau untuk development (hot reload):
npm run dev
```

**8. Jalankan server**
```bash
php artisan serve
```

Akses aplikasi di: **http://localhost:8000**

---

## Akun Demo (Setelah Seeder)

| Role | Email | Password |
|------|-------|----------|
| Admin | `admin@preloved.mhs` | `admin123` |
| Penjual | `penjual@demo.com` | `password` |
| Pembeli | `pembeli@demo.com` | `password` |

---

## Tech Stack

| Layer | Teknologi |
|-------|-----------|
| Backend | Laravel 12 (PHP 8.2+) |
| Frontend | Blade Template, Bootstrap / Tailwind CSS |
| Database | MySQL |
| Build Tool | Vite + NPM |
| Auth | Session-based dengan Middleware Role |
| Email | Laravel Mail (SMTP) |

---

## Struktur Direktori Utama

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/          # Controller khusus admin
│   │   ├── Auth/           # Forgot & reset password
│   │   ├── Penjual/        # Dashboard penjual
│   │   ├── AuthController.php
│   │   ├── HomeController.php
│   │   ├── ProdukController.php
│   │   ├── TransaksiController.php
│   │   ├── ProfilController.php
│   │   ├── ReviewController.php
│   │   ├── SellerReviewController.php
│   │   └── ChatController.php
│   └── Middleware/
│       ├── IsAdmin.php
│       └── RoleMiddleware.php
├── Models/                 # Eloquent Models
└── helpers.php             # Helper functions

resources/views/
├── admin/                  # Tampilan panel admin
├── auth/                   # Login, register, forgot/reset password
├── layouts/                # Layout utama (app.blade.php)
├── penjual/                # Dashboard penjual
├── produk/                 # Detail, tambah, edit produk
├── seller/                 # Profil & ulasan penjual
├── transaksi/              # Checkout, detail, chat, sukses
└── home.blade.php          # Halaman utama marketplace

database/
├── migrations/             # Skema database
└── seeders/
    ├── DatabaseSeeder.php
    └── SellerReviewSeeder.php
```

---

## Kategori Produk

- Buku & Modul
- Elektronik
- Alat Tulis & Stationery
- Fashion & Pakaian
- Perabot Kos
- Olahraga
- Kendaraan & Aksesori
- Makanan & Minuman
- Jasa & Lainnya

---

## Lisensi

Proyek ini dibuat untuk keperluan tugas akhir semester (UAS) dan bersifat open-source untuk keperluan akademik.

---

> Dikembangkan dengan ❤️ menggunakan [Laravel](https://laravel.com) · Universitas Pembangunan Nasional "Veteran" Yogyakarta
