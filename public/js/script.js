const mobileMenuToggler = document.querySelector(".mobile-menu__toggler");
const navigationMenu = document.querySelector(".main-navigation");

let isMenuVisible = false;

mobileMenuToggler.addEventListener("click", toggleNavigationMenu);

function toggleNavigationMenu() {
  if (isMenuVisible) {
    isMenuVisible = !isMenuVisible;
    navigationMenu.classList.remove("main-navigation--active");
    mobileMenuToggler.ariaExpanded = "false";
    mobileMenuToggler.ariaLabel = "Ouvrir le menu de navigation";
    mobileMenuToggler.children[0].src = "./public/icons/bars-solid.svg";
  } else {
    isMenuVisible = !isMenuVisible;
    navigationMenu.classList.add("main-navigation--active");
    mobileMenuToggler.ariaExpanded = "true";
    mobileMenuToggler.ariaLabel = "Fermer le menu de navigation";
    mobileMenuToggler.children[0].src = "./public/icons/xmark-solid.svg";
  }
}
