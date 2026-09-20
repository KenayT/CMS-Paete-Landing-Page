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
    <title>Dashboard - Paete CMS</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: system-ui, sans-serif; background: #0f172a; color: #f8fafc; }
        header { background: #1e293b; border-bottom: 1px solid #334155; padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; }
        header h1 { font-size: 1.1rem; }
        header a { color: #ef4444; text-decoration: none; margin-left: 1rem; }
        main { max-width: 960px; margin: 2rem auto; padding: 0 1rem; }
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 1rem; margin-top: 1.5rem; }
        .section-card { background: #1e293b; border: 1px solid #334155; border-radius: 10px; padding: 1.2rem; text-decoration: none; color: inherit; transition: border-color 0.2s; }
        .section-card:hover { border-color: #38bdf8; }
        .section-card h3 { text-transform: capitalize; margin-bottom: 0.5rem; font-size: 1.1rem; }
        .meta { font-size: 0.75rem; color: #94a3b8; }
    </style>
</head>
<body>
    <header>
        <h1>Rural Bank of Paete CMS</h1>
        <div>
            Logged in as <strong><?= $admin ?></strong>
            <a href="logout.php">Log out</a>
        </div>
    </header>
    <main>
        <h2>Page Sections</h2>
        <div class="grid">
            <?php foreach ($sections as $row): ?>
            <a class="section-card" href="sections/<?= $row['section'] ?>.php">
                <h3><?= htmlspecialchars($row['section']) ?></h3>
                <div class="meta">Updated: <?= $row['updated_at'] ?></div>
            </a>
            <?php endforeach; ?>
        </div>
    </main>
</body>
</html>