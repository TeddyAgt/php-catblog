<?php
// $secret = getenv("HASH_SECRET");
$secret = "djskhf3556l:0j580t,";

class SessionDB
{

    private PDOStatement $statementCreateOne;
    private PDOStatement $statementReadOneSession;
    private PDOStatement $statementReadOneUserById;
    private PDOStatement $statementDeleteOne;

    function __construct(private PDO $pdo)
    {
        $this->statementCreateOne = $pdo->prepare("INSERT INTO session VALUES (:sessionId, :userId)");
        $this->statementReadOneSession = $pdo->prepare("SELECT * FROM session WHERE session_id=:sessionId");
        $this->statementReadOneUserById = $pdo->prepare("SELECT * FROM user WHERE user_id=:userId");
        $this->statementDeleteOne = $pdo->prepare("DELETE FROM session WHERE session_id=:sessionId");
    }

    function isLoggedIn(): array | string
    {
        global $secret;
        $sessionId = $_COOKIE["session"] ?? "";
        $signature = $_COOKIE["signature"] ?? "";
        if ($sessionId && $signature) {
            $hash = hash_hmac("sha256", $sessionId, $secret);

            if (hash_equals($hash, $signature)) {
                $this->statementReadOneSession->bindValue(":sessionId", $sessionId);
                $this->statementReadOneSession->execute();
                $session = $this->statementReadOneSession->fetch();
                if ($session) {
                    $user = $this->getUserById($session["session_user_id"]);
                }
            }
        }
        return $user ?? "";
    }

    function getUserById($userId)
    {
        $this->statementReadOneUserById->bindValue(":userId", $userId);
        $this->statementReadOneUserById->execute();
        return $this->statementReadOneUserById->fetch();
    }

    function createSession(int $userId): void
    {
        global $secret;

        $sessionId = bin2hex(random_bytes(32));
        $signature = hash_hmac("sha256", $sessionId,  $secret);

        $this->statementCreateOne->bindValue(":sessionId", $sessionId);
        $this->statementCreateOne->bindValue(":userId", $userId);
        $this->statementCreateOne->execute();

        setcookie("session", $sessionId, time() + 1209600, "", "", false, true); // 1209600 = 2 semaines
        setcookie("signature", $signature, time() + 1209600, "", "", false, true);
    }

    function logout(): void
    {
        $sessionId = $_COOKIE["session"] ?? "";
        if ($sessionId) {
            $this->statementDeleteOne->bindValue(":sessionId", $sessionId);
            $this->statementDeleteOne->execute();
            setcookie("session", "", time() - 1);
            setcookie("signature", "", time() - 1);
            return;
        }
    }
}

return new SessionDb($pdo);
