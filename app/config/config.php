<?php
require_once __DIR__ . '/../../env.php';
loadEnv(__DIR__ . '/../../.env');

define('BASE_URL', getenv('BASE_URL'));
define('APP_NAME', getenv('APP_NAME'));

function base_url($path = '') {
    $protocol = get_protocol();
    return  $protocol . $_SERVER['HTTP_HOST'] . BASE_URL . ltrim($path, '/');
}

function current_url() {
    $protocol = get_protocol();
    $host = $_SERVER['HTTP_HOST'];
    $requestUri = $_SERVER['REQUEST_URI'];
    return $protocol . $host . $requestUri;
}

function get_protocol() {
    return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
}