<?php

// require_onceで必要なクラスを読み込むこと
require_once 'app/Router.php';
require_once 'app/View.php';
require_once 'app/Exceptions/HttpNotFoundException.php';
require_once 'app/DatabaseConnector.php';
require_once 'app/Middlewares/AuthMiddleware.php';

require_once 'app/Controllers/TestController.php';
require_once 'app/Controllers/AuthController.php';

class App
{
    private Router $routes;
    private View $view;
    private DatabaseConnector $databaseConnector;

    public function __construct()
    {
        $this->routes = new Router($this->registerRoutes());
        $this->view = new View(__DIR__ . '/resources/views');
    }

    public function run(): void
    {
        try {
            session_start();
            $this->databaseConnector = new DatabaseConnector([
                'hostname' => 'db',     // DockerのDBコンテナ名
                'database' => 'team_dev',
                'username' => 'team_user',
                'password' => 'pass',
            ]);

            // リクエストURIを取得してルーティングを行う
            $accessPath = $_SERVER['REQUEST_URI'];
            $route = $this->routes->getRoute($accessPath);

            // ログイン状態でないとアプリ内部にアクセスできないよう制御
            $middleware = $route['middleware'];
            if (!$middleware() ?? false) {
                Response::redirect('/login')->send();
                return;
            }

            // ルーティングで指定したロジック処理を実行
            $controllerName = $route['controller'];
            $actionName = $route['action'];
            $response = $this->runAction($controllerName, $actionName);
            $response->send();
        } catch (HttpNotFoundException) {
            $content = $this->view->render('/errors/404page.php');
            $response = Response::html($content, 404);
            $response->send();
        }
    }

    // ルーティングの設定（ここでどのパスが来たらどのコントローラーのどのアクションを実行するかを指定する）
    private function registerRoutes(): array
    {
        return [
            '/' => [
                'middleware' => fn() => AuthMiddleware::auth(),
                'controller' => 'TestController',
                'action' => 'index',
            ],
            '/test' => [
                'middleware' => fn() => AuthMiddleware::auth(),
                'controller' => 'TestController',
                'action' => 'test',
            ],
            '/login' => [
                'middleware' => fn() => AuthMiddleware::guest(),
                'controller' => 'AuthController',
                'action' => 'index',
            ],
            '/login/auth' => [
                'middleware' => fn() => AuthMiddleware::guest(),
                'controller' => 'AuthController',
                'action' => 'auth',
            ],
            '/logout' => [
                'middleware' => fn() => AuthMiddleware::auth(),
                'controller' => 'AuthController',
                'action' => 'logout',
            ],
        ];
    }

    // ここから以下はアプリの基礎動作に関わるメソッドを定義
    // （アプリ開発時はいじらなくてOK）
    private function runAction(string $controllerName, string $actionName): Response
    {
        $controller = new $controllerName($this);
        return $controller->$actionName();
    }

    public function getDatabaseConnector(): DatabaseConnector
    {
        return $this->databaseConnector;
    }

    public function getView(): View
    {
        return $this->view;
    }
}
