<?php

class UserDb
{

    private PDOStatement $statementCreateOne;
    private PDOStatement $statementReadOneByEmail;
    private PDOStatement $statementReadOneByUsername;
    private PDOStatement $statementUpdateProfilePicture;

    function __construct(private PDO $pdo)
    {
        $this->statementCreateOne = $pdo->prepare("INSERT INTO user VALUES (
            DEFAULT,
            :firstname,
            :lastname,
            :username,
            :email,
            :password,
            DEFAULT,
            DEFAULT
        )");

        $this->statementReadOneByEmail = $pdo->prepare("SELECT * FROM user WHERE user_email=:email");
        $this->statementReadOneByUsername = $pdo->prepare("SELECT * FROM user WHERE user_username=:username");
        $this->statementUpdateProfilePicture = $pdo->prepare("UPDATE user SET user_picture=:profilePicture WHERE user_id=:userId");
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

    function UpdateProfilePicture(string $profilePicture, int $userId): void
    {
        $this->statementUpdateProfilePicture->bindValue(":profilePicture", $profilePicture);
        $this->statementUpdateProfilePicture->bindValue(":userId", $userId);
        $this->statementUpdateProfilePicture->execute();
    }
}

return new UserDb($pdo);
