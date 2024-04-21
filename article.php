<?php
require_once __DIR__ . "/database/db_access.php";
$userDb = require_once __DIR__ . "/database/models/UserDb.php";
$sessionDb = require_once __DIR__ . "/database/models/SessionDb.php";
$user = $sessionDb->isLoggedIn() ?? "";

$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$articleId = $_GET["id"] ?? "";

if (!$articleId) {
    header("Location: /");
}

$articleDb = require_once __DIR__ . "/database/models/ArticleDb.php";
$article = $articleDb->fetchOne($articleId);

?>

<!DOCTYPE html>
<html lang="en">

<head>


    <?php require_once "./includes/head.php"; ?>
    <link rel="stylesheet" href="./public/css/article.css">
    <title><?= $article["article_title"]; ?> - by <?= $article["user_firstname"] . " " . $article["user_lastname"][0] . "."; ?> | CatBlog</title>
</head>

<body>

    <?php require_once "./includes/header.php" ?>

    <main>
        <section class="section--1100 read-article-section">
            <article class="article">

                <header class="article__header">
                    <h1 class="main-title article__title"><?= $article["article_title"]; ?></h1>
                    <p class="article__author">by <a href="./public-profile.php?author=<?= $article["article_author"]; ?>" class="article__author-link" title="Voir le profil"><span class="article__author-name"><?= $article["user_firstname"]; ?></span> <span class="article__author-name"><?= $article["user_lastname"]; ?></span></a></p>
                </header>

                <div class="separator"></div>

                <div class="article__picture-container">
                    <img class="article__picture" src="<?= $article["article_image"]; ?>" alt="">
                </div>
                <p class="article__content"><?= $article["article_content"]; ?></p>

                <footer class="article__footer">

                    <div class="reaction-group article__reaction-group">
                        <div class="reaction-group__container">
                            <button type="button" class="reaction-button btn" aria-label="Donner un like">
                                <i class="fa-regular fa-heart" aria-hidden="true"></i>
                            </button>
                            <span class="reaction-group__text">2</span>
                        </div>

                        <div class="reaction-group__container">
                            <button type="button" class="reaction-button btn" aria-label="Poster un commentaire">
                                <i class="fa-regular fa-comment"></i>
                            </button>
                            <span class="reaction-group__text">12</span>
                        </div>
                    </div>

                    <div class="controls-group article__controls-group">
                        <a href="./article-form.php?id=<?= $article["article_id"]; ?>" type="submit" class="btn btn--primary">Éditer</a>
                        <a href="./index.php" class="btn btn--accent">Supprimer</a>
                    </div>
                </footer>

            </article>
        </section>
    </main>

    <?php require_once "./includes/footer.php" ?>

    <script src="./public/js/script.js"></script>
</body>

</html>