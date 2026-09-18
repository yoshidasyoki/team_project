<?php

require_once 'Controller.php';

class ArticlesController extends Controller
{
    public function create(): Response
    {
        $sql = 'SELECT id, name FROM tags;';
        $sth = $this->dbh->prepare($sql);
        $sth->execute();
        $tags = $sth->fetchAll(PDO::FETCH_ASSOC);

        $content = $this->render('/articles/create.php', ['tags' => $tags]);
        return Response::html($content);
    }

    public function store()
    {
        $title = $_POST['title'];
        $tags = $_POST['tags'];
        $body = $_POST['body'];

        $articleId = $this->insertArticle($title, $body);
        $this->insertTags($tags, $articleId);

        return Response::redirect('/');
    }

    private function insertArticle(string $title, string $body): int
    {
        $sql = <<<EOF
            INSERT INTO articles
                (title, body, user_id)
            VALUES
                (:title, :body, :user_id)
        EOF;

        $sth = $this->dbh->prepare($sql);
        $sth->execute([
            ':title' => $title,
            ':body' => $body,
            ':user_id' => $_SESSION['user_id'],
        ]);

        return $this->dbh->lastInsertId();
    }

    private function insertTags(array $tags, int $articleId): void
    {
        $params = [];
        $values = [];
        foreach ($tags as $index => $tagId) {
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

    public function show(): Response
    {
        $queryParams = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
        $articleId = str_replace(["id="], "", $queryParams);

        $article = $this->fetchArticle($articleId);
        $content = $this->render('/articles/show.php', [
            ...$article,
            'isAuthor' => $this->checkAuthor($articleId),
        ]);
        return Response::html($content);
    }

    public function edit(): Response
    {
        $queryParams = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
        $articleId = str_replace(["id="], "", $queryParams);

        $article = $this->fetchArticle($articleId);
        $tags = $this->dbh->query('SELECT id, name FROM tags')->fetchAll(PDO::FETCH_ASSOC);
        $content = $this->render('/articles/edit.php', [
            ...$article,
            'tags' => $tags,
        ]);
        return Response::html($content);
    }

    public function update()
    {
        $form = $_POST;
        $queryParams = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
        $articleId = str_replace(["id="], "", $queryParams);
        $this->updateArticle($articleId, $form);
        return Response::redirect("/");
    }

    // 閲覧記事が投稿者本人のものであるかをチェックするメソッド
    private function checkAuthor(string $articleId): bool
    {
        $sql = 'SELECT user_id FROM articles WHERE id = :id';
        $sth = $this->dbh->prepare($sql);
        $sth->execute([':id' => $articleId]);
        $article = $sth->fetch(PDO::FETCH_ASSOC);
        return $article['user_id'] == $_SESSION['user_id'];
    }

    private function fetchArticle(string $articleId): array
    {
        $articleSql = <<<EOF
            SELECT id, title, body FROM articles
            WHERE id = :id;
        EOF;
        $sth = $this->dbh->prepare($articleSql);
        $sth->execute([':id' => $articleId]);
        $article = $sth->fetch(PDO::FETCH_ASSOC);

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
        return [
            'id' => $article['id'],
            'title' => $article['title'],
            'body' => $article['body'],
            'checkedTags' => $checkedTags,
        ];
    }

    private function updateArticle(string $articleId, array $form)
    {
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

        // タグ更新は一度登録情報を削除→INSERTする形で簡易実装
        $deleteSql = 'DELETE FROM articles_tags WHERE article_id = :article_id';
        $sth = $this->dbh->prepare($deleteSql);
        $sth->execute([':article_id' => $articleId]);

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
}
