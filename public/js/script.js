// Gestion du menu de navigation mobile

const mobileMenuToggler = document.querySelector(".mobile-menu__toggler");
const navigationMenu = document.querySelector(".main-navigation");

let isMenuVisible = false;

if (mobileMenuToggler) {
  mobileMenuToggler.addEventListener("click", toggleNavigationMenu);
}

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

// Gestion de l'affichage/masquage du mot de passe
const passwordVisibilityToggler = document.querySelector(
  ".password-visibility-toggler"
);
const passwordInputs = document.querySelectorAll('input[type="password"]');
let isPassWordVisible = false;

passwordVisibilityToggler.addEventListener("click", togglePasswordVisibility);

function togglePasswordVisibility() {
  if (isPassWordVisible) {
    isPassWordVisible = !isPassWordVisible;
    passwordInputs.forEach((input) => (input.type = "password"));
    passwordVisibilityToggler.innerHTML =
      '<i class="fa-regular fa-eye" aria-hidden="true"></i>';
    passwordVisibilityToggler.ariaLabel = "Masquer le mot de passe";
  } else {
    isPassWordVisible = !isPassWordVisible;
    passwordInputs.forEach((input) => (input.type = "text"));
    passwordVisibilityToggler.innerHTML =
      '<i class="fa-regular fa-eye-slash" aria-hidden="true"></i>';
    passwordVisibilityToggler.ariaLabel = "Afficher le mot de passe";
  }
}
