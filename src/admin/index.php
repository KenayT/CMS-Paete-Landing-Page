<?php

require_once dirname(__DIR__, 2) . '/config/database.php';
require_once dirname(__DIR__, 2) . '/includes/auth.php';
require_once dirname(__DIR__, 2) . '/includes/functions.php';

session_start();
requireLogin();

$admin = sanitize($_SESSION['admin_username'] ?? 'Admin');
$pdo = getPDO();

$stmt = $pdo->query("SELECT section, updated_at FROM page_sections ORDER BY section");
$sections = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Paete CMS</title>
    <link rel="icon" href="../../assets/logo.png">
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <header class="topbar">
        <a class="brand" href="index.php">
            <span class="brand-mark"><img src="../../assets/logo.png" alt="Rural Bank of Paete seal"></span>
            <span class="brand-name">Rural Bank of Paete CMS</span>
        </a>
        <nav>
            <span class="who">Logged in as <strong><?= $admin ?></strong></span>
            <a class="logout" href="logout.php">Log out</a>
        </nav>
    </header>

    <main class="shell">
        <div class="page-heading">
            <h1>Page Sections</h1>
            <p>Manage the content blocks that appear on the public site.</p>
        </div>

        <div class="ledger">
            <?php foreach ($sections as $row): ?>
            <a class="ledger-row" href="sections/<?= $row['section'] ?>.php">
                <h3><?= htmlspecialchars($row['section']) ?></h3>
                <span class="meta">Updated <?= $row['updated_at'] ?></span>
            </a>
            <?php endforeach; ?>
        </div>
    </main>
</body>
</html>
