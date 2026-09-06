<?php

require_once 'app/View.php';
require_once 'App.php';

class Controller
{
    private View $view;
    protected DatabaseConnector $databaseConnector;
    protected PDO $dbh;

    public function __construct(private App $app)
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
}
