const DATA_FOOTER = "./api/public/get-section.php?section=footer";

const renderFooter = (footerData) => {
  const footerContainer = document.querySelector("#footer-section");
  if (!footerContainer) return;

  const bankName = footerData.brandName || footerData.bankName || "Rural Bank of Paete, Inc.";
  const copyrightText = footerData.copyright || footerData.copyrightText || "© 2026 Rural Bank of Paete, Inc. All rights reserved.";
  const logoSrc = footerData.logoSrc || "./assets/logo.png";
  const links = footerData.links || footerData.navLinks || [];

  const navLinksHtml = links
    .map((link) => {
      const label = link.label || link.name || "";
      const url = link.url || "#";
      return `<a href="${url}" class="hover:text-white transition-colors duration-200">${label}</a>`;
    })
    .join("");

  footerContainer.innerHTML = `
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex flex-col md:flex-row justify-between items-center gap-6 border-b border-blue-800 dark:border-gray-800 pb-8 mb-6">

        <div class="flex items-center gap-3">
          <img src="${logoSrc}" alt="Footer Logo" class="h-10 w-10 object-contain rounded-full bg-white p-1" />
          <span class="font-bold text-lg text-white">${bankName}</span>
        </div>

        <nav class="flex flex-wrap justify-center gap-6 text-sm font-medium text-blue-200 dark:text-gray-400">
          ${navLinksHtml}
        </nav>
      </div>

      <div class="text-center text-sm text-blue-300 dark:text-gray-500">
        ${copyrightText}
      </div>
    </div>
  `;
};

export async function loadFooterSection() {
  try {
    const res = await fetch(DATA_FOOTER);
    if (!res.ok) throw new Error("Network response was not ok");
    const rawData = await res.json();
    const data = typeof rawData === "string" ? JSON.parse(rawData) : rawData;

    renderFooter(data);
  } catch (err) {
    console.error("Failed to load footer dynamically:", err);
  }
}