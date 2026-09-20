const DATA_SECURITY = "./src/data/security.json";

const checkIconSvg = () => `
  <svg class="h-6 w-6 text-green-600 dark:text-green-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
`;

const renderSecurity = (data) => {
  const container = document.querySelector("#security-section");
  if (!container) return;

  const { icon, heading, intro, items, cta, image } = data;

  const itemsHtml = items
    .map(
      (item) => `
        <li class="flex items-center gap-3 text-gray-700 dark:text-gray-300 font-medium">
          ${checkIconSvg()}
          ${item}
        </li>
      `
    )
    .join("");

  container.innerHTML = `
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-center gap-3 mb-4">
        <img src="${icon}" alt="Security icon" class="h-8 w-8 object-contain" />
        <h2 class="text-2xl sm:text-3xl font-bold text-blue-900 dark:text-blue-300">${heading}</h2>
      </div>
      <div class="w-full h-0.5 bg-blue-900 dark:bg-blue-700 mb-12"></div>

      <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-16">
        <div class="w-full lg:w-1/2">
          <p class="font-semibold text-lg text-gray-800 dark:text-gray-200 mb-6">${intro}</p>
          <ul class="space-y-4 mb-8">
            ${itemsHtml}
          </ul>
          <a href="${cta.url}" class="inline-block bg-blue-900 dark:bg-blue-700 hover:bg-blue-950 dark:hover:bg-blue-600 text-white font-semibold px-8 py-2.5 rounded-full shadow-lg transition-colors">
            ${cta.text}
          </a>
        </div>

        <div class="w-full lg:w-1/2 flex justify-center">
          <img src="${image}" alt="Security and data protection illustration" class="w-full max-w-lg object-contain drop-shadow-xl" />
        </div>
      </div>
    </div>
  `;
};

export const loadSecuritySection = async () => {
  try {
    const response = await fetch(DATA_SECURITY);
    if (!response.ok) throw new Error("Could not find the security data file.");
    const rawData = await response.json();
    renderSecurity(rawData[0]);
  } catch (err) {
    console.error("Oops:", err);
  }
};
