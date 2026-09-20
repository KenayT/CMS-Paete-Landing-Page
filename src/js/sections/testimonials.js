const DATA_TESTIMONIALS = "./src/data/testimonials.json";

const starSvg = () => `
  <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
`;

const testimonialHtml = (item) => {
  const { avatar, name, role, rating, text } = item;
  const starsHtml = Array.from({ length: rating }, () => starSvg()).join("");

  return `
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 sm:p-8 text-center border-2 border-sky-200 dark:border-gray-700 transition-colors duration-500">
      <img src="${avatar}" alt="${name}" class="h-16 w-16 rounded-full mx-auto mb-4 object-cover shadow-sm" />
      <h4 class="font-bold text-blue-900 dark:text-blue-200">${name}</h4>
      <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">${role}</p>
      <div class="flex justify-center gap-0.5 mb-3 text-yellow-400">${starsHtml}</div>
      <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
        ${text}
      </p>
    </div>
  `;
};

const renderTestimonials = (data) => {
  const container = document.querySelector("#testimonials-section");
  if (!container) return;

  const { badgeText, items } = data;
  const itemsHtml = items.map((item) => testimonialHtml(item)).join("");

  container.innerHTML = `
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-center mb-12">
        <div class="bg-blue-900 dark:bg-blue-800 text-white font-bold text-lg sm:text-xl rounded-full px-10 py-3 shadow-lg">
          ${badgeText}
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
        ${itemsHtml}
      </div>
    </div>
  `;
};

export const loadTestimonialsSection = async () => {
  try {
    const response = await fetch(DATA_TESTIMONIALS);
    if (!response.ok) throw new Error("Could not find the testimonials data file.");
    const rawData = await response.json();
    renderTestimonials(rawData[0]);
  } catch (err) {
    console.error("Oops:", err);
  }
};
