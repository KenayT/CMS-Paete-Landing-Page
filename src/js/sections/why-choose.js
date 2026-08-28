const DATA_WHY_CHOOSE = "./src/data/why-choose.json";

const renderWhyChoose = (data) => {
  const container = document.querySelector("#why-choose-section");
  if (!container) return;

  const { badgeText, items } = data;

  const itemsHtml = items
    .map(
      (item) => `
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 sm:p-8 flex flex-col items-center text-center hover:-translate-y-2 transition-transform duration-300">
          <img src="${item.icon}" alt="${item.text}" class="h-24 w-auto object-contain mb-5" />
          <p class="font-semibold text-gray-800 dark:text-gray-200">${item.text}</p>
        </div>
      `
    )
    .join("");

  container.innerHTML = `
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-center mb-12">
        <div class="bg-white dark:bg-gray-900 text-blue-900 dark:text-yellow-400 font-bold text-lg sm:text-xl rounded-full px-10 py-3 shadow-lg">
          ${badgeText}
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        ${itemsHtml}
      </div>
    </div>
  `;
};

export const loadWhyChooseSection = async () => {
  try {
    const response = await fetch(DATA_WHY_CHOOSE);
    if (!response.ok) throw new Error("Could not find the why-choose data file.");
    const rawData = await response.json();
    renderWhyChoose(rawData[0]);
  } catch (err) {
    console.error("Oops:", err);
  }
};
