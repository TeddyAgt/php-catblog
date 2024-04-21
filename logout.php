<?php
require_once __DIR__ . "/database/db_access.php";
$sessionDb = require_once __DIR__ . "/database/models/SessionDb.php";
$user = $sessionDb->isLoggedIn() ?? "";

if ($user) {
    $sessionDb->logout();
}

header("Location: /");
