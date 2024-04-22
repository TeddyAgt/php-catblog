<?php
require_once __DIR__ . "/database/db_access.php";
$userDb = require_once __DIR__ . "/database/models/UserDb.php";
$sessionDb = require_once __DIR__ . "/database/models/SessionDb.php";
$user = $sessionDb->isLoggedIn() ?? "";

if (!$user) {
    header("Location: /");
}

$articleDb = require_once __DIR__ . "/database/models/ArticleDb.php";
$articles = $articleDb->fetchAllByUser($user["user_id"]) ?? [];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once "./includes/head.php"; ?>
    <link rel="stylesheet" href="./public/css/profile.css">
    <title>Profil | CatBlog</title>
</head>

<body>

    <?php require_once "./includes/header.php" ?>

    <main>

        <section class="profile-section">

            <article class="profile-section__subsection profile-section__subsection--user-infos card">
                <h2 class="section__title"><?= $user["user_firstname"] . " " . $user["user_lastname"]; ?></h2>
                <button class="user-info__picture-container" aria-controls="change-user-infos-modal">
                    <img class="user-info__profile-picture" src=<?= $user["user_picture"]; ?> alt="">
                </button>
            </article>

            <article class="profile-section__subsection profile-section__subsection--articles card">
                <h2 class="section__title">Mes articles</h2>
                <ul class="profile-section__article-list">
                    <?php foreach ($articles as $article) : ?>
                        <li class="article-list__element">
                            <a href="./article.php?id=<?= $article["article_id"] ?>" class="article-list__link">
                                <h3 class="article-list__title"><?= $article["article_title"] ?> <span class="article-list__category">(<?= ucfirst($article["article_category"]); ?>)</span></h3>
                            </a>
                            <div class="controls-group article-list__controls-group article-list__controls-group--mobile">
                                <a href="./article-form.php?id=<?= $article["article_id"]; ?>" class="btn btn--primary" aria-label="Éditer l'article" title="Éditer l'article">
                                    <i class="fa-solid fa-pencil" aria-hidden="true"></i>
                                </a>
                                <a href="./index.php" class="btn btn--accent" aria-label="Supprimer l'article" title="Supprimer l'article">
                                    <i class="fa-regular fa-trash-can" aria-hidden="true"></i>
                                </a>
                            </div>
                            <div class="controls-group article-list__controls-group article-list__controls-group--desktop">
                                <a href="./article-form.php?id=<?= $article["article_id"]; ?>" class="btn btn--primary" title="Éditer l'article">Éditer</a>
                                <a href="./delete-article.php?id=<?= $article["article_id"]; ?>" class="btn btn--accent" title="Supprimer l'article">Supprimer</a>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </article>

        </section>

    </main>

    <?php require_once "./includes/footer.php" ?>

    <script src="./public/js/script.js"></script>
</body>

</html>