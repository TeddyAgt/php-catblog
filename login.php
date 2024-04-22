<?php
require_once __DIR__ . "/database/db_access.php";
$userDb = require_once __DIR__ . "/database/models/UserDb.php";
$sessionDb = require_once __DIR__ . "/database/models/SessionDb.php";


const ERROR_REQUIRED = "Ce champs est requis";
const ERROR_CREDENTIAL_REQUIRED = "Renseignez votre nom d'utilisateur ou votre email";
const ERROR_USER_UNKOWN = "Le nom d'utilisateur est inconnu";
const ERROR_EMAIL_INVALID = "L'adresse mail n'est pas valide";
const ERROR_EMAIL_UNKOWN = "L'adresse mail est inconnue";
const ERROR_PASSWORD_INVALID = "Le mot de passe est incorrect";

$errors = [
    "username" => "",
    "email" => "",
    "password" => "",
    "credential" => ""
];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? "";
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL) ?? "";
    $password = $_POST["password"] ?? "";

    if (!$username && !$email) {
        $errors["credential"] = ERROR_CREDENTIAL_REQUIRED;
    }

    if ($username && !$userDb->userExists($username)) {
        $errors["username"] = ERROR_USER_UNKOWN;
    }

    if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors["email"] = ERROR_EMAIL_INVALID;
    } elseif ($email && !$userDb->emailExists($email)) {
        $errors["email"] = ERROR_EMAIL_UNKOWN;
    }

    if (!$password) {
        $errors["password"] = ERROR_REQUIRED;
    }

    if (empty(array_filter($errors, fn ($e) => $e !== ""))) {
        $user = $username ? $userDb->getUserByUsername($username) : $userDb->getUserByEmail($email);

        if (!password_verify($password, $user["user_password"])) {
            $errors["password"] = ERROR_PASSWORD_INVALID;
        } else {
            $sessionDb->createSession($user["user_id"]);
            header("Location: /profile.php");
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <?php require_once "./includes/head.php"; ?>
    <link rel="stylesheet" href="./public/css/userform.css">
    <title>Connexion | CatBlog</title>
</head>

<body>

    <section class="userform-section userform-section--login">
        <header class="header header--transparent-white">
            <a href="./index.php" class="header__logo-link header__logo-link--white" aria-label="Retour à l'accueil">
                <img src="./public/images/catblog-logo-white.png" alt="logo catblog" class="logo__image">
                <span class="logo__text">CatBlog</span>
            </a>
        </header>

        <form class="user-form user-form--login card" action="/login.php" method="POST">
            <h1 class="register-form__title">Connexion</h1>

            <div class="input-group">
                <label for="username">Nom d'utilisateur</label>
                <input type="text" name="username" id="username" value=<?= $username ?? "" ?>>
                <p class="form-error"><?= $errors["username"] ?? "" ?></p>
            </div>

            <p class="center">ou</p>

            <div class="input-group">
                <label for="email">Email</label>
                <input type="text" name="email" id="email" value=<?= $email ?? "" ?>>
                <p class="form-error"><?= $errors["email"] ?? "" ?></p>
            </div>
            <p class="form-error"><?= $errors["credential"] ?? "" ?></p>
            <div class="separator"></div>

            <div class="input-group">
                <label for="password">Mot de passe</label>
                <div class="password-input-container">
                    <input type="password" name="password" id="password">
                    <button type="button" class="password-visibility-toggler" aria-label="Afficher le mode de passe"><i class="fa-regular fa-eye" aria-hidden="true"></i></button>
                </div>
                <a href="./forgotten-password.php" class="forgotten-password">Mot de passe oublié ?</a>
                <p class="form-error"><?= $errors["password"] ?? "" ?></p>
            </div>

            <div class="controls-group">
                <button type="submit" class="btn btn--primary">Confirmer</button>
                <a href="./index.php" class="btn btn--accent">Annuler</a>
            </div>

            <p class="mt-16 center">Pas encore inscrit ? <a href="./register.php">Créez un compte</a>.</p>
        </form>
    </section>

    <script src="./public/js/script.js"></script>
</body>

</html>