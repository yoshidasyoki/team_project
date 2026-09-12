<?php

require_once 'app/Controllers/Controller.php';
require_once 'app/Response.php';

class UsersController extends Controller
{
    public function create(): Response
    {
        $content = $this->render('/users/create.php');
        return Response::html($content);
    }
}
