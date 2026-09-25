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
  <title>Edit Info Boxes - Paete CMS</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#0b1329] text-slate-100 min-h-screen p-8">
  <div class="max-w-4xl mx-auto">
    <div class="flex items-center justify-between mb-8 pb-4 border-b border-slate-700">
      <div>
        <a href="../index.php" class="text-blue-400 hover:underline text-sm">&larr; Back to Dashboard</a>
        <h1 class="text-3xl font-bold mt-2">Edit Info Boxes</h1>
      </div>
      <div class="flex gap-3">
        <button id="addBoxBtn" type="button" class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-4 py-2 rounded-lg text-sm transition">
          + Add Box
        </button>
        <button id="saveBtn" type="button" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2 rounded-lg text-sm transition">
          Save All Changes
        </button>
      </div>
    </div>

    <div id="boxesContainer" class="space-y-6"></div>
  </div>

  <script>
    const API_URL = '/CMS-Paete-Landing-Page-main/api/admin/info-boxes.php';
    let boxes = [];

    async function loadData() {
      try {
        const res = await fetch(API_URL);
        const data = await res.json();
        boxes = typeof data === 'string' ? JSON.parse(data) : data;
        if (!Array.isArray(boxes)) boxes = [];
        render();
      } catch (err) {
        console.error("Failed to fetch data:", err);
      }
    }

    function render() {
      const container = document.getElementById('boxesContainer');
      container.innerHTML = '';

      if (boxes.length === 0) {
        container.innerHTML = '<p class="text-slate-400 text-center py-8">No info boxes found. Click "+ Add Box" to create one.</p>';
        return;
      }

      boxes.forEach((box, bIdx) => {
        const isCta = box.type === 'cta';
        const card = document.createElement('div');
        card.className = "bg-[#131f42] border border-slate-700 p-6 rounded-xl relative space-y-4 shadow-md";

        let innerContent = '';
        if (isCta) {
          innerContent = `
            <div>
              <label class="block text-xs font-semibold uppercase text-slate-400 mb-1">CTA URL</label>
              <input type="text" class="w-full bg-[#0b1329] border border-slate-600 rounded p-2 text-white" value="${box.url || '#'}" oninput="updateField(${bIdx}, 'url', this.value)" />
            </div>
          `;
        } else {
          const items = box.items || [];
          const itemsHtml = items.map((item, iIdx) => `
            <div class="flex gap-2 items-center">
              <input type="text" class="flex-1 bg-[#0b1329] border border-slate-600 rounded p-2 text-white text-sm" value="${item.replace(/"/g, '&quot;')}" oninput="updateItem(${bIdx}, ${iIdx}, this.value)" />
              <button type="button" onclick="removeItem(${bIdx}, ${iIdx})" class="text-red-400 hover:text-red-300 px-2 text-lg leading-none" title="Remove">&times;</button>
            </div>
          `).join('');

          innerContent = `
            <div>
              <div class="flex justify-between items-center mb-2">
                <label class="text-xs font-semibold uppercase text-slate-400">Bullet Items</label>
                <button type="button" onclick="addItem(${bIdx})" class="text-xs text-emerald-400 hover:underline font-medium">+ Add Bullet</button>
              </div>
              <div class="space-y-2">
                ${itemsHtml}
              </div>
            </div>
          `;
        }

        card.innerHTML = `
          <div class="flex justify-between items-center border-b border-slate-700 pb-3">
            <h3 class="font-bold text-lg text-blue-300">Box #${bIdx + 1} (${isCta ? 'Call to Action' : 'List Card'})</h3>
            <button type="button" onclick="removeBox(${bIdx})" class="text-red-400 hover:text-red-300 text-sm font-semibold">Delete Box</button>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-semibold uppercase text-slate-400 mb-1">Box Type</label>
              <select onchange="updateField(${bIdx}, 'type', this.value); render();" class="w-full bg-[#0b1329] border border-slate-600 rounded p-2 text-white">
                <option value="list" ${!isCta ? 'selected' : ''}>List (Bullet Items)</option>
                <option value="cta" ${isCta ? 'selected' : ''}>Call to Action (CTA)</option>
              </select>
            </div>
            <div>
              <label class="block text-xs font-semibold uppercase text-slate-400 mb-1">Title</label>
              <input type="text" class="w-full bg-[#0b1329] border border-slate-600 rounded p-2 text-white" value="${(box.title || '').replace(/"/g, '&quot;')}" oninput="updateField(${bIdx}, 'title', this.value)" />
            </div>
          </div>

          ${innerContent}
        `;

        container.appendChild(card);
      });
    }

    function updateField(boxIdx, field, value) {
      boxes[boxIdx][field] = value;
      if (field === 'type' && value === 'list' && !boxes[boxIdx].items) {
        boxes[boxIdx].items = ["Sample item"];
      }
    }

    function updateItem(boxIdx, itemIdx, value) {
      boxes[boxIdx].items[itemIdx] = value;
    }

    function addItem(boxIdx) {
      if (!boxes[boxIdx].items) boxes[boxIdx].items = [];
      boxes[boxIdx].items.push("New item");
      render();
    }

    function removeItem(boxIdx, itemIdx) {
      boxes[boxIdx].items.splice(itemIdx, 1);
      render();
    }

    function removeBox(boxIdx) {
      if (confirm('Delete this box?')) {
        boxes.splice(boxIdx, 1);
        render();
      }
    }

    document.getElementById('addBoxBtn').addEventListener('click', () => {
      const newId = boxes.length > 0 ? Math.max(...boxes.map(b => b.id || 0)) + 1 : 1;
      boxes.push({ id: newId, type: 'list', title: 'New Box', items: ['Sample item'] });
      render();
    });

    document.getElementById('saveBtn').addEventListener('click', async () => {
      try {
        const res = await fetch(API_URL, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(boxes)
        });
        const result = await res.json();
        if (res.ok && result.success) {
          alert('Info Boxes updated successfully!');
        } else {
          alert('Error saving data: ' + (result.error || 'Server error'));
        }
      } catch (err) {
        alert('Request failed: ' + err.message);
      }
    });

    loadData();
  </script>
</body>
</html>