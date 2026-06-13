<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$keranjang = $_SESSION['keranjang'] ?? [];
if (empty($keranjang)) {
    header('Location: keranjang.php');
    exit;
}

$namaPemesan = trim($_POST['nama_pemesan'] ?? '');
$meja = trim($_POST['meja'] ?? '');

if ($namaPemesan === '') {
    $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Nama pemesan wajib diisi.'];
    header('Location: checkout.php');
    exit;
}

// Validasi ulang stok sebelum simpan
foreach ($keranjang as $id => $item) {
    $stmt = $pdo->prepare("SELECT stok, nama FROM menu WHERE id = ?");
    $stmt->execute([$id]);
    $menu = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$menu || $item['qty'] > $menu['stok']) {
        $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Stok ' . ($menu['nama'] ?? 'item') . ' tidak cukup. Silakan periksa kembali keranjang.'];
        header('Location: keranjang.php');
        exit;
    }
}

$total = 0;
foreach ($keranjang as $item) {
    $total += $item['harga'] * $item['qty'];
}

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("INSERT INTO pesanan (nama_pemesan, meja, total, status) VALUES (?, ?, ?, 'Menunggu')");
    $stmt->execute([$namaPemesan, $meja, $total]);
    $pesananId = $pdo->lastInsertId();

    $stmtItem = $pdo->prepare("INSERT INTO pesanan_item (pesanan_id, menu_id, nama_menu, harga, qty, subtotal) VALUES (?, ?, ?, ?, ?, ?)");
    $stmtStok = $pdo->prepare("UPDATE menu SET stok = stok - ? WHERE id = ?");

    foreach ($keranjang as $id => $item) {
        $subtotal = $item['harga'] * $item['qty'];
        $stmtItem->execute([$pesananId, $id, $item['nama'], $item['harga'], $item['qty'], $subtotal]);
        $stmtStok->execute([$item['qty'], $id]);
    }

    $pdo->commit();

    // Kosongkan keranjang
    unset($_SESSION['keranjang']);
    $_SESSION['last_order_id'] = $pesananId;

    header('Location: sukses.php?id=' . $pesananId);
    exit;

} catch (Exception $e) {
    $pdo->rollBack();
    $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Terjadi kesalahan: ' . $e->getMessage()];
    header('Location: keranjang.php');
    exit;
}
