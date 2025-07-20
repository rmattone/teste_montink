<?php

namespace core;


class Controller
{

    public $load;

    public function __construct()
    {
        $this->load = new Loader();
    }

    protected function jsonResponse(array $data = [], int $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }

    protected function error(string $message, int $statusCode = 400)
    {
        $this->jsonResponse(['error' => $message], $statusCode);
    }
    
    protected function success(array $data = [], int $statusCode = 200)
    {
        $this->jsonResponse([
            'success' => true,
            'data' => $data,
        ], $statusCode);
    }
    
}
