const DATA_INFO_BOXES = "./src/data/info-boxes.json";

const boxHtml = (box) => {
  const { type, title, items, url } = box;

  if (type === "cta") {
    return `
      <div class="bg-blue-900 dark:bg-blue-950 rounded-xl p-6 sm:p-8 text-white shadow-lg hover:-translate-y-2 transition-transform duration-300 flex items-center justify-center text-center cursor-pointer group">
        <a href="${url}" class="text-2xl font-bold group-hover:text-yellow-400 transition-colors duration-300">
          ${title}
        </a>
      </div>
    `;
  }

  const itemsHtml = items.map((item) => `<li>&bull; ${item}</li>`).join("");

  return `
    <div class="bg-sky-500 dark:bg-blue-800 rounded-xl p-6 sm:p-8 text-white shadow-lg hover:-translate-y-2 transition-transform duration-300">
      <h3 class="text-xl font-bold mb-4">${title}</h3>
      <ul class="space-y-2 text-sm sm:text-base text-sky-50 dark:text-blue-100">${itemsHtml}</ul>
    </div>
  `;
};

const renderInfoBoxes = (boxes) => {
  const container = document.querySelector("#info-boxes-section");
  if (!container) return;

  const boxesHtml = boxes.map((box) => boxHtml(box)).join("");

  container.innerHTML = `
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        ${boxesHtml}
      </div>
    </div>
  `;
};

export const loadInfoBoxesSection = async () => {
  try {
    const response = await fetch(DATA_INFO_BOXES);
    if (!response.ok) throw new Error("Could not find the info-boxes data file.");
    const rawData = await response.json();
    renderInfoBoxes(rawData);
  } catch (err) {
    console.error("Oops:", err);
  }
};
