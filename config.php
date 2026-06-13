<?php
// config.php - Koneksi database & inisialisasi tabel
// Menggunakan SQLite agar mudah dijalankan tanpa setup MySQL

$dbFile = __DIR__ . '/kantin.db';
$isNew = !file_exists($dbFile);

try {
    $pdo = new PDO('sqlite:' . $dbFile);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}

// Buat tabel jika belum ada
$pdo->exec("
CREATE TABLE IF NOT EXISTS menu (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nama TEXT NOT NULL,
    kategori TEXT NOT NULL DEFAULT 'Makanan',
    harga INTEGER NOT NULL,
    stok INTEGER NOT NULL DEFAULT 0,
    gambar TEXT DEFAULT '',
    created_at TEXT DEFAULT CURRENT_TIMESTAMP
)");

$pdo->exec("
CREATE TABLE IF NOT EXISTS pesanan (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nama_pemesan TEXT NOT NULL,
    meja TEXT DEFAULT '',
    total INTEGER NOT NULL DEFAULT 0,
    status TEXT NOT NULL DEFAULT 'Menunggu',
    created_at TEXT DEFAULT CURRENT_TIMESTAMP
)");

$pdo->exec("
CREATE TABLE IF NOT EXISTS pesanan_item (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    pesanan_id INTEGER NOT NULL,
    menu_id INTEGER NOT NULL,
    nama_menu TEXT NOT NULL,
    harga INTEGER NOT NULL,
    qty INTEGER NOT NULL,
    subtotal INTEGER NOT NULL,
    FOREIGN KEY (pesanan_id) REFERENCES pesanan(id)
)");

$pdo->exec("
CREATE TABLE IF NOT EXISTS admin (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT UNIQUE NOT NULL,
    password TEXT NOT NULL
)");

// Seed data awal jika database baru dibuat
if ($isNew) {
    // Admin default: username = admin, password = admin123
    $hash = password_hash('admin123', PASSWORD_DEFAULT);
    $pdo->prepare("INSERT INTO admin (username, password) VALUES (?, ?)")
        ->execute(['admin', $hash]);

    $menuAwal = [
        ['Nasi Goreng', 'Makanan', 15000, 20],
        ['Mie Ayam', 'Makanan', 12000, 15],
        ['Es Teh Manis', 'Minuman', 5000, 50],
        ['Es Jeruk', 'Minuman', 6000, 40],
        ['Kerupuk', 'Snack', 2000, 100],
        ['Ayam Geprek', 'Makanan', 17000, 10],
    ];
    $stmt = $pdo->prepare("INSERT INTO menu (nama, kategori, harga, stok) VALUES (?, ?, ?, ?)");
    foreach ($menuAwal as $m) {
        $stmt->execute($m);
    }
}

// Helper format rupiah
function rupiah($angka) {
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

// Mulai session untuk keranjang & login admin
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
