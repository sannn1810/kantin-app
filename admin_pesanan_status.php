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
$status = $_POST['status'] ?? 'Menunggu';

$validStatus = ['Menunggu', 'Diproses', 'Selesai', 'Dibatalkan'];
if (!in_array($status, $validStatus)) {
    $status = 'Menunggu';
}

$stmt = $pdo->prepare("UPDATE pesanan SET status = ? WHERE id = ?");
$stmt->execute([$status, $id]);

$_SESSION['flash'] = ['type' => 'success', 'msg' => 'Status pesanan #' . $id . ' diperbarui menjadi ' . $status . '.'];
header('Location: admin.php');
exit;
