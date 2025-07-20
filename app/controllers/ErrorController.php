<?php

namespace app\controllers;

use core\Controller;

class ErrorController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }


    public function error404($params)
    {
        echo "Erro 404";
    }
}
