<?php

require_once 'app/Exceptions/HttpNotFoundException.php';

class Router
{
    public function __construct(private array $routes) {}

    public function getRoute(string $path): array
    {
        $route = $this->routes[$path] ?? null;
        if (!$route) {
            throw new HttpNotFoundException();
        }
        return $route;
    }
}
