<?php
require 'config.php';
$pageTitle = 'Pesanan Berhasil';

$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM pesanan WHERE id = ?");
$stmt->execute([$id]);
$pesanan = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pesanan) {
    header('Location: index.php');
    exit;
}

$stmtItem = $pdo->prepare("SELECT * FROM pesanan_item WHERE pesanan_id = ?");
$stmtItem->execute([$id]);
$items = $stmtItem->fetchAll(PDO::FETCH_ASSOC);

include 'header.php';
?>

<div class="card" style="text-align:center; max-width: 500px; margin: 0 auto;">
    <div style="font-size: 60px;">✅</div>
    <h1>Pesanan Berhasil Dibuat!</h1>
    <p class="subtitle">Nomor Pesanan: <strong>#<?= $pesanan['id'] ?></strong></p>

    <div style="text-align:left; margin-top: 20px;">
        <p><strong>Nama:</strong> <?= htmlspecialchars($pesanan['nama_pemesan']) ?></p>
        <?php if ($pesanan['meja']): ?>
        <p><strong>Meja:</strong> <?= htmlspecialchars($pesanan['meja']) ?></p>
        <?php endif; ?>
        <p><strong>Status:</strong> <span class="status-badge status-<?= $pesanan['status'] ?>"><?= $pesanan['status'] ?></span></p>

        <hr style="margin: 14px 0; border: none; border-top: 1px solid #eee;">

        <?php foreach ($items as $item): ?>
            <div class="cart-row">
                <span><?= htmlspecialchars($item['nama_menu']) ?> x <?= $item['qty'] ?></span>
                <span><?= rupiah($item['subtotal']) ?></span>
            </div>
        <?php endforeach; ?>

        <div class="total-row">
            <span>Total</span>
            <span><?= rupiah($pesanan['total']) ?></span>
        </div>
    </div>

    <p style="margin-top: 20px; color:#888; font-size: 14px;">Silakan tunjukkan nomor pesanan ini ke kasir untuk pembayaran.</p>

    <div style="margin-top: 20px; display:flex; gap: 10px;">
        <a href="index.php" class="btn btn-block">Pesan Lagi</a>
        <a href="riwayat.php" class="btn btn-secondary btn-block">Lihat Riwayat</a>
    </div>
</div>

<?php include 'footer.php'; ?>
