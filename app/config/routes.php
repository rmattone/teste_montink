<?php

use app\controllers\OrderController;
use app\controllers\ProductController;

return function(\core\Router $router) {
    $router->add('GET', '/', [ProductController::class, 'index']);
    $router->add('POST', '/products', [ProductController::class, 'create']);
    $router->add('POST', '/products/{id}', [ProductController::class, 'update']);
    
    $router->add('GET', '/order', [OrderController::class, 'index']);
    $router->add('GET', '/checkout', [OrderController::class, 'checkout']);
    $router->add('POST', '/checkout', [OrderController::class, 'create']);
  
    $router->add('GET', '/orders', [OrderController::class, 'getOrder']);
    $router->add('GET', '/orders/{id}', [OrderController::class, 'getOrder']);
};