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
        // DB接続・操作
        $stmt = $this->dbh->query('SHOW DATABASES');
        $databases = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // ① 埋め込むデータを設定
        $variables = [
            'message' => 'This is a test message.',
            'timestamp' => date('Y-m-d H:i:s'),
            'databases' => $databases,
            'lists' => ['Item 1', 'Item 2', 'Item 3'],
        ];

        // ② レンダリング処理の実装
        $content = $this->render('/test/test.php', $variables);

        // ③ HTTPレスポンスの設定
        return Response::html($content, 200);
    }
}
