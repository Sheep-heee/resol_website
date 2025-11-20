const refreshBtn = document.querySelector(".refresh-btn");
const menuText = document.querySelector(".menu-text");

if (refreshBtn && menuText && window.RESOL_RANDOM_MENU) {
  const menus = Array.isArray(RESOL_RANDOM_MENU.menus)
    ? RESOL_RANDOM_MENU.menus
    : [];

  refreshBtn.addEventListener("click", () => {
    if (menus.length === 0) return;
    const random = menus[Math.floor(Math.random() * menus.length)];
    menuText.textContent = random;
  });
}
