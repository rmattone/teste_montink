<?php

namespace core;

use app\controllers\ErrorController;

class Router
{
    private $routes = [];

    public function add(string $method, string $path, $handler): void
    {
        $this->routes[] = compact('method', 'path', 'handler');
    }

    public function dispatch(string $requestUri, string $requestMethod): void
    {
        foreach ($this->routes as $route) {
            $params = [];

            if ($this->match($route['path'], $requestUri, $params)) {
                if ($route['method'] === strtoupper($requestMethod)) {
                    if (!is_array($route['handler'])) {
                        call_user_func($route['handler'], $params);
                        return;
                    }

                    $controller = new $route['handler'][0]();
                    $method = $route['handler'][1];
                    call_user_func([$controller, $method], $params);
                    return;
                }
            }
        }
        echo '<pre>';
        var_dump($requestUri);
        var_dump($requestMethod);
        exit;
        $controller = new ErrorController();

        call_user_func([$controller, 'error404'], array_merge($params, ['requestMethod' => $requestMethod, 'requestUri' => $requestUri]));
    }

    public function match(string $routePath, string $requestUri, array &$params): bool
    {
        $routePattern = preg_replace('/\{([\w]+)\}/', '(?P<$1>[^/]+)', $routePath);
        $routePattern = '#^' . $routePattern . '$#';

        if (preg_match($routePattern, $requestUri, $matches)) {
            $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
            return true;
        }
        return false;
    }
}
