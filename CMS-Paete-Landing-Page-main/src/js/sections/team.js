const DATA_TEAM = "./src/data/team.json";

const renderTeam = (data) => {
  const container = document.querySelector("#team-section");
  if (!container) return;

  const { image, alt } = data;

  container.innerHTML = `
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
      <div class="rounded-2xl overflow-hidden shadow-xl">
        <img src="${image}" alt="${alt}" class="w-full h-48 sm:h-64 lg:h-80 object-cover" />
      </div>
    </div>
  `;
};

export const loadTeamSection = async () => {
  try {
    const response = await fetch(DATA_TEAM);
    if (!response.ok) throw new Error("Could not find the team data file.");
    const rawData = await response.json();
    renderTeam(rawData[0]);
  } catch (err) {
    console.error("Oops:", err);
  }
};
