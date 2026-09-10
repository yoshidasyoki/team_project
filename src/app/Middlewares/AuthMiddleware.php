<?php

class AuthMiddleware
{
    public static function auth(string $redirectPath): void
    {
        if (!isset($_SESSION['user_id'])) {
            Response::redirect($redirectPath)->send();
            exit;
        }
    }

    public static function guest(string $redirectPath): void
    {
        if (isset($_SESSION['user_id'])) {
            Response::redirect($redirectPath)->send();
            exit;
        }
    }
}
