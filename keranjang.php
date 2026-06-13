<?php
require 'config.php';
$pageTitle = 'Keranjang';

$keranjang = $_SESSION['keranjang'] ?? [];
$total = 0;
foreach ($keranjang as $item) {
    $total += $item['harga'] * $item['qty'];
}

include 'header.php';

if (!empty($_SESSION['flash'])) {
    echo '<div class="alert alert-' . $_SESSION['flash']['type'] . '">' . htmlspecialchars($_SESSION['flash']['msg']) . '</div>';
    unset($_SESSION['flash']);
}
?>

<h1>Keranjang Belanja</h1>
<p class="subtitle">Periksa pesananmu sebelum checkout</p>

<div class="card">
<?php if (empty($keranjang)): ?>
    <div class="empty-state">
        <div>🛒</div>
        <p>Keranjang masih kosong.</p>
        <br>
        <a href="index.php" class="btn">Lihat Menu</a>
    </div>
<?php else: ?>
    <?php foreach ($keranjang as $id => $item): ?>
        <div class="cart-row">
            <div>
                <strong><?= htmlspecialchars($item['nama']) ?></strong><br>
                <span style="color:#888; font-size:13px;"><?= rupiah($item['harga']) ?> x <?= $item['qty'] ?></span>
            </div>
            <div style="display:flex; align-items:center; gap:14px;">
                <form method="post" action="update_keranjang.php" style="display:flex; align-items:center; gap:6px;">
                    <input type="hidden" name="menu_id" value="<?= $id ?>">
                    <input type="number" name="qty" value="<?= $item['qty'] ?>" min="1" class="qty-box">
                    <button type="submit" class="btn btn-sm btn-secondary">Update</button>
                </form>
                <strong><?= rupiah($item['harga'] * $item['qty']) ?></strong>
                <form method="post" action="hapus_keranjang.php">
                    <input type="hidden" name="menu_id" value="<?= $id ?>">
                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    <?php endforeach; ?>

    <div class="total-row">
        <span>Total</span>
        <span><?= rupiah($total) ?></span>
    </div>

    <div style="margin-top: 20px;">
        <a href="checkout.php" class="btn btn-success btn-block">Lanjut ke Checkout</a>
    </div>
<?php endif; ?>
</div>

<?php include 'footer.php'; ?>
