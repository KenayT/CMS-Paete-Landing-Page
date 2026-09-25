<?php
require_once dirname(__DIR__, 3) . '/includes/auth.php';
requireLogin();

require_once dirname(__DIR__, 3) . '/config/database.php';
require_once dirname(__DIR__, 3) . '/includes/functions.php';

$pdo = getPDO();
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rawItems = $_POST['items'] ?? [];
    $updatedContent = [];

    foreach ($rawItems as $index => $item) {
        $updatedContent[] = [
            'id'          => $index + 1,
            'icon'        => trim($item['icon'] ?? ''),
            'title'       => sanitize($item['title'] ?? ''),
            'description' => sanitize($item['description'] ?? ''),
            'linkText'    => sanitize($item['linkText'] ?? 'Learn more...'),
            'url'         => trim($item['url'] ?? '#')
        ];
    }

    try {
        saveContent($pdo, 'carousel', $updatedContent);
        $message = 'Carousel slides updated successfully!';
    } catch (Throwable $e) {
        $error = 'Failed to save: ' . $e->getMessage();
    }
}

$slides = getSectionContent($pdo, 'carousel');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Edit Carousel Section - Admin</title>
  <link rel="stylesheet" href="/dist/output.css" />
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen p-6 font-sans">
  <div class="max-w-4xl mx-auto bg-slate-800 p-8 rounded-2xl border border-slate-700 shadow-xl">
    
    <div class="flex items-center justify-between mb-8 pb-4 border-b border-slate-700">
      <h1 class="text-2xl font-bold">Edit Carousel Section</h1>
      <a href="../index.php" class="text-sky-400 hover:text-sky-300 text-sm font-medium">← Back to Dashboard</a>
    </div>

    <?php if ($message): ?>
      <div class="mb-6 p-4 bg-emerald-900/50 border border-emerald-500 rounded-lg text-emerald-300 text-sm">
        <?= htmlspecialchars($message) ?>
      </div>
    <?php endif; ?>

    <?php if ($error): ?>
      <div class="mb-6 p-4 bg-rose-900/50 border border-rose-500 rounded-lg text-rose-300 text-sm">
        <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>

    <form method="POST" class="space-y-8">
      <?php foreach ($slides as $i => $slide): ?>
        <div class="p-6 bg-slate-900/60 rounded-xl border border-slate-700 space-y-4">
          <h3 class="text-sky-400 font-semibold text-sm tracking-wider uppercase">Slide #<?= $i + 1 ?></h3>

          <div>
            <label class="block text-xs font-semibold text-slate-400 mb-1">TITLE</label>
            <input 
              type="text" 
              name="items[<?= $i ?>][title]" 
              value="<?= htmlspecialchars($slide['title'] ?? '') ?>" 
              class="w-full bg-slate-950 border border-slate-700 rounded-lg p-3 text-sm focus:border-sky-500 focus:outline-none"
              required 
            />
          </div>

          <div>
            <label class="block text-xs font-semibold text-slate-400 mb-1">DESCRIPTION</label>
            <textarea 
              name="items[<?= $i ?>][description]" 
              rows="3" 
              class="w-full bg-slate-950 border border-slate-700 rounded-lg p-3 text-sm focus:border-sky-500 focus:outline-none"
              required><?= htmlspecialchars($slide['description'] ?? '') ?></textarea>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
              <label class="block text-xs font-semibold text-slate-400 mb-1">ICON PATH</label>
              <input 
                type="text" 
                name="items[<?= $i ?>][icon]" 
                value="<?= htmlspecialchars($slide['icon'] ?? '') ?>" 
                class="w-full bg-slate-950 border border-slate-700 rounded-lg p-3 text-sm focus:border-sky-500 focus:outline-none"
                required 
              />
            </div>

            <div>
              <label class="block text-xs font-semibold text-slate-400 mb-1">BUTTON / LINK TEXT</label>
              <input 
                type="text" 
                name="items[<?= $i ?>][linkText]" 
                value="<?= htmlspecialchars($slide['linkText'] ?? '') ?>" 
                class="w-full bg-slate-950 border border-slate-700 rounded-lg p-3 text-sm focus:border-sky-500 focus:outline-none"
                required 
              />
            </div>

            <div>
              <label class="block text-xs font-semibold text-slate-400 mb-1">BUTTON / LINK URL</label>
              <input 
                type="text" 
                name="items[<?= $i ?>][url]" 
                value="<?= htmlspecialchars($slide['url'] ?? '#') ?>" 
                class="w-full bg-slate-950 border border-slate-700 rounded-lg p-3 text-sm focus:border-sky-500 focus:outline-none"
                required 
              />
            </div>
          </div>
        </div>
      <?php endforeach; ?>

      <div class="pt-4">
        <button type="submit" class="w-full py-3 bg-blue-600 hover:bg-blue-500 rounded-lg font-semibold text-sm transition-colors">
          Save Changes
        </button>
      </div>
    </form>

  </div>
</body>
</html>