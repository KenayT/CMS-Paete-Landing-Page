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

        .header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 28px;
    background: #0b1329;
    border-bottom: 1px solid #1e293b;
}

.brand-link {
    display: flex;
    align-items: center;
    gap: 12px;
    text-decoration: none;
    transition: opacity 0.2s ease;
}

.brand-link:hover {
    opacity: 0.85;
}

.brand-logo {
    width: 38px;
    height: 38px;
    object-fit: contain;
    border-radius: 50%;
}

.brand-title {
    font-size: 1.15rem;
    font-weight: 700;
    color: #3b82f6; /* Matching bank brand blue */
    letter-spacing: -0.01em;
}

.badge {
    background: #1e293b;
    color: #94a3b8;
    font-size: 0.72rem;
    font-weight: 600;
    padding: 2px 7px;
    border-radius: 4px;
    border: 1px solid #334155;
    text-transform: uppercase;
}

.user-info {
    display: flex;
    align-items: center;
    gap: 16px;
    font-size: 0.9rem;
    color: #cbd5e1;
}

.logout-btn {
    color: #f87171;
    text-decoration: none;
    padding: 6px 12px;
    border-radius: 6px;
    transition: all 0.2s ease;
}

.logout-btn:hover {
    background: rgba(239, 68, 68, 0.15);
    color: #ef4444;
}

    </style>
</head>
<body>
    <header class="header">
    <a href="index.php" class="brand-link" title="Dashboard">
        <img src="../../assets/logo.png" alt="Rural Bank of Paete Logo" class="brand-logo">
        <span class="brand-title">Rural Bank of Paete, Inc.</span>
        <span class="badge">CMS</span>
    </a>

    <div class="user-info">
        <span>Logged in as <strong><?= htmlspecialchars($_SESSION['admin_username']) ?></strong></span>
        <a href="logout.php" class="logout-btn" onclick="return confirm('Are you sure you want to log out?');">Log out</a>
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