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
    <title>Edit: Header - Paete CMS</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: system-ui, sans-serif; background: #0b1120; color: #f8fafc; }
        header { background: #0f172a; border-bottom: 1px solid #1e293b; padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; }
        header h1 { font-size: 1.1rem; }
        header a { color: #94a3b8; text-decoration: none; margin-left: 1.2rem; }
        header a:hover { color: #38bdf8; }
        main { max-width: 860px; margin: 2rem auto; padding: 0 1rem 4rem; }
        .page-title { font-size: 1.5rem; margin-bottom: 1.5rem; }
        .card { background: #0f172a; border: 1px solid #1e293b; border-radius: 10px; padding: 1.4rem; margin-bottom: 1.5rem; }
        .card-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.2rem; }
        .card-header h2 { font-size: 1rem; }
        .badge { font-size: 0.7rem; font-weight: 700; padding: 3px 8px; border-radius: 20px; text-transform: uppercase; }
        .badge-update { background: #1e3a8a; color: #93c5fd; border: 1px solid #1d4ed8; }
        .badge-crud { background: #064e3b; color: #6ee7b7; border: 1px solid #047857; }
        .field { margin-bottom: 0.9rem; }
        .field label { display: block; font-size: 0.78rem; color: #64748b; margin-bottom: 3px; }
        input[type="text"] { width: 100%; padding: 8px 10px; background: #020617; border: 1px solid #1e293b; border-radius: 6px; color: #f8fafc; font-size: 0.9rem; }
        input:focus { outline: none; border-color: #38bdf8; }
        .btn { padding: 7px 14px; border: none; border-radius: 6px; font-weight: 500; cursor: pointer; font-size: 0.85rem; }
        .btn-blue { background: #0284c7; color: #fff; }
        .btn-blue:hover { background: #0369a1; }
        .btn-green { background: #059669; color: #fff; }
        .btn-ghost { background: transparent; border: 1px solid #ef4444; color: #ef4444; }
        .btn-ghost:hover { background: rgba(239, 68, 68, 0.1); }
        .link-item { display: grid; grid-template-columns: 1fr 1fr auto auto; gap: 8px; margin-bottom: 8px; align-items: center; }
        .add-row { display: grid; grid-template-columns: 1fr 1fr auto; gap: 8px; margin-top: 1rem; padding-top: 1rem; border-top: 1px dashed #1e293b; }
        #toast { position: fixed; bottom: 1.5rem; right: 1.5rem; padding: 10px 18px; border-radius: 6px; font-weight: 500; font-size: 0.85rem; opacity: 0; transition: opacity 0.25s; z-index: 9999; }
        #toast.show { opacity: 1; }
        #toast.success { background: #059669; color: #ecfdf5; }
        #toast.error { background: rgba(239, 68, 68, 0.2); color: #f87171; border: 1px solid #ef4444; }
    </style>
</head>
<body>
    <header>
        <h1>Paete CMS</h1>
        <div>
            <a href="../index.php">&larr; Dashboard</a>
            <a href="../logout.php">Log out</a>
        </div>
    </header>

    <main>
        <h1 class="page-title">Edit: Header</h1>
        <div class="card" id="card-logo"></div>
        <div class="card" id="card-links"></div>
    </main>

    <div id="toast"></div>

    <script type="module" src="../pages/header.js?v=2"></script>
</body>
</html>