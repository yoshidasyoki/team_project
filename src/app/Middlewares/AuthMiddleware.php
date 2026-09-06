<?php

class AuthMiddleware
{
    public static function auth(): bool
    {
        return isset($_SESSION['user_id']);
    }

    public static function guest(): bool
    {
        return !isset($_SESSION['user_id']);
    }
}
