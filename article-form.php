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
    <title>Écrire un article | CatBlog</title>
</head>

<body>

    <?php require_once "./includes/header.php" ?>

    <main>
        <section class="section--1100"></section>
    </main>

    <?php require_once "./includes/footer.php" ?>

    <script src="./public/js/script.js"></script>
</body>

</html>