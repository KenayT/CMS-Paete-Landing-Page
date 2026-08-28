const DATA_MOBILE_BANKING = "./src/data/mobile-banking.json";

const renderMobileBanking = (data) => {
  const container = document.querySelector("#mobile-banking-section");
  if (!container) return;

  const { bgImage, badgeText, image, heading, description, cta } = data;

  container.style.backgroundImage = `url('${bgImage}')`;

  container.innerHTML = `
    <div class="absolute inset-0 bg-blue-950/80 dark:bg-black/85 transition-colors duration-500"></div>

    <div class="relative z-10 container mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-center mb-10">
        <div class="bg-white text-blue-900 font-bold text-sm sm:text-base rounded-full px-8 py-2.5 shadow-lg">
          ${badgeText}
        </div>
      </div>

      <div class="flex flex-col lg:flex-row items-center gap-10 lg:gap-16">
        <div class="w-full lg:w-2/5 flex justify-center">
          <img src="${image}" alt="Rural Bank of Paete mobile app preview" class="w-full max-w-[220px] sm:max-w-xs object-contain drop-shadow-2xl" />
        </div>

        <div class="w-full lg:w-3/5 text-center lg:text-left text-white">
          <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold leading-tight mb-6">
            ${heading}
          </h2>
          <p class="text-blue-100 dark:text-gray-300 text-base sm:text-lg mb-8 max-w-xl mx-auto lg:mx-0">
            ${description}
          </p>
          <a href="${cta.url}" class="inline-block bg-orange-500 hover:bg-orange-600 text-white font-bold px-8 py-3 rounded-full shadow-lg transition-colors">
            ${cta.text}
          </a>
        </div>
      </div>
    </div>
  `;
};

export const loadMobileBankingSection = async () => {
  try {
    const response = await fetch(DATA_MOBILE_BANKING);
    if (!response.ok) throw new Error("Could not find the mobile-banking data file.");
    const rawData = await response.json();
    renderMobileBanking(rawData[0]);
  } catch (err) {
    console.error("Oops:", err);
  }
};
