# Prompt Revisi Proyek SecondLife (UASsecondlife)

Berikut adalah kumpulan *prompt* yang sudah disusun secara detail untuk membantu implementasi revisi pada *codebase* proyekmu. Kamu dapat langsung menyalin dan menempelkan (*copy-paste*) prompt ini ke AI assistant (seperti Cursor, GitHub Copilot, atau ChatGPT) untuk memperbaiki atau menambahkan fitur.

## 1. Approval (ACC) Penjual oleh Admin
**Prompt:**
> "Tolong buatkan fitur *approval* (acc) akun penjual di proyek saya. Tambahkan kolom status pada database (misalnya `status_akun` dengan nilai default 'pending' saat registrasi). Selanjutnya, buatkan halaman khusus di Dashboard Admin untuk menampilkan daftar penjual dengan status 'pending', lengkap dengan tombol aksi untuk 'Approve' (mengizinkan login/jualan) atau 'Reject'."

## 2. Edit Jadwal COD Tanpa Menghapus Transaksi Lama
**Prompt:**
> "Buatkan fitur untuk mengedit atau me-*reschedule* jadwal COD pada transaksi yang sedang berjalan. Tolong pastikan fungsi ini hanya menggunakan query `UPDATE` untuk mengubah waktu atau tempat COD pada baris transaksi yang sama di database, sehingga riwayat atau data transaksi lamanya tidak perlu dihapus."

## 3. Memperbaiki Duplikasi Chat WhatsApp di Pembeli
**Prompt:**
> "Di antarmuka halaman pembeli proyek saya, saat ini tombol atau tautan untuk *chat* negosiasi via WhatsApp muncul dua kali (terduplikasi). Tolong periksa file view/UI pembeli dan hapus salah satu tombol tersebut. Pastikan satu tombol yang tersisa berfungsi mengarahkan pembeli langsung ke nomor WhatsApp penjual dengan benar."

## 4. Tanda Bukti Foto untuk Menyelesaikan Transaksi
**Prompt:**
> "Tolong modifikasi alur penyelesaian transaksi. Sesuai alur terbaru, saat ini transaksi langsung selesai tanpa sistem *rating*. Namun, sekarang saya ingin menambahkan syarat wajib unggah (*upload*) 'tanda bukti foto' (foto saat COD) sebelum status transaksi benar-benar bisa diubah menjadi 'selesai'. Buatkan form upload-nya, fungsi simpan gambar, dan tambahkan kolom `foto_bukti` di tabel transaksi database."

## 5. Menghapus Menu Dashboard dan Jual Barang di Navbar/Atas
**Prompt:**
> "Tolong hapus atau sembunyikan elemen navigasi untuk menu 'Dashboard' dan 'Jual Barang' yang berada di bagian atas (header/navbar) pada file antarmuka utama agar tampilannya lebih bersih."

## 6 & 7. Fitur Hapus Produk oleh Penjual
**Prompt:**
> "Tolong buatkan fungsi 'Hapus Produk' di halaman manajemen produk untuk akses penjual. Pastikan query penghapusan (DELETE) di database merujuk pada id produk dan memvalidasi kepemilikannya berdasarkan kolom `id_user` yang sedang *login*. Selain menghapus baris dari database, pastikan script juga menghapus file gambar fisik dari *storage*."

## 8. Laporan Grafik di Bagian Admin
**Prompt:**
> "Tolong buatkan fitur laporan berbasis grafik di halaman *dashboard* Admin menggunakan *library* JavaScript (seperti Chart.js atau ApexCharts). Grafik tersebut harus menarik data dari database untuk memvisualisasikan metrik penting, seperti tren jumlah transaksi per bulan, jumlah *listing* produk yang aktif, dan pertumbuhan *user*."

