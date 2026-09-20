const DATA_CONTACT_INFO = "./src/data/contact-info.json";

const ICONS = {
  phone: `<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>`,
  mail: `<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>`,
  globe: `<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>`,
  pin: `<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.243-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>`,
};

const contactItemHtml = (item) => {
  const { icon, title, detail } = item;
  return `
    <div class="flex items-start transition-all duration-300 hover:scale-105 cursor-pointer">
      <div class="text-brand-cyan mr-3">${ICONS[icon] || ""}</div>
      <div><h3 class="font-bold text-brand-cyan text-lg">${title}</h3><p class="text-brand-cyan dark:text-cyan-400 text-sm">${detail}</p></div>
    </div>
  `;
};

const renderContactInfo = (data) => {
  const container = document.querySelector("#contact-info-section");
  if (!container) return;

  const { heading, description, items } = data;
  const itemsHtml = items.map((item) => contactItemHtml(item)).join("");

  container.innerHTML = `
    <h2 class="text-2xl font-bold text-brand-cyan mb-3">${heading}</h2>
    <p class="text-brand-cyan dark:text-cyan-400 mb-10 text-base max-w-md">${description}</p>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
      ${itemsHtml}
    </div>
  `;
};

export const loadContactInfoSection = async () => {
  try {
    const response = await fetch(DATA_CONTACT_INFO);
    if (!response.ok) throw new Error("Could not find the contact-info data file.");
    const rawData = await response.json();
    renderContactInfo(rawData[0]);
  } catch (err) {
    console.error("Oops:", err);
  }
};
