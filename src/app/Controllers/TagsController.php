<?php

require_once 'app/Controllers/Controller.php';
require_once 'app/Response.php';

class TagsController extends Controller
{
    public function index(): Response
    {
        $content = $this->render('/tags/index.php');
        return Response::html($content);
    }

    public function store(): Response
    {
        $form = $_POST['tag'];

        // DBへの登録処理を記載する

        return Response::redirect('/tags');
    }
}
