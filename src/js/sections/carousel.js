const DATA_CAROUSEL = "./src/data/carousel.json";

const cardHtml = (card) => {
  const { icon, title, description, linkText, url } = card;
  return `
    <div class="card-item min-w-[280px] sm:min-w-[300px] flex-shrink-0 bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-xl border border-gray-100 dark:border-gray-700 flex flex-col items-center text-center select-none transition-colors duration-500">
      <img src="${icon}" alt="${title}" class="h-20 w-20 mb-4 object-contain pointer-events-none" />
      <h3 class="text-xl font-bold text-blue-900 dark:text-blue-200 mb-2">${title}</h3>
      <p class="text-gray-600 dark:text-gray-400 text-sm mb-6 flex-grow">${description}</p>
      <a href="${url}" class="bg-blue-800 dark:bg-blue-700 hover:bg-blue-900 dark:hover:bg-blue-600 text-white px-8 py-2.5 rounded-full text-sm font-semibold transition-colors w-full">${linkText}</a>
    </div>
  `;
};

const renderCarousel = (cards) => {
  const sectionContainer = document.querySelector("#carousel-section");
  if (!sectionContainer) return;

  const cardsHtml = cards.map((card) => cardHtml(card)).join("");

  sectionContainer.innerHTML = `
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative flex items-center">

      <button id="slide-left" class="absolute left-2 sm:left-4 z-30 bg-cyan-400 hover:bg-cyan-500 text-white p-2 rounded-md shadow-md transition-transform hover:scale-105 focus:outline-none">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7" /></svg>
      </button>

      <div id="card-slider" class="flex gap-6 overflow-x-auto hide-scrollbar cursor-grab active:cursor-grabbing pb-8 pt-4 px-12 w-full">
        ${cardsHtml}
      </div>

      <button id="slide-right" class="absolute right-2 sm:right-4 z-30 bg-cyan-400 hover:bg-cyan-500 text-white p-2 rounded-md shadow-md transition-transform hover:scale-105 focus:outline-none">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7" /></svg>
      </button>

    </div>
  `;

  initCarouselEvents();
};

const initCarouselEvents = () => {
  const slider = document.getElementById("card-slider");
  const leftArrow = document.getElementById("slide-left");
  const rightArrow = document.getElementById("slide-right");
  if (!slider || !leftArrow || !rightArrow) return;

  // Clone the original cards to create the infinite loop effect
  const originalCards = Array.from(slider.children);
  originalCards.forEach((card) => {
    const clone = card.cloneNode(true);
    slider.appendChild(clone);
  });

  let isDown = false;
  let startX;
  let scrollLeft;
  let isAutoScrolling = true;
  let autoScrollTimeout;

  function autoScroll() {
    if (isAutoScrolling && !isDown) {
      slider.scrollLeft += 1;

      if (slider.scrollLeft >= slider.scrollWidth / 2) {
        slider.scrollLeft = 0;
      } else if (slider.scrollLeft <= 0) {
        slider.scrollLeft = slider.scrollWidth / 2;
      }
    }
    requestAnimationFrame(autoScroll);
  }
  requestAnimationFrame(autoScroll);

  const pauseScroll = () => {
    isAutoScrolling = false;
    clearTimeout(autoScrollTimeout);
  };
  const resumeScroll = () => {
    autoScrollTimeout = setTimeout(() => {
      isAutoScrolling = true;
    }, 1500);
  };

  slider.addEventListener("mouseenter", pauseScroll);
  slider.addEventListener("mouseleave", resumeScroll);
  slider.addEventListener("touchstart", pauseScroll);
  slider.addEventListener("touchend", resumeScroll);

  leftArrow.addEventListener("click", () => {
    pauseScroll();
    const cardWidth = slider.firstElementChild.offsetWidth + 24;

    if (slider.scrollLeft === 0) {
      slider.scrollLeft = slider.scrollWidth / 2;
    }
    slider.scrollBy({ left: -cardWidth, behavior: "smooth" });
    resumeScroll();
  });

  rightArrow.addEventListener("click", () => {
    pauseScroll();
    const cardWidth = slider.firstElementChild.offsetWidth + 24;

    if (slider.scrollLeft >= slider.scrollWidth / 2) {
      slider.scrollLeft = 0;
    }
    slider.scrollBy({ left: cardWidth, behavior: "smooth" });
    resumeScroll();
  });

  slider.addEventListener("mousedown", (e) => {
    isDown = true;
    pauseScroll();
    slider.classList.add("active:cursor-grabbing");
    startX = e.pageX - slider.offsetLeft;
    scrollLeft = slider.scrollLeft;
  });

  slider.addEventListener("mouseup", () => {
    isDown = false;
    slider.classList.remove("active:cursor-grabbing");
    resumeScroll();
  });

  slider.addEventListener("mouseleave", () => {
    if (isDown) {
      isDown = false;
      slider.classList.remove("active:cursor-grabbing");
      resumeScroll();
    }
  });

  slider.addEventListener("mousemove", (e) => {
    if (!isDown) return;
    e.preventDefault();
    const x = e.pageX - slider.offsetLeft;
    const walk = (x - startX) * 2;
    slider.scrollLeft = scrollLeft - walk;
  });
};

export const loadCarouselSection = async () => {
  try {
    const response = await fetch(DATA_CAROUSEL);
    if (!response.ok) throw new Error("Could not find the carousel data file.");
    const rawData = await response.json();
    renderCarousel(rawData);
  } catch (err) {
    console.error("Oops:", err);
  }
};
