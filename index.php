<?php
require 'config.php';
$pageTitle = 'Menu';

// Ambil filter kategori
$kategori = $_GET['kategori'] ?? '';
if ($kategori) {
    $stmt = $pdo->prepare("SELECT * FROM menu WHERE kategori = ? ORDER BY nama ASC");
    $stmt->execute([$kategori]);
} else {
    $stmt = $pdo->query("SELECT * FROM menu ORDER BY kategori ASC, nama ASC");
}
$menus = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Ambil daftar kategori unik
$kategoriList = $pdo->query("SELECT DISTINCT kategori FROM menu ORDER BY kategori ASC")->fetchAll(PDO::FETCH_COLUMN);

// Ikon per kategori
$ikon = ['Makanan' => '🍛', 'Minuman' => '🥤', 'Snack' => '🍪'];

include 'header.php';

if (!empty($_SESSION['flash'])) {
    echo '<div class="alert alert-' . $_SESSION['flash']['type'] . '">' . htmlspecialchars($_SESSION['flash']['msg']) . '</div>';
    unset($_SESSION['flash']);
}
?>

<h1>Daftar Menu Kantin</h1>
<p class="subtitle">Pilih menu favoritmu dan tambahkan ke keranjang</p>

<div class="actions-row" style="margin-bottom: 10px;">
    <a href="index.php" class="btn <?= $kategori == '' ? '' : 'btn-secondary' ?> btn-sm">Semua</a>
    <?php foreach ($kategoriList as $k): ?>
        <a href="index.php?kategori=<?= urlencode($k) ?>" class="btn <?= $kategori == $k ? '' : 'btn-secondary' ?> btn-sm"><?= htmlspecialchars($k) ?></a>
    <?php endforeach; ?>
</div>

<?php if (empty($menus)): ?>
    <div class="empty-state">
        <div>🍽️</div>
        <p>Belum ada menu yang tersedia.</p>
    </div>
<?php else: ?>
<div class="menu-grid">
    <?php foreach ($menus as $m): ?>
        <div class="menu-item">
            <div class="menu-img"><?= $ikon[$m['kategori']] ?? '🍴' ?></div>
            <div class="menu-body">
                <span class="menu-cat"><?= htmlspecialchars($m['kategori']) ?></span>
                <span class="menu-name"><?= htmlspecialchars($m['nama']) ?></span>
                <span class="menu-price"><?= rupiah($m['harga']) ?></span>
                <span class="menu-stock <?= $m['stok'] <= 5 ? 'low' : '' ?>">
                    <?= $m['stok'] > 0 ? 'Stok: ' . $m['stok'] : 'Stok habis' ?>
                </span>
                <?php if ($m['stok'] > 0): ?>
                <form method="post" action="tambah_keranjang.php" style="margin-top: 8px;">
                    <input type="hidden" name="menu_id" value="<?= $m['id'] ?>">
                    <div style="display:flex; gap:8px;">
                        <input type="number" name="qty" value="1" min="1" max="<?= $m['stok'] ?>" style="width: 70px;">
                        <button type="submit" class="btn btn-sm" style="flex:1;">+ Keranjang</button>
                    </div>
                </form>
                <?php else: ?>
                    <button class="btn btn-sm btn-secondary" disabled style="margin-top: 8px;">Stok Habis</button>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<?php include 'footer.php'; ?>
