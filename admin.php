<?php
require 'config.php';
$pageTitle = 'Admin Panel';

if (empty($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit;
}

// Jika ada parameter edit, ambil data menu untuk form edit
$editMenu = null;
if (!empty($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM menu WHERE id = ?");
    $stmt->execute([(int)$_GET['edit']]);
    $editMenu = $stmt->fetch(PDO::FETCH_ASSOC);
}

$menus = $pdo->query("SELECT * FROM menu ORDER BY kategori ASC, nama ASC")->fetchAll(PDO::FETCH_ASSOC);
$pesananList = $pdo->query("SELECT * FROM pesanan ORDER BY id DESC LIMIT 30")->fetchAll(PDO::FETCH_ASSOC);

// Statistik singkat
$totalPesanan = $pdo->query("SELECT COUNT(*) FROM pesanan")->fetchColumn();
$totalPendapatan = $pdo->query("SELECT SUM(total) FROM pesanan WHERE status != 'Dibatalkan'")->fetchColumn() ?: 0;
$pesananMenunggu = $pdo->query("SELECT COUNT(*) FROM pesanan WHERE status = 'Menunggu'")->fetchColumn();

include 'header.php';

if (!empty($_SESSION['flash'])) {
    echo '<div class="alert alert-' . $_SESSION['flash']['type'] . '">' . htmlspecialchars($_SESSION['flash']['msg']) . '</div>';
    unset($_SESSION['flash']);
}
?>

<h1>Admin Panel</h1>
<p class="subtitle">Halo, <?= htmlspecialchars($_SESSION['admin_username']) ?>! Kelola menu dan pesanan kantin di sini.</p>

<!-- Statistik -->
<div class="menu-grid" style="margin-bottom: 24px;">
    <div class="card"><strong>Total Pesanan</strong><h2 style="margin-top:8px;"><?= $totalPesanan ?></h2></div>
    <div class="card"><strong>Pesanan Menunggu</strong><h2 style="margin-top:8px; color:#856404;"><?= $pesananMenunggu ?></h2></div>
    <div class="card"><strong>Total Pendapatan</strong><h2 style="margin-top:8px; color:#27ae60;"><?= rupiah($totalPendapatan) ?></h2></div>
</div>

<div class="grid-2">
    <!-- Form Tambah/Edit Menu -->
    <div class="card">
        <h2><?= $editMenu ? 'Edit Menu' : 'Tambah Menu Baru' ?></h2>
        <form method="post" action="admin_menu_save.php">
            <?php if ($editMenu): ?>
                <input type="hidden" name="id" value="<?= $editMenu['id'] ?>">
            <?php endif; ?>

            <label>Nama Menu</label>
            <input type="text" name="nama" required value="<?= $editMenu ? htmlspecialchars($editMenu['nama']) : '' ?>">

            <label>Kategori</label>
            <select name="kategori">
                <?php foreach (['Makanan', 'Minuman', 'Snack'] as $k): ?>
                    <option value="<?= $k ?>" <?= ($editMenu && $editMenu['kategori'] == $k) ? 'selected' : '' ?>><?= $k ?></option>
                <?php endforeach; ?>
            </select>

            <label>Harga (Rp)</label>
            <input type="number" name="harga" required min="0" value="<?= $editMenu ? $editMenu['harga'] : '' ?>">

            <label>Stok</label>
            <input type="number" name="stok" required min="0" value="<?= $editMenu ? $editMenu['stok'] : '' ?>">

            <button type="submit" class="btn" style="margin-top: 6px;"><?= $editMenu ? 'Update Menu' : 'Tambah Menu' ?></button>
            <?php if ($editMenu): ?>
                <a href="admin.php" class="btn btn-secondary">Batal Edit</a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Daftar Menu -->
    <div class="card">
        <h2>Daftar Menu</h2>
        <div style="max-height: 380px; overflow-y: auto;">
        <table>
            <tr><th>Nama</th><th>Kategori</th><th>Harga</th><th>Stok</th><th></th></tr>
            <?php foreach ($menus as $m): ?>
            <tr>
                <td><?= htmlspecialchars($m['nama']) ?></td>
                <td><?= htmlspecialchars($m['kategori']) ?></td>
                <td><?= rupiah($m['harga']) ?></td>
                <td><?= $m['stok'] ?></td>
                <td class="actions-row">
                    <a href="admin.php?edit=<?= $m['id'] ?>" class="btn btn-sm btn-secondary">Edit</a>
                    <form method="post" action="admin_menu_delete.php" onsubmit="return confirm('Hapus menu ini?');" style="display:inline;">
                        <input type="hidden" name="id" value="<?= $m['id'] ?>">
                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        </div>
    </div>
</div>

<!-- Daftar Pesanan -->
<div class="card" style="margin-top: 24px;">
    <h2>Pesanan Terbaru</h2>
    <?php if (empty($pesananList)): ?>
        <div class="empty-state"><div>📋</div><p>Belum ada pesanan.</p></div>
    <?php else: ?>
    <table>
        <tr><th>No</th><th>Nama</th><th>Meja</th><th>Total</th><th>Status</th><th>Waktu</th><th>Ubah Status</th></tr>
        <?php foreach ($pesananList as $p): ?>
        <tr>
            <td>#<?= $p['id'] ?></td>
            <td><?= htmlspecialchars($p['nama_pemesan']) ?></td>
            <td><?= htmlspecialchars($p['meja'] ?: '-') ?></td>
            <td><?= rupiah($p['total']) ?></td>
            <td><span class="status-badge status-<?= $p['status'] ?>"><?= $p['status'] ?></span></td>
            <td><?= date('d M Y, H:i', strtotime($p['created_at'])) ?></td>
            <td>
                <form method="post" action="admin_pesanan_status.php" style="display:flex; gap:6px;">
                    <input type="hidden" name="id" value="<?= $p['id'] ?>">
                    <select name="status">
                        <?php foreach (['Menunggu', 'Diproses', 'Selesai', 'Dibatalkan'] as $s): ?>
                            <option value="<?= $s ?>" <?= $p['status'] == $s ? 'selected' : '' ?>><?= $s ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" class="btn btn-sm">Simpan</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
