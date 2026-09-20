const DATA_LOAN_SERVICES = "./src/data/loan-services.json";

const renderLoanServices = (data) => {
  const container = document.querySelector("#loan-services-section");
  if (!container) return;

  const {
    bgImage,
    badgeText,
    image,
    imageAlt,
    description,
    features,
    ctaPrimary,
    ctaSecondary,
  } = data;

  container.style.backgroundImage = `url('${bgImage}')`;

  const featuresHtml = features
    .map(
      (feature) => `
        <li class="flex items-center gap-3"><span class="h-2 w-2 rounded-full bg-blue-600 dark:bg-yellow-400 flex-shrink-0"></span> ${feature}</li>
      `
    )
    .join("");

  container.innerHTML = `
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-center mb-12 lg:mb-16">
        <div class="bg-blue-900 dark:bg-blue-800 text-white font-bold text-lg sm:text-xl rounded-full px-8 py-3 shadow-lg">
          ${badgeText}
        </div>
      </div>
      <div class="flex flex-col lg:flex-row items-center gap-10 lg:gap-16">
        <div class="w-full lg:w-2/5 flex justify-center">
          <img src="${image}" alt="${imageAlt}" class="w-full max-w-xs object-contain drop-shadow-xl" />
        </div>
        <div class="w-full lg:w-3/5">
          <p class="text-blue-900 dark:text-blue-200 font-semibold text-lg sm:text-xl mb-6 leading-relaxed">
            ${description}
          </p>
          <ul class="space-y-3 mb-8 text-gray-700 dark:text-gray-300">
            ${featuresHtml}
          </ul>
          <div class="flex flex-col sm:flex-row gap-4">
            <a href="${ctaPrimary.url}" class="bg-white dark:bg-gray-800 border-2 border-blue-900 dark:border-blue-400 text-blue-900 dark:text-blue-300 font-semibold text-center py-3 px-6 rounded-full shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
              ${ctaPrimary.text}
            </a>
            <a href="${ctaSecondary.url}" class="bg-blue-900 dark:bg-blue-700 hover:bg-blue-950 dark:hover:bg-blue-600 text-white font-semibold text-center py-3 px-6 rounded-full shadow-lg transition-colors">
              ${ctaSecondary.text}
            </a>
          </div>
        </div>
      </div>
    </div>
  `;
};

export const loadLoanServicesSection = async () => {
  try {
    const response = await fetch(DATA_LOAN_SERVICES);
    if (!response.ok) throw new Error("Could not find the loan-services data file.");
    const rawData = await response.json();
    renderLoanServices(rawData[0]);
  } catch (err) {
    console.error("Oops:", err);
  }
};
