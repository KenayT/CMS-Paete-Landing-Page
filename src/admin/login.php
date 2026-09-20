<?php

require_once dirname(__DIR__, 2) . '/config/database.php';
require_once dirname(__DIR__, 2) . '/includes/auth.php';
require_once dirname(__DIR__, 2) . '/includes/functions.php';

session_start();

if (isLoggedIn()) {
    header('Location: index.php');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error = 'Username and password are required.';
    } else {
        $pdo = getPDO();
        $stmt = $pdo->prepare("SELECT id, username, password_hash FROM admins WHERE username = ?");
        $stmt->execute([$username]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password_hash'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            header('Location: index.php');
            exit();
        }

        $error = 'Invalid username or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Paete CMS</title>
    <link rel="icon" href="../../assets/logo.png">
    <link rel="stylesheet" href="admin.css">
</head>
<body>
<div class="auth-wrap">
    <div class="passbook">
        <div class="medallion"><img src="../../assets/logo.png" alt="Rural Bank of Paete seal"></div>
        <div class="passbook-card">
            <p class="passbook-kicker">Rural Bank of Paete, Inc. &middot; CMS</p>
            <h1>Admin Login</h1>

            <?php if ($error): ?>
                <div class="alert alert-error"><?= $error ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="field">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required autocomplete="username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
                </div>
                <div class="field">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required autocomplete="current-password">
                </div>
                <button type="submit" class="btn btn-primary btn-block">Log In</button>
            </form>
            <p class="sub"><a href="register.php">Create an admin account &rarr;</a></p>
        </div>
    </div>
</div>
</body>
</html>
