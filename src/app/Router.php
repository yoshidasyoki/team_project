<?php

require_once 'app/Exceptions/HttpNotFoundException.php';
require_once 'app/Exceptions/MethodNotAllowedException.php';

class Router
{
    public function __construct(private array $resources) {}

    public function getRoute(string $accessPath): array
    {
        $route = null;

        foreach($this->resources as $middleware => $routeTable) {
            if (array_key_exists($accessPath, $routeTable)) {
                $route = [
                    'middleware' => $middleware,
                    ...$routeTable[$accessPath],
                ];
                break;
            }
        }

        // ルーティングテーブルに合致しなければNotFoundページを出力する
        if (!$route) {
            throw new HttpNotFoundException();
        }

        // HTTPメソッドがルーティングテーブルで定義したものと異なる場合は処理を止める
        if ($route['method'] !== $_SERVER['REQUEST_METHOD']) {
            throw new MethodNotAllowedException();
        }

        return $route;
    }
}
