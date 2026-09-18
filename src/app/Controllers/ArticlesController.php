<?php

require_once 'app/Controllers/Controller.php';
require_once 'app/Response.php';
require_once 'app/Models/Article.php';

class ArticlesController extends Controller
{

    // private App $app; // ★1. プロパティを追加

    // ★2. コンストラクタで App を受け取って代入する
    // public function __construct(App $app)
    // {
    //     $this->app = $app;
    // }
    //Controller.php（親クラス）で $app を受け取ってプロパティ（$this->app または $this->dbh）を保持する設計になっていれば
    //子クラス側（ここ）に __construct を書く必要なくなる
    //Controller.php（親クラス）で __construct(protected App $app)あるのでここでのコンストラクタ不要


    public function index(): Response
    {



        // 1. モデルに DB 接続（$this->dbh）を渡してインスタンス化
        // $articleModel = new Article($this->dbh);
        //↓1の最新コード DatabaseConnector から getConnection() で PDO を取得
        $pdo = $this->app->getDatabaseConnector()->getConnection();
        $articleModel = new Article($pdo);

        // 2. 記事一覧（投稿者名、タグ名、いいね数など含む）を取得
        $articles = $articleModel->getHome();

        // ★ここにデバッグコードを貼り付ける
        // echo '<pre>';
        // var_dump($articles);
        // exit;

        // 3. ビューへ渡す変数を準備
        $variables = [
            'username' => $_SESSION['username'] ?? 'Guest',
            'articles' => $articles,
        ];

        

        // 4. home/index.php をレンダリングして返す
        $content = $this->render('/home/index.php', $variables);
        return Response::html($content);
    }


    // public function index(): Response
    // {
    //     $sql = 'SELECT name FROM articles;';
    //     $sth = $this->dbh->prepare($sql);
    //     $sth->execute();
    //     $articles = $sth->fetchAll(PDO::FETCH_ASSOC);

    //     $content = $this->render('/home/index.php', ['articles' => $articles]);
    //     return Response::html($content);
    // }

    public function store(): Response
    {
        $tag = $_POST['tag'];   // 入力値を取得

        $sql = 'INSERT INTO tags (name) VALUES (:tag)';
        $sth = $this->dbh->prepare($sql);
        $sth->bindValue('tag', $tag);
        $sth->execute();

        return Response::redirect('/tags');
    }
}
