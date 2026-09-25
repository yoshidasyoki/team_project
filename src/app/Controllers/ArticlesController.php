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
        $search = $_GET['search']??null;
        $value = $_GET['value']??null;

        // $articles = [];
        switch (true) {
            case !$search || !$value:
                $articles = $articleModel->getHome();
                break;
            case $search === 'tag':
                $articles = $articleModel->findByTag($value);
                break;
            case $search === 'keyword':
                $articles = $articleModel->findByKeyword($value);
                break;
            default:
                throw new HttpNotFoundException();
        }

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

    public function likesCount(): Response
    {
        $articlesId = $_GET['id'];
        $articlesModel = new Article($this->dbh);
        $articlesModel->likesCount($articlesId);
        return Response::redirect('/');
    }

    public function show(): Response
    {
        $articleId = $_GET['id'];
        $username = $_SESSION['username'] ?? 'Guest';

        $articleModel = new Article($this->dbh);
        $article = $articleModel->find($articleId);
        $content = $this->render('/articles/show.php', [
            ...$article,
            'isAuthor' => $this->checkAuthor($articleId),
            'username' => $username
        ]);


        return Response::html($content);
    }

    public function edit(): Response
    {
        $articleId = $_GET['id'];
        $username = $_SESSION['username'] ?? 'Guest';

        $articleModel = new Article($this->dbh);
        $article = $articleModel->find($articleId);
        $tags = $this->dbh->query('SELECT id, name FROM tags')->fetchAll(PDO::FETCH_ASSOC);
        $content = $this->render('/articles/edit.php', [
            ...$article,
            'tags' => $tags,
            'username' => $username
        ]);

        return Response::html($content);
    }

    public function update(): Response
    {
        $form = $_POST;
        $articleId = $_GET['id'];

        $articleModel = new Article($this->dbh);
        $articleModel->update($articleId, $form);
        return Response::redirect("/");
    }

    public function delete(): Response
    {
        $articleId = $_GET['id'];

        $articleModel = new Article($this->dbh);
        $articleModel->delete($articleId);

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


    // public function showCreate(): Response
    // {
    //     // 1. ビューへ渡す変数を準備（必要に応じて）
    //     $variables = [
    //         'username' => $_SESSION['username'] ?? 'Guest',
    //     ];

    //     // 2. renderメソッドを使ってHTMLを生成し、Responseオブジェクトとして返す
    //     // ※ビューファイルの配置場所に合わせてパスを調整してください (例: /articles/create.php)
    //     $content = $this->render('/create.html', $variables);
    //     return Response::html($content);
    // }

    public function showCreate(): Response
    {
        // 1. PDO接続を取得してタグ一覧を取得する
        $pdo = $this->app->getDatabaseConnector()->getConnection();
        $stmt = $pdo->query('SELECT id, name FROM tags');
        $tags = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 2. ビューへ渡す変数を準備
        $variables = [
            'username' => $_SESSION['username'] ?? 'Guest',
            'tags'     => $tags, // タグ一覧を追加
        ];

        // 3. renderメソッドを使ってHTML/PHPテンプレートを生成
        // ※PHPのタグ（foreach）を動かすため、テンプレートファイルが .php の場合はパスを調整してください（例: /create.php や /articles/create.php）
        $content = $this->render('/articles/create.php', $variables);
        return Response::html($content);
    }

    public function store(): Response
    {
        $form = $_POST;
        $userId = $_SESSION['user_id'];

        // PDO接続
        $pdo = $this->app->getDatabaseConnector()->getConnection();
        $articleModel = new Article($pdo);

        // DBへ保存処理を実行
        $articleModel->storeArticle($form, $userId);

        // HOME画面へリダイレクト
        return Response::redirect("/");
    }
}
