<?php
require_once __DIR__ . "/database/db_access.php";
$sessionDb = require_once __DIR__ . "/database/models/SessionDb.php";
$user = $sessionDb->isLoggedIn() ?? "";

if ($user) {
    $articleDb = require_once __DIR__ . "/database/models/ArticleDb.php";

    $_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $articleId = $_GET["id"] ?? "";

    if ($articleId) {
        $article = $articleDb->fetchOne($articleId);
        if ($article["article_author"] === $user["user_id"]) {
            $articleDb->deleteOne($articleId);
        }
    }
}

header("Location: /");
