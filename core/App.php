<?php

namespace core;


class App
{
    public function __construct()
    {
        $router = new Router();
        $routesDefinition = include __DIR__ . '/../app/config/routes.php';
        $routesDefinition($router);

        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        $requestUri = '/' . str_replace(BASE_URL, '', $requestUri);
        $router->dispatch($requestUri, $requestMethod);
    }
}
