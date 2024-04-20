<header>
    <a href="./index.php" class="header__logo-link" aria-label="Retour à l'accueil">
        <img src="./public/images/catblog-logo.png" alt="logo catblog" class="logo__image">
        <span class="logo__text">CatBlog</span>
    </a>

    <button class="mobile-menu__toggler" aria-controls="main-navigation" aria-expanded="false" aria-label="Ouvrir le menu de navigation">
        <img src="./public/icons/bars-solid.svg" alt="" class="mobile-menu__toggler-icon" aria-hidden="true">
    </button>

    <nav class="main-navigation" id="main-navigation">
        <a href="./index.php" class="main-navigation__link <?= $_SERVER["REQUEST_URI"] === "/index.php" ? "main-navigation__link--active" : ""; ?>" title="Accueil">Accueil</a>
        <a href="./about.php" class="main-navigation__link <?= $_SERVER["REQUEST_URI"] === "/about.php" ? "main-navigation__link--active" : ""; ?>" title="L'équipe">L'équipe</a>
        <a href="./contact.php" class="main-navigation__link <?= $_SERVER["REQUEST_URI"] === "/contact.php" ? "main-navigation__link--active" : ""; ?>" title="Nous contacter">Contact</a>
    </nav>
</header>