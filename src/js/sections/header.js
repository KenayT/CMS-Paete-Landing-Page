const DATA_HEADER = "./src/data/header.json";

const getCurrentPage = () => {
  const path = window.location.pathname.split("/").pop();
  return path === "" ? "index.html" : path;
};

const renderHeader = (headerData) => {
  const headerContainer = document.querySelector("#header-section");
  if (!headerContainer) return;

  const { logoSrc, logoAlt, bankName, navLinks } = headerData;
  const currentPage = getCurrentPage();

  const navLinksHtml = navLinks
    .map((link) => {
      const isActive = link.url === currentPage;
      const activeClasses = isActive
        ? "text-blue-600 dark:text-yellow-400 border-b-2 border-blue-600 dark:border-yellow-400 pb-1"
        : "hover:text-blue-600 dark:hover:text-yellow-400 transition-colors duration-300";
      return `<a href="${link.url}" class="${activeClasses}">${link.name}</a>`;
    })
    .join("");

  const mobileNavLinksHtml = navLinks
    .map(
      (link) =>
        `<a href="${link.url}" class="block w-full bg-gray-50 dark:bg-gray-800 hover:bg-blue-600 hover:text-white dark:hover:bg-yellow-400 dark:hover:text-blue-900 py-3 px-4 rounded-lg transition-colors">${link.name}</a>`
    )
    .join("");

  headerContainer.innerHTML = `
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-20">
        
        <a href="index.html" class="flex items-center gap-3 group">
          <img src="${logoSrc}" alt="${logoAlt}" class="h-12 w-12 object-contain transition-transform duration-300 group-hover:scale-110 group-hover:rotate-3" />
          <span class="font-bold text-blue-900 dark:text-blue-400 text-lg leading-tight hidden sm:block transition-colors duration-300">
            ${bankName}
          </span>
        </a>

        <nav class="hidden lg:flex space-x-6 xl:space-x-8 font-medium text-sm xl:text-base text-gray-600 dark:text-gray-300">
          ${navLinksHtml}
        </nav>

        <div class="flex items-center space-x-3 sm:space-x-4">
          <button id="theme-toggle" type="button" class="text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-yellow-400 rounded-lg text-sm p-2.5 transition-colors duration-300">
            <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5 sm:w-6 sm:h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
            <svg id="theme-toggle-light-icon" class="hidden w-5 h-5 sm:w-6 sm:h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path></svg>
          </button>

          <button class="hidden md:block text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-yellow-400 transition-colors duration-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
          </button>

          <a href="#" class="hidden md:block bg-yellow-400 hover:bg-yellow-500 text-blue-900 font-semibold py-2 px-5 rounded-md shadow-sm transition-all duration-300 transform hover:-translate-y-1">
            Log In
          </a>

          <button id="mobile-menu-btn" class="lg:hidden text-blue-900 dark:text-gray-200 focus:outline-none transition-colors duration-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
          </button>
        </div>

      </div>
    </div>

    <div id="mobile-menu" class="hidden lg:hidden bg-white dark:bg-gray-900 absolute w-full left-0 top-20 shadow-2xl z-40">
      <nav class="flex flex-col px-6 py-6 space-y-4 font-medium text-lg text-gray-600 dark:text-gray-300">
        ${mobileNavLinksHtml}
        <div class="pt-2">
          <a href="#" class="block w-full bg-yellow-400 hover:bg-yellow-500 text-blue-900 font-bold py-3 px-4 rounded-lg shadow-sm text-center transition-transform hover:scale-[1.02]">Log In</a>
        </div>
      </nav>
    </div>
  `;

  initHeaderEvents();
};

const initHeaderEvents = () => {
  const themeToggleBtn = document.getElementById("theme-toggle");
  const themeToggleDarkIcon = document.getElementById("theme-toggle-dark-icon");
  const themeToggleLightIcon = document.getElementById("theme-toggle-light-icon");

  if (
    localStorage.getItem("color-theme") === "dark" ||
    (!("color-theme" in localStorage) &&
      window.matchMedia("(prefers-color-scheme: dark)").matches)
  ) {
    document.documentElement.classList.add("dark");
    themeToggleLightIcon?.classList.remove("hidden");
  } else {
    document.documentElement.classList.remove("dark");
    themeToggleDarkIcon?.classList.remove("hidden");
  }

  themeToggleBtn?.addEventListener("click", function () {
    themeToggleDarkIcon?.classList.toggle("hidden");
    themeToggleLightIcon?.classList.toggle("hidden");

    if (document.documentElement.classList.contains("dark")) {
      document.documentElement.classList.remove("dark");
      localStorage.setItem("color-theme", "light");
    } else {
      document.documentElement.classList.add("dark");
      localStorage.setItem("color-theme", "dark");
    }
  });

  const mobileMenuBtn = document.getElementById("mobile-menu-btn");
  const mobileMenu = document.getElementById("mobile-menu");

  if (mobileMenuBtn && mobileMenu) {
    mobileMenuBtn.addEventListener("click", function () {
      mobileMenu.classList.toggle("hidden");
    });
  }
};

export const loadHeaderSection = async () => {
  try {
    const response = await fetch(DATA_HEADER);
    if (!response.ok) throw new Error("Could not find the header data file.");
    const rawData = await response.json();
    renderHeader(rawData[0]);
  } catch (err) {
    console.error("Oops:", err);
  }
};
