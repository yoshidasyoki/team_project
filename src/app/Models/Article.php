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

    // Article.php 内へ追加

    public function storeArticle(array $form, string $userId): void //←: voidは、return でデータを出力しない
    {
        try {
            $this->dbh->beginTransaction();

            // 1. articles テーブルへの保存
            $articleSql = "
                INSERT INTO articles (user_id, title, body, created_at)
                VALUES (:user_id, :title, :body, NOW());
            ";

            $sth = $this->dbh->prepare($articleSql);
            $sth->execute([
                ':user_id' => $userId,
                ':title'   => $form['title'],
                ':body'    => $form['body'],
            ]);

            // 保存した記事のIDを取得
            $articleId = $this->dbh->lastInsertId();

            // 2. タグが選択されている場合は articles_tags (中間テーブル) に保存
            if (!empty($form['tags']) && is_array($form['tags'])) {
                $params = [':article_id' => $articleId];
                $values = [];

                foreach ($form['tags'] as $index => $tagId) {
                    $values[] = "(:article_id, :tag_id_$index)";
                    $params["tag_id_$index"] = $tagId;
                }

                $placeholder = implode(',', $values);
                $tagSql = "INSERT INTO articles_tags (article_id, tag_id) VALUES $placeholder";

                $sth = $this->dbh->prepare($tagSql);
                $sth->execute($params);
            }

            $this->dbh->commit();
        } catch (Exception $e) {
            $this->dbh->rollBack();
            throw $e;
        }
    }

    public function fetchArticle(string $articleId): array
    {
        // タイトルと本文（articlesテーブルの情報）を取得
        $articleSql = <<<EOF
            SELECT id, title, body FROM articles
            WHERE id = :id;
        EOF;
        $sth = $this->dbh->prepare($articleSql);
        $sth->execute([':id' => $articleId]);
        $article = $sth->fetch(PDO::FETCH_ASSOC);

        // 記事のタグ情報（articles_tagsテーブルの情報）を取得
        $tagsSql = <<<EOF
            SELECT at.tag_id AS id, t.name
            FROM articles_tags AS at
            INNER JOIN tags AS t
            ON at.tag_id = t.id
            WHERE article_id = :article_id;
            EOF;
        $sth = $this->dbh->prepare($tagsSql);
        $sth->execute([':article_id' => $articleId]);
        $checkedTags = $sth->fetchAll(PDO::FETCH_ASSOC);

        // 記事情報（タイトル、本文、タグ情報）を組み立てて返却
        return [
            'id' => $article['id'],
            'title' => $article['title'],
            'body' => $article['body'],
            'checkedTags' => $checkedTags,
        ];
    }

    public function updateArticle(string $articleId, array $form): void
    {
        // タイトルと本文を更新
        $articleSql = <<<EOF
            UPDATE articles
            SET title = :title,
                body = :body
            WHERE id = :id;
        EOF;

        $sth = $this->dbh->prepare($articleSql);
        $sth->execute([
            ':title' => $form['title'],
            ':body' => $form['body'],
            ':id' => $articleId,
        ]);

        // タグ情報は一度登録情報を削除→INSERTする形で更新処理を実装
        $deleteSql = 'DELETE FROM articles_tags WHERE article_id = :article_id';
        $sth = $this->dbh->prepare($deleteSql);
        $sth->execute([':article_id' => $articleId]);

        // 更新後、タグが1つも登録されない場合は削除のみ実施
        if (empty($form['tags'])) {
            return;
        }

        $params = [];
        $values = [];
        foreach ($form['tags'] as $index => $tagId) {
            $values[] = "(:article_id, :tag_id_$index)";
            $params['article_id'] = "$articleId";
            $params["tag_id_$index"] = "$tagId";
        }

        $placeholder = implode(",", $values);
        $sql = <<<EOF
            INSERT INTO articles_tags
                (article_id, tag_id)
            VALUES
                $placeholder
        EOF;
        $sth = $this->dbh->prepare($sql);
        $sth->execute($params);
    }

    public function deleteArticle(string $articleId): void
    {
        try {
            $this->dbh->beginTransaction();
            $pivotSql = 'DELETE FROM articles_tags WHERE article_id = :id';
            $sth = $this->dbh->prepare($pivotSql);
            $sth->execute([':id' => $articleId]);

            $articleSql = 'DELETE FROM articles WHERE id = :id';
            $sth = $this->dbh->prepare($articleSql);
            $sth->execute([':id' => $articleId]);
            $this->dbh->commit();
        } catch (Exception) {
            $this->dbh->rollBack();
        }
    }
}
