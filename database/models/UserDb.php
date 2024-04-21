<?php

class UserDb
{

    private PDOStatement $statementCreateOne;
    private PDOStatement $statementReadOneByEmail;
    private PDOStatement $statementReadOneByUsername;

    function __construct(private PDO $pdo)
    {
        $this->statementCreateOne = $pdo->prepare("INSERT INTO user VALUES (
            DEFAULT,
            :firstname,
            :lastname,
            :username,
            :email,
            :password,
            DEFAULT
        )");

        $this->statementReadOneByEmail = $pdo->prepare("SELECT * FROM user WHERE user_email=:email");

        $this->statementReadOneByUsername = $pdo->prepare("SELECT * FROM user WHERE user_username=:username");
    }

    function emailExists(string $email): bool
    {
        $this->statementReadOneByEmail->bindValue(":email", $email);
        $this->statementReadOneByEmail->execute();
        $user = $this->statementReadOneByEmail->fetch();
        return $user ? true : false;
    }

    function userExists(string $username): bool
    {
        $this->statementReadOneByUsername->bindValue(":username", $username);
        $this->statementReadOneByUsername->execute();
        $user = $this->statementReadOneByUsername->fetch();
        return $user ? true : false;
    }

    function createUser(array $user): void
    {
        $hashedPassword = password_hash($user["password"], PASSWORD_ARGON2I);
        $this->statementCreateOne->bindValue(":firstname", $user["firstname"]);
        $this->statementCreateOne->bindValue(":lastname", $user["lastname"]);
        $this->statementCreateOne->bindValue(":username", $user["username"]);
        $this->statementCreateOne->bindValue(":email", $user["email"]);
        $this->statementCreateOne->bindValue(":password", $hashedPassword);
        $this->statementCreateOne->execute();
    }

    function getUserByUsername(string $username): array
    {

        $this->statementReadOneByUsername->bindValue(":username", $username);
        $this->statementReadOneByUsername->execute();
        return $this->statementReadOneByUsername->fetch();
    }

    function getUserByEmail(string $email): array
    {
        $this->statementReadOneByEmail->bindValue(":email", $email);
        $this->statementReadOneByEmail->execute();
        return $this->statementReadOneByEmail->fetch();
    }

    function logout()
    {
    }
}

return new UserDb($pdo);
