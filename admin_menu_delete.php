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

$stmt = $pdo->prepare("DELETE FROM menu WHERE id = ?");
$stmt->execute([$id]);

$_SESSION['flash'] = ['type' => 'success', 'msg' => 'Menu berhasil dihapus.'];
header('Location: admin.php');
exit;
