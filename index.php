<?php
require_once __DIR__ . "/database/db_access.php";
$userDb = require_once __DIR__ . "/database/models/UserDb.php";
$sessionDb = require_once __DIR__ . "/database/models/SessionDb.php";
$user = $sessionDb->isLoggedIn() ?? "";

?>

<!DOCTYPE html>
<html lang="en">

<head>


    <?php require_once "./includes/head.php"; ?>
    <link rel="stylesheet" href="./public/css/index.css">
    <title>Accueil | CatBlog</title>
</head>

<body>

    <?php require_once "./includes/header.php" ?>

    <main>
        <section class="section hero-section">
            <h1 class="hero-section__title">CatBlog<span class="title--thin">.com</span></h1>
            <p class="subtitle hero-section__subtitle">Cat mojo burrow under covers.</p>
            <a href="#articles" class="btn btn--accent hero-section__cta">Voir les derniers articles</a>
        </section>

        <section class="section--1100 articles-section" id="articles">
            <h2 class="section__title">Derniers articles</h2>

            <article class="article-preview-card article-preview-card--first card">
                <div class="article-preview-card__container article-preview-card__container--top">
                    <img src="https://res.cloudinary.com/dzfp90k4a/image/upload/v1713604285/catblog/articles_picture/article-picture-1.jpg" alt="" class="article-preview-card__image">
                </div>
                <div class="article-preview-card__container article-preview-card__container--bottom">
                    <h3 class="article-preview-card__title">Attack curtains meow</h3>
                    <p class="article-preview-card__author"><span class="light">by</span> Cosmo Fripouillou</p>
                    <p class="article-preview-card__category">Lifestyle</p>
                    <div class="separator"></div>
                    <p class="article-preview-card__preview-text">Attack curtains demand to have some of whatever the human is cooking, then sniff the offering and walk away yet if it fits i sits, avoid the new toy and...</p>
                    <a href="" class="btn btn--primary article-preview-card__link">Lire l'article</a>
                </div>

            </article>
        </section>
    </main>

    <?php require_once "./includes/footer.php" ?>

    <script src="./public/js/script.js"></script>
</body>

</html>