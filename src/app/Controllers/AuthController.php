<?php

class AuthController extends Controller
{
    public function index()
    {
        $content = $this->render('login/index.php');
        return Response::html($content);
    }

    public function auth()
    {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $sql = 'SELECT * FROM users WHERE name = :name';
        $sth = $this->dbh->prepare($sql);
        $sth->execute([':name' => $username]);
        $user = $sth->fetch(PDO::FETCH_ASSOC);

        if (!empty($user) && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['name'];
            return Response::redirect('/');
        }

        $_SESSION['flashMessage'] = [
            'errors' => [
                'auth' => 'ログインに失敗しました',
            ],
        ];
        return Response::redirect('/login');
    }

    public function logout()
    {
        $_SESSION = [];
        session_regenerate_id(true);
        return Response::redirect('/login');
    }
}
