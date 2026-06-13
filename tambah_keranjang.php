<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$menuId = (int)($_POST['menu_id'] ?? 0);
$qty = max(1, (int)($_POST['qty'] ?? 1));

$stmt = $pdo->prepare("SELECT * FROM menu WHERE id = ?");
$stmt->execute([$menuId]);
$menu = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$menu) {
    $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Menu tidak ditemukan.'];
    header('Location: index.php');
    exit;
}

if (!isset($_SESSION['keranjang'])) {
    $_SESSION['keranjang'] = [];
}

// Cek stok
$qtyAda = $_SESSION['keranjang'][$menuId]['qty'] ?? 0;
$totalQty = $qtyAda + $qty;

if ($totalQty > $menu['stok']) {
    $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Stok ' . $menu['nama'] . ' tidak cukup. Sisa stok: ' . $menu['stok']];
    header('Location: index.php');
    exit;
}

if (isset($_SESSION['keranjang'][$menuId])) {
    $_SESSION['keranjang'][$menuId]['qty'] += $qty;
} else {
    $_SESSION['keranjang'][$menuId] = [
        'nama' => $menu['nama'],
        'harga' => $menu['harga'],
        'qty' => $qty,
    ];
}

$_SESSION['flash'] = ['type' => 'success', 'msg' => $menu['nama'] . ' ditambahkan ke keranjang.'];
header('Location: index.php');
exit;
