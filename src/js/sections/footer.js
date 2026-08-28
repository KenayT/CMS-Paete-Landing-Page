const DATA_FOOTER = "./src/data/footer.json";

const renderFooter = (footerData) => {
  const footerContainer = document.querySelector("#footer-section");
  if (!footerContainer) return;

  const { logoSrc, bankName, navLinks, copyrightText } = footerData;

  const navLinksHtml = navLinks
    .map(
      (link) =>
        `<a href="${link.url}" class="hover:text-white transition-colors duration-200">${link.name}</a>`
    )
    .join("");

  footerContainer.innerHTML = `
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex flex-col md:flex-row justify-between items-center gap-6 border-b border-blue-800 dark:border-gray-800 pb-8 mb-6">

        <div class="flex items-center gap-3">
          <img src="${logoSrc}" alt="Footer Logo" class="h-10 w-10 object-contain rounded-full bg-white p-1" />
          <span class="font-bold text-lg">${bankName}</span>
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

export const loadFooterSection = async () => {
  try {
    const response = await fetch(DATA_FOOTER);
    if (!response.ok) throw new Error("Could not find the footer data file.");
    const rawData = await response.json();
    renderFooter(rawData[0]);
  } catch (err) {
    console.error("Oops:", err);
  }
};
