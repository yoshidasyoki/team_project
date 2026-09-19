<?php

require_once 'app/View.php';
require_once 'App.php';

class Controller
{
    private View $view;
    protected DatabaseConnector $databaseConnector;
    protected PDO $dbh;

    // ★ private を protected に変更することでArticlesControllerでコンストラクタ不要になる
    public function __construct(protected App $app)
    {
        $this->view = $app->getView();
        $this->databaseConnector = $app->getDatabaseConnector();
        $this->dbh = $this->databaseConnector->getConnection();
    }

    protected function render(string $viewPath, array $data = []): string
    {
        $content = $this->view->render($viewPath, $data);
        return $content;
    }

    protected function getArticleId(): string
    {
        $queryParams = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
        return str_replace(["id="], "", $queryParams);
    }
}
