<?php

require_once 'app/Controllers/Controller.php';
require_once 'app/Response.php';
require_once 'app/Models/Article.php';

class UsersController extends Controller
{
    public function index(): Response
    {
        $tags = $this->getPostedArticleByTags($_SESSION['user_id']);

        $articleModel = new Article($this->dbh);
        $articles = $articleModel->findAllByUser($_SESSION['user_id']);

        $postingCounts = count($articles);
        $likesCount = array_sum(array_map(fn($item) => $item['likes_count'], $articles));

        $username = $_SESSION['username'] ?? 'Guest';

        $content = $this->render('/users/index.php', [
            'postingCounts' => $postingCounts,
            'likesCount' => $likesCount,
            'articles' => $articles,
            'tags' => $tags,
            'username' => $username
        ]);
        return Response::html($content);
    }

    public function create(): Response
    {
        $content = $this->render('/users/create.php');
        return Response::html($content);
    }

    public function store()
    {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // 既に登録済みのユーザが存在する場合は再度登録画面に遷移させる
        $isExistUser = $this->checkExistUser($username);
        if ($isExistUser) {
            $_SESSION['flashMessage'] = [
                'errors' => [
                    'users' => 'このユーザ名は既に使用されています',
                ],
            ];
            return Response::redirect('/users/create');
        }

        $sql = <<<EOF
            INSERT INTO users
                (name, password)
            VALUES
                (:name, :password)
        EOF;

        $sql = 'INSERT INTO users (name, password) VALUES (:name, :password)';
        $sth = $this->dbh->prepare($sql);
        $sth->execute([
            ':name' => $username,
            ':password' => password_hash($password, PASSWORD_DEFAULT),
        ]);

        return Response::redirect('/login');
    }

    private function checkExistUser(string $username): bool
    {
        $sql = <<<EOF
            SELECT * FROM users
            WHERE name = :name
        EOF;

        $sth = $this->dbh->prepare($sql);
        $sth->bindValue('name', $username);
        $sth->execute();
        $user = $sth->fetchAll(PDO::FETCH_ASSOC);
        return !empty($user);
    }

    // 指定したユーザのタグごとの投稿数を取得するメソッド
    private function getPostedArticleByTags(string $userId): array
    {
        $sql = <<<EOF
            SELECT t.name, count(*) AS count
            FROM articles_tags AS at
            INNER JOIN tags AS t
                ON t.id = at.tag_id
            INNER JOIN articles AS a
                ON a.id = article_id
            WHERE a.user_id = :user_id
            GROUP BY a.user_id, at.tag_id;
        EOF;
        $sth = $this->dbh->prepare($sql);
        $sth->execute(['user_id' => $userId]);
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }
}
