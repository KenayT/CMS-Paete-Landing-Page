<?php
require_once dirname(__DIR__, 3) . '/config/database.php';
require_once dirname(__DIR__, 3) . '/includes/auth.php';
require_once dirname(__DIR__, 3) . '/includes/functions.php';

requireLogin();

$pdo = getPDO();
$section = 'hero';
$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $updatedContent = [
        'bgImage' => sanitize($_POST['bgImage'] ?? ''),
        'vectorImage' => sanitize($_POST['vectorImage'] ?? ''),
        'headline' => sanitize($_POST['headline'] ?? ''),
        'headlineHighlight' => sanitize($_POST['headlineHighlight'] ?? ''),
        'description' => sanitize($_POST['description'] ?? ''),
        'badgeText' => sanitize($_POST['badgeText'] ?? ''),
        'ctaPrimary' => [
            'text' => sanitize($_POST['ctaPrimary_text'] ?? ''),
            'url' => sanitize($_POST['ctaPrimary_url'] ?? '')
        ],
        'ctaSecondary' => [
            'text' => sanitize($_POST['ctaSecondary_text'] ?? ''),
            'url' => sanitize($_POST['ctaSecondary_url'] ?? '')
        ]
    ];

    try {
        saveContent($pdo, $section, $updatedContent);
        $success = true;
    } catch (Exception $e) {
        $error = 'Failed to update section: ' . $e->getMessage();
    }
}

$content = getSectionContent($pdo, $section);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Hero Section - CMS</title>
    <link rel="icon" href="../../../assets/logo.png" type="image/png" />
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', system-ui, sans-serif; background: #070d1e; color: #f8fafc; padding: 2rem 1rem; }
        .container { max-width: 760px; margin: 0 auto; background: #0f172a; border: 1px solid #1e293b; border-radius: 12px; padding: 2rem; box-shadow: 0 10px 25px rgba(0,0,0,0.4); }
        .header-nav { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid #1e293b; }
        .back-link { color: #38bdf8; text-decoration: none; font-size: 0.9rem; font-weight: 500; }
        .back-link:hover { text-decoration: underline; }
        h1 { font-size: 1.5rem; font-weight: 700; color: #f1f5f9; }
        .alert { padding: 0.75rem 1rem; border-radius: 8px; margin-bottom: 1.25rem; font-size: 0.9rem; }
        .alert-success { background: #064e3b; color: #6ee7b7; border: 1px solid #047857; }
        .alert-error { background: #4c0519; color: #fda4af; border: 1px solid #be123c; }
        .form-group { margin-bottom: 1.25rem; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        label { display: block; font-size: 0.85rem; font-weight: 600; color: #94a3b8; margin-bottom: 0.4rem; text-transform: uppercase; letter-spacing: 0.05em; }
        input[type="text"], textarea { width: 100%; padding: 0.65rem 0.85rem; background: #070d1e; border: 1px solid #334155; border-radius: 6px; color: #f8fafc; font-size: 0.95rem; }
        input[type="text"]:focus, textarea:focus { border-color: #38bdf8; outline: none; }
        textarea { resize: vertical; min-height: 90px; }
        .sub-box { background: #070d1e; border: 1px solid #1e293b; padding: 1rem; border-radius: 8px; margin-bottom: 1.25rem; }
        .sub-box-title { font-size: 0.85rem; font-weight: 700; color: #38bdf8; margin-bottom: 0.75rem; text-transform: uppercase; }
        .btn-submit { width: 100%; padding: 0.85rem; background: #0284c7; color: white; border: none; border-radius: 6px; font-weight: 600; font-size: 1rem; cursor: pointer; transition: background 0.2s; }
        .btn-submit:hover { background: #0369a1; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header-nav">
            <h1>Edit Hero / Banner Section</h1>
            <a href="../index.php" class="back-link">← Back to Dashboard</a>
        </div>

        <?php if ($success): ?>
            <div class="alert alert-success">Hero section updated successfully!</div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="grid-2">
                <div class="form-group">
                    <label>Headline</label>
                    <input type="text" name="headline" value="<?= htmlspecialchars($content['headline'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label>Headline Highlight (Yellow Text)</label>
                    <input type="text" name="headlineHighlight" value="<?= htmlspecialchars($content['headlineHighlight'] ?? '') ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label>Badge Text</label>
                <input type="text" name="badgeText" value="<?= htmlspecialchars($content['badgeText'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label>Description Paragraph</label>
                <textarea name="description" required><?= htmlspecialchars($content['description'] ?? '') ?></textarea>
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label>Background Image Path</label>
                    <input type="text" name="bgImage" value="<?= htmlspecialchars($content['bgImage'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label>Vector Image Path</label>
                    <input type="text" name="vectorImage" value="<?= htmlspecialchars($content['vectorImage'] ?? '') ?>" required>
                </div>
            </div>

            <!-- Primary CTA -->
            <div class="sub-box">
                <div class="sub-box-title">Primary Button (White)</div>
                <div class="grid-2">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label>Button Label</label>
                        <input type="text" name="ctaPrimary_text" value="<?= htmlspecialchars($content['ctaPrimary']['text'] ?? '') ?>" required>
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label>Button URL</label>
                        <input type="text" name="ctaPrimary_url" value="<?= htmlspecialchars($content['ctaPrimary']['url'] ?? '') ?>" required>
                    </div>
                </div>
            </div>

            <!-- Secondary CTA -->
            <div class="sub-box">
                <div class="sub-box-title">Secondary Button (Outline)</div>
                <div class="grid-2">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label>Button Label</label>
                        <input type="text" name="ctaSecondary_text" value="<?= htmlspecialchars($content['ctaSecondary']['text'] ?? '') ?>" required>
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label>Button URL</label>
                        <input type="text" name="ctaSecondary_url" value="<?= htmlspecialchars($content['ctaSecondary']['url'] ?? '') ?>" required>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-submit">Save Changes</button>
        </form>
    </div>
</body>
</html>