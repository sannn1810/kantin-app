<?php
require 'config.php';
$pageTitle = 'Checkout';

$keranjang = $_SESSION['keranjang'] ?? [];
if (empty($keranjang)) {
    header('Location: keranjang.php');
    exit;
}

$total = 0;
foreach ($keranjang as $item) {
    $total += $item['harga'] * $item['qty'];
}

include 'header.php';
?>

<h1>Checkout</h1>
<p class="subtitle">Lengkapi data pemesanan</p>

<div class="grid-2">
    <div class="card">
        <h2>Ringkasan Pesanan</h2>
        <?php foreach ($keranjang as $item): ?>
            <div class="cart-row">
                <span><?= htmlspecialchars($item['nama']) ?> x <?= $item['qty'] ?></span>
                <span><?= rupiah($item['harga'] * $item['qty']) ?></span>
            </div>
        <?php endforeach; ?>
        <div class="total-row">
            <span>Total Bayar</span>
            <span><?= rupiah($total) ?></span>
        </div>
    </div>

    <div class="card">
        <h2>Data Pemesan</h2>
        <form method="post" action="proses_pesanan.php">
            <label>Nama Pemesan</label>
            <input type="text" name="nama_pemesan" required placeholder="Masukkan nama Anda">

            <label>Nomor Meja (opsional)</label>
            <input type="text" name="meja" placeholder="Contoh: A1, atau kosongkan jika take away">

            <button type="submit" class="btn btn-success" style="margin-top: 10px;">Buat Pesanan</button>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>
