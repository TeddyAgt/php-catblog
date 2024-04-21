<?php
require_once __DIR__ . "/database/db_access.php";
$userDb = require_once __DIR__ . "/database/models/UserDb.php";
$sessionDb = require_once __DIR__ . "/database/models/SessionDb.php";
$user = $sessionDb->isLoggedIn() ?? "";

if (!$user) {
    header("Location: /login.php");
}

$articleDb = require_once __DIR__ . "/database/models/ArticleDb.php";

$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$articleId = $_GET["id"] ?? "";
$category = "";

// Récupération de l'article si mode édition
if ($articleId) {

    $article = $articleDb->fetchOne($articleId);
    /* On vérifie que l'article appartient bien à l'utilisateur connecté, 
     sinon on le redirige car il n'a pas le droit de le modifier */
    if ($article["article_author"] !== $user["user_id"]) {
        header("Location: /");
    }
    $title = $article["article_title"];
    $image = $article["article_image"];
    $category = $article["article_category"];
    $content = $article["article_content"];
}

const ERROR_REQUIRED = "Veuillez renseigner ce champs";
const ERROR_TITLE_TOO_SHORT = "Le titre doit faire 8 caractères minimum";
const ERROR_CONTENT_TOO_SHORT = "L'article doit faire 50 caractères minimum";
const ERROR_IMAGE_URL = "L'URL de l'image n'est pas valide";
$errors = [
    "title" => "",
    "image" => "",
    "category" => "",
    "content" => ""
];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Nettoyage des données
    $_POST = filter_input_array(INPUT_POST, [
        "title" => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        "image" => FILTER_SANITIZE_URL,
        "category" => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        "content" => [
            "filter" => FILTER_SANITIZE_SPECIAL_CHARS,
            "flags" => FILTER_FLAG_NO_ENCODE_QUOTES
        ]
    ]);
    echo $articleId . "4" . "<br>";
    $title = $_POST["title"] ?? "";
    $image = $_POST["image"] ?? "";
    $category = $_POST["category"] ?? "";
    $content = $_POST["content"] ?? "";


    // Gestion des erreurs
    if (!$title) {
        $errors["title"] = ERROR_REQUIRED;
    } elseif (mb_strlen($title) < 8) {
        $errors["title"] = ERROR_TITLE_TOO_SHORT;
    }

    if (!$image) {
        $errors["image"] = ERROR_REQUIRED;
    } elseif (!filter_var($image, FILTER_VALIDATE_URL)) {
        $errors["image"] = ERROR_IMAGE_URL;
    }

    if (!$category) {
        $errors["category"] = ERROR_REQUIRED;
    }

    if (!$content) {
        $errors["content"] = ERROR_REQUIRED;
    } elseif (mb_strlen($title) < 8) {
        $errors["content"] = ERROR_CONTENT_TOO_SHORT;
    }

    if (empty(array_filter($errors, fn ($e) => $e !== ""))) {
        // Si pas d'erreur, on vérifie si on est en mode édition ou création
        if ($articleId) {
            // mode édition
            $article["article_title"] = $title;
            $article["article_image"] = $image;
            $article["article_category"] = $category;
            $article["article_content"] = $content;
            $articleDb->updateOne($article);
        } else {
            // mode création
            $articleDb->createArticle([
                "title" => $title,
                "image" => $image,
                "category" => $category,
                "content" => $content,
                "author" => $user["user_id"]
            ]);
        }
        header("Location: /");
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>


    <?php require_once "./includes/head.php"; ?>
    <link rel="stylesheet" href="./public/css/article-form.css">
    <title><?= $articleId ? "Éditer" : "Écrire" ?> un article | CatBlog</title>
</head>

<body>

    <?php require_once "./includes/header.php" ?>

    <main>
        <section class="section--1100 article-form-section">
            <h1 class="main-title article-form__title"><?= $articleId ? "Éditer" : "Écrire" ?> un article</h1>

            <form action="/article-form.php<?= $articleId ? "?id=$articleId" : ""; ?>" method="POST" class="card article-form__form">
                <div class="input-group">
                    <label for="title">Titre</label>
                    <input type="text" name="title" id="title" value="<?= $title ?? "" ?>">
                    <p class="form-error"><?= $errors["title"] ?? "" ?></p>
                </div>

                <div class="input-group">
                    <label for="image">Image</label>
                    <input type="text" name="image" id="image" value=<?= $image ?? "" ?>>
                    <p class="form-error"><?= $errors["image"] ?? "" ?></p>
                </div>

                <div class="input-group">
                    <label for="category">Category</label>
                    <select name="category" id="category">
                        <option <?= !$category || $category === "lifestyle" ? "selected" : ""; ?> value="lifestyle">Lifestyle</option>
                        <option <?= $category === "loisirs" ? "selected" : ""; ?> value="loisirs">Loisirs</option>
                        <option <?= $category === "nourriture" ? "selected" : ""; ?> value="nourriture">Nourriture</option>
                    </select>
                    <p class="form-error"><?= $errors["category"] ?? "" ?></p>
                </div>

                <div class="input-group">
                    <label for="content">Article</label>
                    <textarea name="content" id="content"><?= $content ?? ""; ?></textarea>
                    <p class="form-error"><?= $errors["content"] ?? "" ?></p>
                </div>

                <div class="controls-group">
                    <button type="submit" class="btn btn--primary">Sauvegarder</button>
                    <a href="./index.php" class="btn btn--accent">Annuler</a>
                </div>
            </form>
        </section>
    </main>

    <?php require_once "./includes/footer.php" ?>

    <script src="./public/js/script.js"></script>
</body>

</html>