const DATA_HERO = "./src/data/hero.json";

const renderHero = (heroData) => {
  const heroContainer = document.querySelector("#hero-section");
  if (!heroContainer) return;

  const {
    bgImage,
    vectorImage,
    headline,
    headlineHighlight,
    description,
    badgeText,
    ctaPrimary,
    ctaSecondary,
  } = heroData;

  heroContainer.style.backgroundImage = `url('${bgImage}')`;

  heroContainer.innerHTML = `
    <div class="absolute inset-0 bg-gradient-to-r from-blue-600/85 via-blue-700/80 to-blue-800/70 dark:from-blue-950/95 dark:via-gray-900/90 dark:to-gray-900/80 transition-colors duration-500"></div>

    <div class="relative z-10 container mx-auto px-4 sm:px-6 lg:px-8 flex flex-col-reverse lg:flex-row items-center justify-between">

      <div class="w-full lg:w-1/2 text-center lg:text-left mt-8 lg:mt-0">
        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white leading-tight mb-6">
          ${headline} <br />
          <span class="text-yellow-400 dark:text-yellow-500">${headlineHighlight}</span>
        </h1>
        <p class="text-blue-50 dark:text-gray-300 text-lg sm:text-base xl:text-xl mb-8 max-w-2xl mx-auto lg:mx-0">
          ${description}
        </p>

        <div class="inline-block bg-yellow-400 dark:bg-yellow-500 text-blue-900 dark:text-gray-900 font-bold text-lg py-2 px-6 rounded-full mb-8 shadow-lg">
          ${badgeText}
        </div>

        <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
          <a href="${ctaPrimary.url}" class="bg-white dark:bg-blue-600 text-blue-900 dark:text-white font-bold py-3 px-8 rounded-md shadow-lg hover:bg-gray-100 transition-all">
            ${ctaPrimary.text}
          </a>
          <a href="${ctaSecondary.url}" class="border-2 border-white text-white font-bold py-3 px-8 rounded-md hover:bg-white hover:text-blue-900 dark:hover:bg-gray-100 dark:hover:text-gray-900 transition-all">
            ${ctaSecondary.text}
          </a>
        </div>
      </div>

      <div class="w-full lg:w-1/2 flex justify-center lg:justify-end items-end relative">
        <img src="${vectorImage}" alt="Banking Illustration" class="w-11/12 sm:w-3/4 lg:w-[90%] max-w-2xl drop-shadow-2xl translate-y-12 lg:translate-y-16 origin-bottom" />
      </div>

    </div>
  `;
};

export const loadHeroSection = async () => {
  try {
    const response = await fetch(DATA_HERO);
    if (!response.ok) throw new Error("Could not find the hero data file.");
    const rawData = await response.json();
    renderHero(rawData[0]);
  } catch (err) {
    console.error("Oops:", err);
  }
};
