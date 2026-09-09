<?php

require_once dirname(__DIR__, 2) . '/config/database.php';
require_once dirname(__DIR__, 2) . '/includes/auth.php';
require_once dirname(__DIR__, 2) . '/includes/functions.php';

session_start();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm'] ?? '';

    if (empty($username) || empty($password)) {
        $error = 'All fields are required.';
    } elseif (strlen($password) < 8) {
        $error = 'Password must be at least 8 characters.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } else {
        $pdo = getPDO();
        $stmt = $pdo->prepare("SELECT id FROM admins WHERE username = ?");
        $stmt->execute([$username]);

        if ($stmt->fetch()) {
            $error = 'That username is already taken.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO admins (username, password_hash) VALUES (?, ?)");
            $stmt->execute([$username, $hash]);
            $success = 'Account created! <a href="login.php" style="color: #38bdf8;">Log in &rarr;</a>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Paete CMS</title>
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
        .success { background: rgba(34, 197, 94, 0.15); border: 1px solid #22c55e; border-radius: 6px; padding: 8px; font-size: 0.85rem; color: #86efac; margin-bottom: 1rem; }
        .sub { text-align: center; margin-top: 1rem; font-size: 0.85rem; color: #94a3b8; }
        .sub a { color: #38bdf8; text-decoration: none; }
    </style>
</head>
<body>
<div class="card">
    <h1>Create Admin</h1>
    <?php if ($error): ?><div class="error"><?= $error ?></div><?php endif; ?>
    <?php if ($success): ?><div class="success"><?= $success ?></div><?php endif; ?>

    <?php if (!$success): ?>
    <form method="POST">
        <label for="username">Username</label>
        <input type="text" name="username" required value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">

        <label for="password">Password</label>
        <input type="password" name="password" required>

        <label for="confirm">Confirm Password</label>
        <input type="password" name="confirm" required>

        <button type="submit" class="btn">Create Account</button>
    </form>
    <?php endif; ?>
    <p class="sub"><a href="login.php">&larr; Back to Login</a></p>
</div>
</body>
</html>