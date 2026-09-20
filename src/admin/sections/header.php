<?php

require_once dirname(__DIR__, 3) . '/includes/auth.php';
require_once dirname(__DIR__, 3) . '/includes/functions.php';

session_start();
requireLogin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit: Header - Paete CMS</title>
    <link rel="icon" href="../../../assets/logo.png">
    <link rel="stylesheet" href="../admin.css">
</head>
<body>
    <header class="topbar">
        <a class="brand" href="../index.php">
            <span class="brand-mark"><img src="../../../assets/logo.png" alt="Rural Bank of Paete seal"></span>
            <span class="brand-name">Paete CMS</span>
        </a>
        <nav>
            <a href="../index.php">&larr; Dashboard</a>
            <a class="logout" href="../logout.php">Log out</a>
        </nav>
    </header>

    <main class="shell">
        <div class="page-heading">
            <h1>Edit: Header</h1>
        </div>
        <div class="panel" id="card-logo"></div>
        <div class="panel" id="card-links"></div>
    </main>

    <div id="toast"></div>

    <script type="module" src="../pages/header.js?v=3"></script>
</body>
</html>
