const DATA_MISSION_VISION = "./src/data/mission-vision.json";

const renderMissionVision = (data) => {
  const container = document.querySelector("#mission-vision-section");
  if (!container) return;

  const { heading, description, items } = data;

  const itemsHtml = items
    .map(
      (item) => `
        <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl shadow-lg p-6 sm:p-8 flex flex-col items-center text-center transition-colors duration-500 hover:-translate-y-2 transition-transform">
          <img src="${item.icon}" alt="${item.label} icon" class="h-16 w-16 mb-4 object-contain" />
          <div class="bg-blue-900 dark:bg-blue-700 text-white font-bold text-sm tracking-wide rounded-full px-6 py-1.5 mb-4">
            ${item.label}
          </div>
          <p class="text-gray-600 dark:text-gray-300 text-sm leading-relaxed">
            ${item.text}
          </p>
        </div>
      `
    )
    .join("");

  container.innerHTML = `
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-16">

        <div class="w-full lg:w-5/12 bg-sky-500 dark:bg-blue-900 text-white rounded-2xl p-8 sm:p-10 shadow-xl transition-colors duration-500">
          <h2 class="text-2xl sm:text-3xl font-bold mb-4">${heading}</h2>
          <p class="text-sky-50 dark:text-blue-100 leading-relaxed">
            ${description}
          </p>
        </div>

        <div class="w-full lg:w-7/12 grid grid-cols-1 sm:grid-cols-2 gap-6">
          ${itemsHtml}
        </div>
      </div>
    </div>
  `;
};

export const loadMissionVisionSection = async () => {
  try {
    const response = await fetch(DATA_MISSION_VISION);
    if (!response.ok) throw new Error("Could not find the mission-vision data file.");
    const rawData = await response.json();
    renderMissionVision(rawData[0]);
  } catch (err) {
    console.error("Oops:", err);
  }
};
