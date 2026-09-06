<?php

require_once 'app/Controllers/Controller.php';
require_once 'app/Response.php';

class TestController extends Controller
{
    public function index(): Response
    {
        $content = $this->render('/test/index.php');
        return Response::html($content);
    }

    public function test(): Response
    {
        // DB接続確認
        $stmt = $this->dbh->query('SHOW DATABASES'); // 簡単なクエリを実行してDBへの接続確認
        $databases = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $variables = [
            'message' => 'This is a test message.',
            'timestamp' => date('Y-m-d H:i:s'),
            'databases' => $databases,
            'lists' => ['Item 1', 'Item 2', 'Item 3'],
        ];
        $content = $this->render('/test/test.php', $variables);
        return Response::html($content);
    }
}
