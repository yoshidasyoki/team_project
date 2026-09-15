<?php

require_once 'app/Controllers/Controller.php';
require_once 'app/Response.php';

class TagsController extends Controller
{
    public function index(): Response
    {
        $sql = 'SELECT name FROM tags;';
        $sth = $this->dbh->prepare($sql);
        $sth->execute();
        $tags = $sth->fetchAll(PDO::FETCH_ASSOC);

        $content = $this->render('/tags/index.php', ['tags' => $tags]);
        return Response::html($content);
    }

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
