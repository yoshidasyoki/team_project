<?php

class Article
{
    private PDO $dbh;

    public function __construct(PDO $dbh)
    {
        $this->dbh = $dbh;
    }

    public function getHome(): array
    {
        // 記事・ユーザー・中間テーブル・タグを一括で結合し、グループ化して取得
        $sql = "
            SELECT
                a.id,
                a.title,
                a.created_at,
                a.likes_count,
                u.name AS author_name,
                GROUP_CONCAT(t.name) AS tags
            FROM articles a
            JOIN users u
                ON a.user_id = u.id
            LEFT JOIN articles_tags at
                ON a.id = at.article_id
            LEFT JOIN tags t
                ON at.tag_id = t.id
            GROUP BY a.id
            ORDER BY a.created_at DESC
        ";

        $stmt = $this->dbh->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
