# Aplikasi Kantin Online (PHP)

Aplikasi sederhana untuk memesan makanan/minuman di kantin, lengkap dengan
panel admin untuk mengelola menu dan status pesanan.

## Fitur
- 📋 Daftar menu dengan kategori (Makanan, Minuman, Snack)
- 🛒 Keranjang belanja (tambah, update qty, hapus item)
- ✅ Checkout & pesanan tersimpan ke database
- 📦 Pengurangan stok otomatis saat pesanan dibuat
- 📜 Riwayat pesanan + pencarian berdasarkan nama
- 🔐 Login admin
- 🛠️ Panel admin: tambah/edit/hapus menu, ubah status pesanan, statistik ringkas

## Kebutuhan
- PHP 7.4 atau lebih baru (sudah termasuk ekstensi `pdo_sqlite`, biasanya aktif secara default)
- Tidak perlu setup MySQL — database menggunakan **SQLite** (file `kantin.db` dibuat otomatis)

## Cara Menjalankan (Lokal)
1. Pastikan PHP terpasang. Cek dengan: `php -v`
2. Masuk ke folder aplikasi:
   ```
   cd kantin-app
   ```
3. Jalankan server bawaan PHP:
   ```
   php -S localhost:8000
   ```
4. Buka browser ke: `http://localhost:8000`

Database `kantin.db` akan otomatis dibuat saat pertama kali diakses, lengkap
dengan beberapa contoh menu.

## Cara Menjalankan di Hosting (Apache/Nginx + PHP)
1. Upload semua file ke folder public hosting (misal `htdocs` atau `public_html`).
2. Pastikan folder tersebut **bisa ditulis (writable)** agar file `kantin.db` bisa dibuat.
3. Akses `index.php` melalui browser.

## Login Admin
- URL: `admin_login.php`
- Username: `admin`
- Password: `admin123`

⚠️ **Disarankan untuk mengganti password default** setelah login pertama kali
(bisa diubah langsung lewat tabel `admin` di database, atau tambahkan fitur
ganti password jika diperlukan).

## Struktur File
```
kantin-app/
├── config.php           # Koneksi & inisialisasi database (SQLite)
├── header.php            # Template bagian atas (navbar + CSS)
├── footer.php             # Template bagian bawah
├── index.php              # Halaman daftar menu (pelanggan)
├── tambah_keranjang.php   # Proses tambah item ke keranjang
├── keranjang.php          # Halaman keranjang
├── update_keranjang.php   # Update qty item di keranjang
├── hapus_keranjang.php    # Hapus item dari keranjang
├── checkout.php           # Form data pemesan
├── proses_pesanan.php     # Simpan pesanan ke database
├── sukses.php             # Halaman konfirmasi pesanan
├── riwayat.php            # Riwayat semua pesanan
├── admin_login.php        # Login admin
├── admin_logout.php       # Logout admin
├── admin.php              # Panel admin (kelola menu & pesanan)
├── admin_menu_save.php    # Tambah/update menu
├── admin_menu_delete.php  # Hapus menu
└── admin_pesanan_status.php # Ubah status pesanan
```

## Catatan Pengembangan Lanjutan
- Untuk produksi, sebaiknya tambahkan validasi & sanitasi input lebih ketat.
- Bisa diganti ke MySQL dengan mengubah `config.php` (ganti DSN PDO ke
  `mysql:host=...;dbname=...` dan sesuaikan tipe data SQL bila perlu).
- Tambahkan upload gambar menu jika diperlukan (saat ini menggunakan ikon emoji).
