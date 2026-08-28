const DATA_ABOUT_INTRO = "./src/data/about-intro.json";

const renderAboutIntro = (data) => {
  const container = document.querySelector("#about-intro-section");
  if (!container) return;

  const { bgImage, heading, image, imageAlt, cards } = data;

  container.style.backgroundImage = `url('${bgImage}')`;

  const cardsHtml = cards
    .map(
      (card) => `
        <div class="bg-sky-500 dark:bg-blue-800 text-white rounded-2xl p-6 sm:p-8 shadow-lg transition-colors duration-500">
          <h3 class="text-xl font-bold mb-3">${card.title}</h3>
          <p class="text-sky-50 dark:text-blue-100 leading-relaxed">
            ${card.text}
          </p>
        </div>
      `
    )
    .join("");

  container.innerHTML = `
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">

      <h2 class="text-3xl sm:text-4xl font-bold text-blue-900 dark:text-blue-300 text-center mb-12 relative inline-block left-1/2 -translate-x-1/2 after:content-[''] after:block after:w-20 after:h-1 after:bg-yellow-400 after:mx-auto after:mt-3">
        ${heading}
      </h2>

      <div class="flex flex-col lg:flex-row items-center gap-10 lg:gap-16">
        <div class="w-full lg:w-1/2 flex justify-center">
          <img src="${image}" alt="${imageAlt}" class="w-full max-w-md object-contain drop-shadow-xl" />
        </div>
        <div class="w-full lg:w-1/2 flex flex-col gap-6">
          ${cardsHtml}
        </div>
      </div>
    </div>
  `;
};

export const loadAboutIntroSection = async () => {
  try {
    const response = await fetch(DATA_ABOUT_INTRO);
    if (!response.ok) throw new Error("Could not find the about-intro data file.");
    const rawData = await response.json();
    renderAboutIntro(rawData[0]);
  } catch (err) {
    console.error("Oops:", err);
  }
};
