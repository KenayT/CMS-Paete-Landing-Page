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
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: system-ui, sans-serif; background: #0f172a; color: #f8fafc; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .card { background: #1e293b; border: 1px solid #334155; border-radius: 12px; padding: 32px; width: 100%; max-width: 380px; }
        h1 { font-size: 1.5rem; margin-bottom: 1.5rem; text-align: center; }
        label { display: block; font-size: 0.85rem; margin-bottom: 4px; color: #94a3b8; }
        input[type="text"], input[type="password"] { width: 100%; padding: 10px; background: #0f172a; border: 1px solid #334155; border-radius: 6px; color: #f8fafc; font-size: 1rem; margin-bottom: 1rem; }
        input:focus { outline: none; border-color: #38bdf8; }
        .btn { width: 100%; padding: 10px; background: #0284c7; border: none; border-radius: 6px; color: #fff; font-weight: 600; cursor: pointer; }
        .btn:hover { background: #0369a1; }
        .error { background: rgba(239, 68, 68, 0.15); border: 1px solid #ef4444; border-radius: 6px; padding: 8px; font-size: 0.85rem; color: #fca5a5; margin-bottom: 1rem; }
        .sub { text-align: center; margin-top: 1rem; font-size: 0.85rem; color: #94a3b8; }
        .sub a { color: #38bdf8; text-decoration: none; }
    </style>
</head>
<body>
<div class="card">
    <h1>Admin Login</h1>
    <?php if ($error): ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>
    <form method="POST">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required autocomplete="username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required autocomplete="current-password">

        <button type="submit" class="btn">Log In</button>
    </form>
    <p class="sub"><a href="register.php">Create an admin account &rarr;</a></p>
</div>
</body>
</html>