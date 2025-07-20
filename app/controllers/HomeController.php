<?php

namespace app\controllers;

use core\Controller;
use core\View;

class HomeController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('America/Sao_Paulo');
    }

    public function index()
    {
        $data = [];
        View::render('home/index', $data);
    }
}
