<?php
require 'config.php';
$pageTitle = 'Riwayat Pesanan';

// Pencarian sederhana berdasarkan nama pemesan
$cari = trim($_GET['cari'] ?? '');

if ($cari !== '') {
    $stmt = $pdo->prepare("SELECT * FROM pesanan WHERE nama_pemesan LIKE ? ORDER BY id DESC LIMIT 50");
    $stmt->execute(['%' . $cari . '%']);
} else {
    $stmt = $pdo->query("SELECT * FROM pesanan ORDER BY id DESC LIMIT 50");
}
$pesananList = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'header.php';
?>

<h1>Riwayat Pesanan</h1>
<p class="subtitle">Daftar pesanan terbaru</p>

<div class="card">
    <form method="get" style="flex-direction: row; gap: 10px;">
        <input type="text" name="cari" placeholder="Cari berdasarkan nama pemesan..." value="<?= htmlspecialchars($cari) ?>" style="flex:1;">
        <button type="submit" class="btn btn-sm">Cari</button>
        <?php if ($cari): ?><a href="riwayat.php" class="btn btn-secondary btn-sm">Reset</a><?php endif; ?>
    </form>

    <?php if (empty($pesananList)): ?>
        <div class="empty-state">
            <div>📋</div>
            <p>Belum ada pesanan.</p>
        </div>
    <?php else: ?>
    <table>
        <tr>
            <th>No</th>
            <th>Nama Pemesan</th>
            <th>Meja</th>
            <th>Total</th>
            <th>Status</th>
            <th>Waktu</th>
            <th></th>
        </tr>
        <?php foreach ($pesananList as $p): ?>
        <tr>
            <td>#<?= $p['id'] ?></td>
            <td><?= htmlspecialchars($p['nama_pemesan']) ?></td>
            <td><?= htmlspecialchars($p['meja'] ?: '-') ?></td>
            <td><?= rupiah($p['total']) ?></td>
            <td><span class="status-badge status-<?= $p['status'] ?>"><?= $p['status'] ?></span></td>
            <td><?= date('d M Y, H:i', strtotime($p['created_at'])) ?></td>
            <td><a href="sukses.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-secondary">Detail</a></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
