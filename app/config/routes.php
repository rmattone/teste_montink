<?php

use app\controllers\HomeController;

return function(\core\Router $router) {
    $router->add('GET', '/', [HomeController::class, 'index']);
};