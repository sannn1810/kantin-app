<?php
// header.php - Template bagian atas halaman
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) . ' - ' : '' ?>Kantin Online</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background: #f4f6f8; color: #2d3436; padding-bottom: 40px; }

        /* Navbar */
        nav { background: linear-gradient(135deg, #ff6b35, #f7931e); color: #fff; padding: 16px 24px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        nav .brand { font-size: 22px; font-weight: 700; text-decoration: none; color: #fff; display: flex; align-items: center; gap: 8px; }
        nav .links { display: flex; gap: 18px; align-items: center; flex-wrap: wrap; }
        nav a { color: #fff; text-decoration: none; font-weight: 500; opacity: 0.95; transition: opacity .2s; }
        nav a:hover { opacity: 1; text-decoration: underline; }
        nav .cart-badge { background: #fff; color: #ff6b35; border-radius: 50%; padding: 2px 8px; font-size: 12px; font-weight: 700; margin-left: 4px; }

        .container { max-width: 1100px; margin: 0 auto; padding: 24px 16px; }

        /* Cards */
        .card { background: #fff; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.06); padding: 20px; }
        h1 { font-size: 26px; margin-bottom: 4px; }
        h2 { font-size: 20px; margin-bottom: 16px; }
        .subtitle { color: #888; margin-bottom: 24px; }

        /* Menu grid */
        .menu-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(230px, 1fr)); gap: 18px; margin-top: 20px; }
        .menu-item { background: #fff; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.06); overflow: hidden; display: flex; flex-direction: column; transition: transform .15s; }
        .menu-item:hover { transform: translateY(-3px); }
        .menu-img { height: 120px; background: linear-gradient(135deg, #ffe5d6, #ffd6a5); display: flex; align-items: center; justify-content: center; font-size: 42px; }
        .menu-body { padding: 14px 16px; display: flex; flex-direction: column; gap: 6px; flex: 1; }
        .menu-cat { font-size: 11px; color: #ff6b35; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; }
        .menu-name { font-size: 17px; font-weight: 600; }
        .menu-price { font-size: 16px; font-weight: 700; color: #2d3436; }
        .menu-stock { font-size: 12px; color: #888; }
        .menu-stock.low { color: #e74c3c; font-weight: 600; }

        /* Forms */
        form { display: flex; flex-direction: column; gap: 12px; }
        input, select, textarea, button { padding: 10px 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; }
        input:focus, select:focus, textarea:focus { outline: none; border-color: #ff6b35; }
        label { font-weight: 600; font-size: 14px; margin-bottom: -6px; }

        /* Buttons */
        .btn { display: inline-block; background: #ff6b35; color: #fff; border: none; padding: 10px 18px; border-radius: 8px; font-weight: 600; cursor: pointer; text-decoration: none; text-align: center; transition: background .15s; font-size: 14px; }
        .btn:hover { background: #e85d2a; }
        .btn-secondary { background: #6c757d; }
        .btn-secondary:hover { background: #5a6268; }
        .btn-danger { background: #e74c3c; }
        .btn-danger:hover { background: #c0392b; }
        .btn-success { background: #27ae60; }
        .btn-success:hover { background: #219150; }
        .btn-sm { padding: 6px 12px; font-size: 13px; }
        .btn-block { width: 100%; }

        /* Tables */
        table { width: 100%; border-collapse: collapse; margin-top: 14px; }
        th, td { padding: 10px 12px; text-align: left; border-bottom: 1px solid #eee; font-size: 14px; }
        th { background: #f8f9fa; font-weight: 700; color: #555; }
        tr:hover { background: #fafafa; }

        /* Cart */
        .cart-row { display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px solid #eee; gap: 10px; }
        .cart-row:last-child { border-bottom: none; }
        .qty-box { display: flex; align-items: center; gap: 6px; }
        .qty-box input { width: 50px; text-align: center; padding: 6px; }

        .total-row { display: flex; justify-content: space-between; font-size: 18px; font-weight: 700; padding-top: 14px; margin-top: 8px; border-top: 2px solid #ff6b35; }

        /* Alerts */
        .alert { padding: 12px 16px; border-radius: 8px; margin-bottom: 16px; font-size: 14px; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .alert-info { background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }

        .status-badge { padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 700; }
        .status-Menunggu { background: #fff3cd; color: #856404; }
        .status-Diproses { background: #d1ecf1; color: #0c5460; }
        .status-Selesai { background: #d4edda; color: #155724; }
        .status-Dibatalkan { background: #f8d7da; color: #721c24; }

        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        @media (max-width: 700px) { .grid-2 { grid-template-columns: 1fr; } nav { flex-direction: column; align-items: flex-start; gap: 10px; } }

        .empty-state { text-align: center; padding: 40px 20px; color: #999; }
        .empty-state div { font-size: 48px; margin-bottom: 10px; }

        .actions-row { display: flex; gap: 8px; flex-wrap: wrap; }
    </style>
</head>
<body>
<nav>
    <a href="index.php" class="brand">🍽️ Kantin Online</a>
    <div class="links">
        <a href="index.php">Menu</a>
        <a href="keranjang.php">Keranjang
            <?php
            $jumlahKeranjang = 0;
            if (!empty($_SESSION['keranjang'])) {
                foreach ($_SESSION['keranjang'] as $item) $jumlahKeranjang += $item['qty'];
            }
            if ($jumlahKeranjang > 0): ?>
                <span class="cart-badge"><?= $jumlahKeranjang ?></span>
            <?php endif; ?>
        </a>
        <a href="riwayat.php">Riwayat Pesanan</a>
        <?php if (!empty($_SESSION['admin_logged_in'])): ?>
            <a href="admin.php">Admin Panel</a>
            <a href="admin_logout.php">Logout</a>
        <?php else: ?>
            <a href="admin_login.php">Login Admin</a>
        <?php endif; ?>
    </div>
</nav>
<div class="container">
