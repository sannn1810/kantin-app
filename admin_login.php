<?php
require 'config.php';
$pageTitle = 'Login Admin';

if (!empty($_SESSION['admin_logged_in'])) {
    header('Location: admin.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM admin WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $admin['username'];
        header('Location: admin.php');
        exit;
    } else {
        $error = 'Username atau password salah.';
    }
}

include 'header.php';
?>

<div class="card" style="max-width: 400px; margin: 40px auto;">
    <h1>Login Admin</h1>
    <p class="subtitle">Masuk untuk mengelola menu &amp; pesanan</p>

    <?php if ($error): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post">
        <label>Username</label>
        <input type="text" name="username" required autofocus>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit" class="btn" style="margin-top: 10px;">Login</button>
    </form>

    <p style="margin-top: 14px; font-size: 13px; color: #888;">Default: username <code>admin</code> / password <code>admin123</code></p>
</div>

<?php include 'footer.php'; ?>
