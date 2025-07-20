<?php
require_once __DIR__ . '/autoload.php';
require_once __DIR__ . '/app/config/config.php';

use core\App;

if (session_status() == PHP_SESSION_NONE) {
	session_start();
}

$app = new App();
