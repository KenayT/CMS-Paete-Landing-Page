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
            $success = 'Account created! <a href="login.php">Log in &rarr;</a>';
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
    <link rel="icon" href="../../assets/logo.png">
    <link rel="stylesheet" href="admin.css">
</head>
<body>
<div class="auth-wrap">
    <div class="passbook">
        <div class="medallion"><img src="../../assets/logo.png" alt="Rural Bank of Paete seal"></div>
        <div class="passbook-card">
            <p class="passbook-kicker">Rural Bank of Paete, Inc. &middot; CMS</p>
            <h1>Create Admin</h1>

            <?php if ($error): ?><div class="alert alert-error"><?= $error ?></div><?php endif; ?>
            <?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>

            <?php if (!$success): ?>
            <form method="POST">
                <div class="field">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
                </div>
                <div class="field">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="field">
                    <label for="confirm">Confirm Password</label>
                    <input type="password" id="confirm" name="confirm" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Create Account</button>
            </form>
            <?php endif; ?>
            <p class="sub"><a href="login.php">&larr; Back to Login</a></p>
        </div>
    </div>
</div>
</body>
</html>
