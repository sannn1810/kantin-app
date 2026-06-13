<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: keranjang.php');
    exit;
}

$menuId = (int)($_POST['menu_id'] ?? 0);
$qty = max(1, (int)($_POST['qty'] ?? 1));

// Cek stok tersedia
$stmt = $pdo->prepare("SELECT stok FROM menu WHERE id = ?");
$stmt->execute([$menuId]);
$menu = $stmt->fetch(PDO::FETCH_ASSOC);

if ($menu && isset($_SESSION['keranjang'][$menuId])) {
    if ($qty > $menu['stok']) {
        $qty = $menu['stok'];
        $_SESSION['flash'] = ['type' => 'info', 'msg' => 'Jumlah disesuaikan dengan stok yang tersedia (' . $menu['stok'] . ').'];
    }
    $_SESSION['keranjang'][$menuId]['qty'] = $qty;
}

header('Location: keranjang.php');
exit;
