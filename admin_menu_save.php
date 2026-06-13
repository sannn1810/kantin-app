<?php
require 'config.php';

if (empty($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: admin.php');
    exit;
}

$id = (int)($_POST['id'] ?? 0);
$nama = trim($_POST['nama'] ?? '');
$kategori = trim($_POST['kategori'] ?? 'Makanan');
$harga = (int)($_POST['harga'] ?? 0);
$stok = (int)($_POST['stok'] ?? 0);

if ($nama === '' || $harga < 0 || $stok < 0) {
    $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Data menu tidak valid.'];
    header('Location: admin.php');
    exit;
}

if ($id > 0) {
    // Update
    $stmt = $pdo->prepare("UPDATE menu SET nama = ?, kategori = ?, harga = ?, stok = ? WHERE id = ?");
    $stmt->execute([$nama, $kategori, $harga, $stok, $id]);
    $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Menu berhasil diperbarui.'];
} else {
    // Insert baru
    $stmt = $pdo->prepare("INSERT INTO menu (nama, kategori, harga, stok) VALUES (?, ?, ?, ?)");
    $stmt->execute([$nama, $kategori, $harga, $stok]);
    $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Menu baru berhasil ditambahkan.'];
}

header('Location: admin.php');
exit;
