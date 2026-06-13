<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: keranjang.php');
    exit;
}

$menuId = (int)($_POST['menu_id'] ?? 0);

if (isset($_SESSION['keranjang'][$menuId])) {
    unset($_SESSION['keranjang'][$menuId]);
}

$_SESSION['flash'] = ['type' => 'info', 'msg' => 'Item dihapus dari keranjang.'];
header('Location: keranjang.php');
exit;
