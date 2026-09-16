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
}
