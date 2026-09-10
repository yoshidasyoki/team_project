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
        $form = $_POST;

        // 仮のユーザー情報（本来はDBから取得する）
        $userInfo = [
            'id' => 1,
            'username' => "test",
            "password" => "pass"
        ];

        if ($form['username'] === $userInfo['username'] && $form['password'] === $userInfo['password']) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $userInfo['id'];
            $_SESSION['username'] = $userInfo['username'];
            return Response::redirect('/');
        }
        return Response::redirect('/login');
    }

    public function logout()
    {
        $_SESSION = [];
        session_regenerate_id(true);
        return Response::redirect('/login');
    }
}
