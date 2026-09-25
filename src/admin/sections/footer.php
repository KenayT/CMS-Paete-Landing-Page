<?php
require_once dirname(__DIR__, 3) . '/config/database.php';
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
  <title>Edit Footer - Paete CMS</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#0b1329] text-slate-100 min-h-screen p-8">
  <div class="max-w-4xl mx-auto">
    <div class="flex items-center justify-between mb-8 pb-4 border-b border-slate-700">
      <div>
        <a href="../index.php" class="text-blue-400 hover:underline text-sm">&larr; Back to Dashboard</a>
        <h1 class="text-3xl font-bold mt-2">Edit Footer Section</h1>
      </div>
      <button id="saveBtn" type="button" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2 rounded-lg text-sm transition">
        Save All Changes
      </button>
    </div>

    <form id="footerForm" class="space-y-6">
      <div class="bg-[#131f42] border border-slate-700 p-6 rounded-xl space-y-4">
        <h3 class="font-bold text-lg text-blue-300">Brand & Copyright</h3>
        
        <div>
          <label class="block text-xs font-semibold uppercase text-slate-400 mb-1">Brand Name</label>
          <input type="text" id="brandName" class="w-full bg-[#0b1329] border border-slate-600 rounded p-2 text-white" />
        </div>

        <div>
          <label class="block text-xs font-semibold uppercase text-slate-400 mb-1">Copyright Line</label>
          <input type="text" id="copyright" class="w-full bg-[#0b1329] border border-slate-600 rounded p-2 text-white" />
        </div>
      </div>

      <div class="bg-[#131f42] border border-slate-700 p-6 rounded-xl space-y-4">
        <div class="flex justify-between items-center border-b border-slate-700 pb-3">
          <h3 class="font-bold text-lg text-blue-300">Navigation Links</h3>
          <button type="button" id="addLinkBtn" class="text-xs bg-emerald-600 hover:bg-emerald-700 text-white font-medium px-3 py-1.5 rounded transition">+ Add Link</button>
        </div>

        <div id="linksContainer" class="space-y-3"></div>
      </div>
    </form>
  </div>

  <script>
    const API_URL = '/CMS-Paete-Landing-Page-main/api/admin/footer.php';
    let footerData = {
      brandName: '',
      copyright: '',
      links: []
    };

    async function loadData() {
      try {
        const res = await fetch(API_URL);
        const data = await res.json();
        footerData = typeof data === 'string' ? JSON.parse(data) : data;
        
        document.getElementById('brandName').value = footerData.brandName || '';
        document.getElementById('copyright').value = footerData.copyright || '';
        renderLinks();
      } catch (err) {
        console.error("Failed to load footer data:", err);
      }
    }

    function renderLinks() {
      const container = document.getElementById('linksContainer');
      container.innerHTML = '';

      (footerData.links || []).forEach((link, idx) => {
        const row = document.createElement('div');
        row.className = "flex gap-3 items-center";
        row.innerHTML = `
          <div class="flex-1">
            <input type="text" placeholder="Label (e.g. Home)" value="${(link.label || '').replace(/"/g, '&quot;')}" oninput="updateLink(${idx}, 'label', this.value)" class="w-full bg-[#0b1329] border border-slate-600 rounded p-2 text-white text-sm" />
          </div>
          <div class="flex-1">
            <input type="text" placeholder="URL (e.g. index.html or #)" value="${(link.url || '').replace(/"/g, '&quot;')}" oninput="updateLink(${idx}, 'url', this.value)" class="w-full bg-[#0b1329] border border-slate-600 rounded p-2 text-white text-sm" />
          </div>
          <button type="button" onclick="removeLink(${idx})" class="text-red-400 hover:text-red-300 px-2 text-xl leading-none" title="Remove">&times;</button>
        `;
        container.appendChild(row);
      });
    }

    function updateLink(idx, field, value) {
      footerData.links[idx][field] = value;
    }

    function removeLink(idx) {
      footerData.links.splice(idx, 1);
      renderLinks();
    }

    document.getElementById('addLinkBtn').addEventListener('click', () => {
      if (!footerData.links) footerData.links = [];
      footerData.links.push({ label: 'New Link', url: '#' });
      renderLinks();
    });

    document.getElementById('saveBtn').addEventListener('click', async () => {
      footerData.brandName = document.getElementById('brandName').value.trim();
      footerData.copyright = document.getElementById('copyright').value.trim();

      try {
        const res = await fetch(API_URL, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(footerData)
        });
        const result = await res.json();
        if (res.ok && result.success) {
          alert('Footer updated successfully!');
        } else {
          alert('Error saving footer: ' + (result.error || 'Server error'));
        }
      } catch (err) {
        alert('Request failed: ' + err.message);
      }
    });

    loadData();
  </script>
</body>
</html>