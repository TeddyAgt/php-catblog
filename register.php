<?php
require_once __DIR__ . "/database/db_access.php";
$userDb = require_once __DIR__ . "/database/models/UserDb.php";
$sessionDb = require_once __DIR__ . "/database/models/SessionDb.php";

const ERROR_REQUIRED = "Ce champs est requis";
const ERROR_TOO_SHORT = "3 caractères minimum";
const ERROR_USER_ALREADY_EXISTS = "Ce nom d'utilisateur est déjà pris";
const ERROR_EMAIL_INVALID = "L'adresse mail n'est pas valide";
const ERROR_EMAIL_ALREADY_EXISTS = "Cette adresse mail est déjà liée à un compte";
const ERROR_PASSWORD_TOO_SHORT = "Le mot de passe doit faire 6 caractères minimum";
const ERROR_PASSWORD_MISMATCH = "Le mot de passe de confirmation ne correspond pas";

$errors = [
    "firstname" => "",
    "lastname" => "",
    "username" => "",
    "email" => "",
    "password" => "",
    "confirmation" => ""
];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $firstname = filter_input(INPUT_POST, "firstname", FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? "";
    $lastname = filter_input(INPUT_POST, "lastname", FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? "";
    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? "";
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL) ?? "";
    $password = $_POST["password"] ?? "";
    $confirmation = $_POST["confirmation"] ?? "";

    if (!$firstname) {
        $errors["firstname"] = ERROR_REQUIRED;
    } elseif (mb_strlen($firstname) < 3) {
        $errors["firstname"] = ERROR_TOO_SHORT;
    }

    if (!$lastname) {
        $errors["lastname"] = ERROR_REQUIRED;
    } elseif (mb_strlen($lastname) < 3) {
        $errors["lastname"] = ERROR_TOO_SHORT;
    }

    if (!$username) {
        $errors["username"] = ERROR_REQUIRED;
    } elseif (mb_strlen($username) < 3) {
        $errors["username"] = ERROR_TOO_SHORT;
    } elseif ($userDb->userExists($username)) {
        $errors["username"] = ERROR_USER_ALREADY_EXISTS;
    }

    if (!$email) {
        $errors["email"] = ERROR_REQUIRED;
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors["email"] = ERROR_EMAIL_INVALID;
    } elseif ($userDb->emailExists($email)) {
        $errors["email"] = ERROR_EMAIL_ALREADY_EXISTS;
    }

    if (!$password) {
        $errors["password"] = ERROR_REQUIRED;
    } elseif (mb_strlen($password) < 6) {
        $errors["password"] = ERROR_PASSWORD_TOO_SHORT;
    }

    if (!$confirmation) {
        $errors["confirmation"] = ERROR_REQUIRED;
    } elseif ($confirmation !== $password) {
        $errors["confirmation"] = ERROR_PASSWORD_MISMATCH;
    }

    if (empty(array_filter($errors, fn ($e) => $e !== ""))) {
        $userDb->createUser([
            "firstname" =>  $firstname,
            "lastname" => $lastname,
            "username" => $username,
            "email" => $email,
            "password" => $password
        ]);
        header("Location: /login.php");
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>


    <?php require_once "./includes/head.php"; ?>
    <link rel="stylesheet" href="./public/css/userform.css">
    <title>Inscription | CatBlog</title>
</head>

<body>

    <section class="userform-section userform-section--register">
        <header class="header header--transparent-white">
            <a href="./index.php" class="header__logo-link header__logo-link--white" aria-label="Retour à l'accueil">
                <img src="./public/images/catblog-logo-white.png" alt="logo catblog" class="logo__image">
                <span class="logo__text">CatBlog</span>
            </a>
        </header>

        <form class="user-form user-form--register user card" action="/register.php" method="POST">
            <h1 class="register-form__title">Créer un compte</h1>

            <div class="input-group">
                <label for="firstname">Prénom</label>
                <input type="text" name="firstname" id="firstname" value="<?= $firstname ?? "" ?>" classe=<?= !!$errors["firstname"] ? "input-error" : ""; ?>>
                <p class="form-error"><?= $errors["firstname"] ?? "" ?></p>
            </div>

            <div class="input-group">
                <label for="lastname">Nom</label>
                <input type="text" name="lastname" id="lastname" value=<?= $lastname ?? "" ?>>
                <p class="form-error"><?= $errors["lastname"] ?? "" ?></p>
            </div>

            <div class="input-group">
                <label for="username">Nom d'utilisateur</label>
                <input type="text" name="username" id="username" value=<?= $username ?? "" ?>>
                <p class="form-error"><?= $errors["username"] ?? "" ?></p>
            </div>

            <div class="input-group">
                <label for="email">Email</label>
                <input type="text" name="email" id="email" value=<?= $email ?? "" ?>>
                <p class="form-error"><?= $errors["email"] ?? "" ?></p>
            </div>

            <div class="input-group">
                <label for="password">Mot de passe</label>
                <div class="password-input-container">
                    <input type="password" name="password" id="password">
                    <button type="button" class="password-visibility-toggler" aria-label="Afficher le mode de passe"><i class="fa-regular fa-eye" aria-hidden="true"></i></button>
                </div>
                <p class="form-error"><?= $errors["password"] ?? "" ?></p>
            </div>

            <div class="input-group">
                <label for="password-confirmation">Confirmation de mot de passe</label>
                <input type="password" name="confirmation" id="password-confirmation">
                <p class="form-error"><?= $errors["confirmation"] ?? "" ?></p>
            </div>

            <div class="controls-group">
                <button type="submit" class="btn btn--primary">Confirmer</button>
                <a href="./index.php" class="btn btn--accent">Annuler</a>
            </div>

            <p class="mt-16 center">Vous avez déjà un compte ? <a href="./login.php">Connectez-vous</a>.</p>
        </form>
    </section>

    <script src="./public/js/script.js"></script>
</body>

</html>