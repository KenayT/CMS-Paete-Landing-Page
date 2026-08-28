const DATA_OFFERINGS = "./src/data/offerings.json";

const renderOfferings = (data) => {
  const container = document.querySelector("#offerings-section");
  if (!container) return;

  const { heading, description, badgeText, image, loans, deposits } = data;

  const loanColHtml = (items) =>
    items
      .map(
        (item) =>
          `<li class="flex items-start gap-2"><span class="text-blue-500 mt-0.5">&bull;</span> ${item}</li>`
      )
      .join("");

  const half = Math.ceil(loans.length / 2);
  const loansColOne = loanColHtml(loans.slice(0, half));
  const loansColTwo = loanColHtml(loans.slice(half));

  const depositsHtml = deposits
    .map(
      (item) =>
        `<li class="flex items-start gap-2"><span class="text-blue-500 mt-0.5">&bull;</span> ${item}</li>`
    )
    .join("");

  container.innerHTML = `
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-20">

        <div class="w-full lg:w-1/2 text-white z-10">
          <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold mb-4 tracking-tight">${heading}</h2>
          <p class="mb-8 text-sky-50 dark:text-gray-300 text-lg">
            ${description}
          </p>

          <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 sm:p-8 shadow-2xl text-gray-800 dark:text-gray-200 transform hover:scale-[1.02] transition-transform duration-300">
            <div class="inline-block border-2 border-blue-900 dark:border-yellow-400 rounded-full px-5 py-1.5 mb-6">
              <h3 class="text-xl font-bold text-blue-900 dark:text-yellow-400">${badgeText}</h3>
            </div>

            <h4 class="font-bold text-lg mb-3 text-blue-800 dark:text-blue-300">Loans</h4>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-2 mb-6 text-sm sm:text-base">
              <ul class="space-y-2">${loansColOne}</ul>
              <ul class="space-y-2">${loansColTwo}</ul>
            </div>

            <h4 class="font-bold text-lg mb-3 text-blue-800 dark:text-blue-300">Deposit</h4>
            <ul class="space-y-2 text-sm sm:text-base">${depositsHtml}</ul>
          </div>
        </div>

        <div class="w-full lg:w-1/2 flex justify-center z-10">
          <img src="${image}" alt="Financial Services Illustration" class="w-full max-w-lg lg:max-w-xl object-contain drop-shadow-2xl" />
        </div>

      </div>
    </div>
  `;
};

export const loadOfferingsSection = async () => {
  try {
    const response = await fetch(DATA_OFFERINGS);
    if (!response.ok) throw new Error("Could not find the offerings data file.");
    const rawData = await response.json();
    renderOfferings(rawData[0]);
  } catch (err) {
    console.error("Oops:", err);
  }
};
