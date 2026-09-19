<?php

require_once 'app/Controllers/Controller.php';
require_once 'app/Response.php';

class UsersController extends Controller
{
    public function index(): Response
    {
        $content = $this->render('/users/index.php');
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
}
