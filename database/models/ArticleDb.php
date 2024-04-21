<?php

class ArticleDb
{
    private PDOStatement $statementCreateOne;
    private PDOStatement $statementFetchOne;
    private PDOStatement $statementFetchAll;
    private PDOStatement $statementUpdateOne;
    private PDOStatement $statementFetchAllByUser;

    function __construct(private PDO $pdo)
    {
        $this->statementCreateOne = $pdo->prepare("INSERT INTO article VALUES (
            DEFAULT,
            :title,
            :image,
            :category,
            :content,
            :author
        )");

        $this->statementFetchOne = $pdo->prepare("SELECT article.*, user.user_firstname, user.user_lastname 
        FROM article LEFT JOIN user ON article.article_author=user.user_id
        WHERE article.article_id=:articleId");

        $this->statementFetchAll = $pdo->prepare("SELECT article.*, user.user_firstname, user.user_lastname 
        FROM article LEFT JOIN user ON article.article_author=user.user_id");

        $this->statementUpdateOne = $pdo->prepare("UPDATE article SET 
            article_title=:title,
            article_image=:image,
            article_category=:category,
            article_content=:content
            WHERE article_id=:articleId
            ");

        $this->statementFetchAllByUser = $pdo->prepare("SELECT * FROM article WHERE article_author=:userId");
    }

    function createArticle(array $article): void
    {
        $this->statementCreateOne->bindValue(":title", $article["title"]);
        $this->statementCreateOne->bindValue(":image", $article["image"]);
        $this->statementCreateOne->bindValue(":category", $article["category"]);
        $this->statementCreateOne->bindValue(":content", $article["content"]);
        $this->statementCreateOne->bindValue(":author", $article["author"]);
        $this->statementCreateOne->execute();
    }

    function fetchOne(int $articleId): array
    {
        $this->statementFetchOne->bindValue(":articleId", $articleId);
        $this->statementFetchOne->execute();
        return $this->statementFetchOne->fetch();
    }

    function fetchAll(): array
    {
        $this->statementFetchAll->execute();
        return $this->statementFetchAll->fetchAll();
    }

    function fetchAllByUser($userId): array
    {
        $this->statementFetchAllByUser->bindValue(":userId", $userId);
        $this->statementFetchAllByUser->execute();
        return $this->statementFetchAllByUser->fetchAll();
    }


    function updateOne($article): void
    {
        $this->statementUpdateOne->bindValue(":title", $article["article_title"]);
        $this->statementUpdateOne->bindValue(":image", $article["article_image"]);
        $this->statementUpdateOne->bindValue(":category", $article["article_category"]);
        $this->statementUpdateOne->bindValue(":content", $article["article_content"]);
        $this->statementUpdateOne->bindValue(":articleId", $article["article_id"]);
        $this->statementUpdateOne->execute();
    }
}


return new ArticleDb($pdo);
