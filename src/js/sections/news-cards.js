const DATA_NEWS_CARDS = "./src/data/news-cards.json";

const newsCardHtml = (item) => {
  const { date, image, title, url } = item;
  return `
    <div class="relative">
      <div class="border-2 border-blue-900 dark:border-blue-500 rounded-2xl overflow-hidden bg-white dark:bg-gray-800 shadow-lg pb-6 transition-colors duration-500">
        <div class="bg-red-500 text-white font-bold text-center py-2.5">${date}</div>
        <div class="flex items-center justify-center h-28 px-6">
          <img src="${image}" alt="${title.replace(/<br\s*\/?>/g, " ")}" class="h-20 w-auto object-contain" />
        </div>
        <div class="bg-blue-900 dark:bg-blue-950 text-white font-bold text-center leading-snug py-3 px-3">
          ${title}
        </div>
      </div>
      <div class="absolute left-1/2 -translate-x-1/2 -bottom-4">
        <a href="${url}" class="bg-green-600 hover:bg-green-700 text-white text-sm font-semibold px-6 py-2 rounded-full shadow-md transition-colors">View Details</a>
      </div>
    </div>
  `;
};

const renderNewsCards = (data) => {
  const container = document.querySelector("#news-cards-section");
  if (!container) return;

  const { bgImage, badgeText, viewAllUrl, items } = data;

  container.style.backgroundImage = `url('${bgImage}')`;

  const cardsHtml = items.map((item) => newsCardHtml(item)).join("");

  container.innerHTML = `
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">

      <div class="flex justify-center mb-12">
        <div class="bg-blue-900 dark:bg-blue-800 text-white font-bold text-lg sm:text-xl rounded-full px-10 py-3 shadow-lg">
          ${badgeText}
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-10 lg:gap-x-8 mb-12">
        ${cardsHtml}
      </div>

      <div class="flex justify-center">
        <a href="${viewAllUrl}" class="bg-green-600 hover:bg-green-700 text-white font-semibold px-10 py-2.5 rounded-full shadow-lg transition-colors">View all</a>
      </div>

    </div>
  `;
};

export const loadNewsCardsSection = async () => {
  try {
    const response = await fetch(DATA_NEWS_CARDS);
    if (!response.ok) throw new Error("Could not find the news-cards data file.");
    const rawData = await response.json();
    renderNewsCards(rawData[0]);
  } catch (err) {
    console.error("Oops:", err);
  }
};
