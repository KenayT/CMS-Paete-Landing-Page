<?php
require_once dirname(__DIR__, 3) . '/includes/auth.php';
session_start();
requireLogin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Manage Loan Services - Paete CMS</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#0b132b] text-white min-h-screen p-8">
  <div class="max-w-4xl mx-auto">
    <div class="flex items-center justify-between mb-8 pb-4 border-b border-gray-700">
      <div>
        <a href="../index.php" class="text-sm text-blue-400 hover:underline">&larr; Back to Dashboard</a>
        <h1 class="text-2xl font-bold mt-1">Edit Loan Services</h1>
      </div>
      <button id="saveBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg font-medium shadow transition">
        Save All Changes
      </button>
    </div>

    <form id="loanForm" class="space-y-6 bg-[#1c2541] p-6 rounded-xl border border-gray-700">
      <div>
        <label class="block text-sm text-gray-300 mb-1">Badge Text</label>
        <input type="text" id="badgeText" class="w-full bg-[#0b132b] border border-gray-600 rounded-lg p-2.5 text-white focus:outline-none focus:border-blue-500" />
      </div>

      <div>
        <label class="block text-sm text-gray-300 mb-1">Description</label>
        <textarea id="description" rows="3" class="w-full bg-[#0b132b] border border-gray-600 rounded-lg p-2.5 text-white focus:outline-none focus:border-blue-500"></textarea>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm text-gray-300 mb-1">Image Path</label>
          <input type="text" id="image" class="w-full bg-[#0b132b] border border-gray-600 rounded-lg p-2.5 text-white focus:outline-none focus:border-blue-500" />
        </div>
        <div>
          <label class="block text-sm text-gray-300 mb-1">Background Image Path</label>
          <input type="text" id="bgImage" class="w-full bg-[#0b132b] border border-gray-600 rounded-lg p-2.5 text-white focus:outline-none focus:border-blue-500" />
        </div>
      </div>

      <!-- Features List -->
      <div>
        <div class="flex items-center justify-between mb-2">
          <label class="block text-sm text-gray-300 font-semibold">Features Bullet Points</label>
          <button type="button" id="addFeatureBtn" class="text-xs bg-emerald-600 hover:bg-emerald-700 px-3 py-1 rounded">
            + Add Feature
          </button>
        </div>
        <div id="featuresContainer" class="space-y-2"></div>
      </div>

      <!-- CTAs -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 border-t border-gray-700">
        <div>
          <label class="block text-sm text-gray-300 mb-1">Primary Button Text</label>
          <input type="text" id="ctaPrimaryText" class="w-full bg-[#0b132b] border border-gray-600 rounded-lg p-2.5 text-white focus:outline-none focus:border-blue-500" />
        </div>
        <div>
          <label class="block text-sm text-gray-300 mb-1">Primary Button URL</label>
          <input type="text" id="ctaPrimaryUrl" class="w-full bg-[#0b132b] border border-gray-600 rounded-lg p-2.5 text-white focus:outline-none focus:border-blue-500" />
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm text-gray-300 mb-1">Secondary Button Text</label>
          <input type="text" id="ctaSecondaryText" class="w-full bg-[#0b132b] border border-gray-600 rounded-lg p-2.5 text-white focus:outline-none focus:border-blue-500" />
        </div>
        <div>
          <label class="block text-sm text-gray-300 mb-1">Secondary Button URL</label>
          <input type="text" id="ctaSecondaryUrl" class="w-full bg-[#0b132b] border border-gray-600 rounded-lg p-2.5 text-white focus:outline-none focus:border-blue-500" />
        </div>
      </div>
    </form>
  </div>

  
  <script>
  const GET_API_URL = '../../../api/public/get-section.php?section=loan-services';
  const ADMIN_API_URL = '../../../api/admin/loan-services.php';
  let currentData = null;

  async function loadData() {
    try {
      const res = await fetch(GET_API_URL);
      const data = await res.json();
      currentData = Array.isArray(data) ? data[0] : data;

      document.getElementById('badgeText').value = currentData.badgeText || '';
      document.getElementById('description').value = currentData.description || '';
      document.getElementById('image').value = currentData.image || '';
      document.getElementById('bgImage').value = currentData.bgImage || '';
      document.getElementById('ctaPrimaryText').value = currentData.ctaPrimary?.text || '';
      document.getElementById('ctaPrimaryUrl').value = currentData.ctaPrimary?.url || '';
      document.getElementById('ctaSecondaryText').value = currentData.ctaSecondary?.text || '';
      document.getElementById('ctaSecondaryUrl').value = currentData.ctaSecondary?.url || '';

      renderFeatures();
    } catch (err) {
      console.error('Error loading loan services:', err);
    }
  }

  function renderFeatures() {
    const container = document.getElementById('featuresContainer');
    container.innerHTML = '';
    if (!currentData.features) currentData.features = [];
    
    currentData.features.forEach((feature, idx) => {
      const row = document.createElement('div');
      row.className = 'flex items-center gap-2';
      row.innerHTML = `
        <input type="text" value="${feature}" data-index="${idx}" class="feature-input flex-1 bg-[#0b132b] border border-gray-600 rounded-lg p-2 text-white focus:outline-none focus:border-blue-500" />
        <button type="button" onclick="removeFeature(${idx})" class="text-red-400 hover:text-red-300 font-bold px-3 py-1">&times;</button>
      `;
      container.appendChild(row);
    });
  }

  function removeFeature(idx) {
    currentData.features.splice(idx, 1);
    renderFeatures();
  }

  document.getElementById('addFeatureBtn').addEventListener('click', () => {
    if (!currentData.features) currentData.features = [];
    currentData.features.push('New Feature');
    renderFeatures();
  });

  document.getElementById('saveBtn').addEventListener('click', async () => {
    const saveBtn = document.getElementById('saveBtn');
    saveBtn.disabled = true;
    saveBtn.innerText = 'Saving...';

    const updatedPayload = {
      id: 1,
      bgImage: document.getElementById('bgImage').value,
      badgeText: document.getElementById('badgeText').value,
      image: document.getElementById('image').value,
      imageAlt: currentData?.imageAlt || 'Loan application illustration',
      description: document.getElementById('description').value,
      features: Array.from(document.querySelectorAll('.feature-input')).map(input => input.value.trim()).filter(Boolean),
      ctaPrimary: {
        text: document.getElementById('ctaPrimaryText').value,
        url: document.getElementById('ctaPrimaryUrl').value
      },
      ctaSecondary: {
        text: document.getElementById('ctaSecondaryText').value,
        url: document.getElementById('ctaSecondaryUrl').value
      }
    };

    try {
      const res = await fetch(ADMIN_API_URL, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(updatedPayload)
      });

      const result = await res.json();

      if (!res.ok) {
        throw new Error(result.error || 'Failed to save');
      }

      alert('Loan Services updated successfully!');
      currentData = updatedPayload;
      renderFeatures();
    } catch (err) {
      console.error(err);
      alert('Save failed: ' + err.message);
    } finally {
      saveBtn.disabled = false;
      saveBtn.innerText = 'Save All Changes';
    }
  });

  loadData();
</script>
</body>
</html>